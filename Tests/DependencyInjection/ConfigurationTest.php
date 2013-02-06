<?php

/*
 * This file is part of the FermioTraitInjectionBundle package.
 *
 * (c) Pierre Minnieur <pierre@ferm.io>
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Fermio\Bundle\TraitInjectionBundle\Tests\DependencyInjection;

use Fermio\Bundle\TraitInjectionBundle\DependencyInjection\Configuration;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    public function testConfiguration()
    {
        $this->assertInstanceOf(
            'Symfony\\Component\\Config\\Definition\\ConfigurationInterface',
            new Configuration()
        );
    }

    public function testGetConfigTreeBuilder()
    {
        $this->assertInstanceOf(
            'Symfony\\Component\\Config\\Definition\\Builder\\TreeBuilder',
            (new Configuration())->getConfigTreeBuilder()
        );
    }

    public function testExcludesDefaultConfiguration()
    {
        $config = (new Processor())->process((new Configuration())->getConfigTreeBuilder()->buildTree(), ['fermio_trait_injection' => []]);
        $this->assertInternalType('array', $config['excludes']);
        $this->assertEmpty($config['excludes']);
    }

    public function testExcludesConfiguration()
    {
        $configs = [
            'fermio_trait_injection' => [
                'excludes' => [
                    'my.service.id',
                ],
            ],
        ];

        $config = (new Processor())->process((new Configuration())->getConfigTreeBuilder()->buildTree(), $configs);
        $this->assertEquals(['my.service.id',], $config['excludes']);
    }

    public function testInvalidBehaviorExceptionStringConversion()
    {
        $configs = [
            'fermio_trait_injection' => [
                'traits' => [
                    'container' => [
                        'trait' => 'Fermio\\Bundle\\TraitInjectionBundle\\Traits\\ContainerAware',
                        'method' => 'setContainer',
                        'service' => 'service_container',
                        'invalid' => 'exception',
                    ],
                ],
            ],
        ];

        $config = (new Processor())->process((new Configuration())->getConfigTreeBuilder()->buildTree(), $configs);
        $this->assertEquals(ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE, $config['traits']['container']['invalid']);
    }

    public function testInvalidBehaviorNullStringConversion()
    {
        $configs = [
            'fermio_trait_injection' => [
                'traits' => [
                    'container' => [
                        'trait' => 'Fermio\\Bundle\\TraitInjectionBundle\\Traits\\ContainerAware',
                        'method' => 'setContainer',
                        'service' => 'service_container',
                        'invalid' => 'null',
                    ],
                ],
            ],
        ];

        $config = (new Processor())->process((new Configuration())->getConfigTreeBuilder()->buildTree(), $configs);
        $this->assertEquals(ContainerInterface::NULL_ON_INVALID_REFERENCE, $config['traits']['container']['invalid']);
    }

    public function testInvalidBehaviorIgnoreStringConversion()
    {
        $configs = [
            'fermio_trait_injection' => [
                'traits' => [
                    'container' => [
                        'trait' => 'Fermio\\Bundle\\TraitInjectionBundle\\Traits\\ContainerAware',
                        'method' => 'setContainer',
                        'service' => 'service_container',
                        'invalid' => 'ignore',
                    ],
                ],
            ],
        ];

        $config = (new Processor())->process((new Configuration())->getConfigTreeBuilder()->buildTree(), $configs);
        $this->assertEquals(ContainerInterface::IGNORE_ON_INVALID_REFERENCE, $config['traits']['container']['invalid']);
    }

    /**
     * @expectedException Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     * @expectedExceptionMessage Trait "Does\Not\Exist" does not exist.
     */
    public function testNonExistingTrait()
    {
        $configs = [
            'fermio_trait_injection' => [
                'traits' => [
                    'trait_does_not_exist' => [
                        'trait' => 'Does\\Not\\Exist',
                    ],
                ],
            ],
        ];

        (new Processor())->process((new Configuration())->getConfigTreeBuilder()->buildTree(), $configs);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Behavior "does_not_exist" is not available
     */
    public function testNonExistingInvalidBehaviorByString()
    {
        $configs = [
            'fermio_trait_injection' => [
                'traits' => [
                    'invalid_string_does_not_exist' => [
                        'trait' => 'Fermio\\Bundle\\TraitInjectionBundle\\Traits\\ContainerAware',
                        'method' => 'setContainer',
                        'service' => 'service_container',
                        'invalid' => 'does_not_exist',
                    ],
                ],
            ],
        ];

        (new Processor())->process((new Configuration())->getConfigTreeBuilder()->buildTree(), $configs);
    }

    /**
     * @expectedException Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     * @expectedExceptionMessage Behavior "4" is not available
     */
    public function testNonExistingInvalidBehaviorByInteger()
    {
        $configs = [
            'fermio_trait_injection' => [
                'traits' => [
                    'invalid_integer_does_not_exist' => [
                        'trait' => 'Fermio\\Bundle\\TraitInjectionBundle\\Traits\\ContainerAware',
                        'method' => 'setContainer',
                        'service' => 'service_container',
                        'invalid' => 4,
                    ],
                ],
            ],
        ];

        (new Processor())->process((new Configuration())->getConfigTreeBuilder()->buildTree(), $configs);
    }

    /**
     * @expectedException Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     * @expectedExceptionMessage Method "Fermio\Bundle\TraitInjectionBundle\Traits\ContainerAware::setServiceContainer" does not exist
     */
    public function testNonExistingMethod()
    {
        $configs = [
            'fermio_trait_injection' => [
                'traits' => [
                    'method_does_not_exist' => [
                        'trait' => 'Fermio\\Bundle\\TraitInjectionBundle\\Traits\\ContainerAware',
                        'method' => 'setServiceContainer',
                        'service' => 'service_container',
                        'invalid' => 'ignore',
                    ],
                ],
            ],
        ];

        (new Processor())->process((new Configuration())->getConfigTreeBuilder()->buildTree(), $configs);
    }
}
