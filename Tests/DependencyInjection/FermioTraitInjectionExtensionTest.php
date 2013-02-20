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

use Fermio\Bundle\TraitInjectionBundle\DependencyInjection\FermioTraitInjectionExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FermioTraitInjectionExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testAlias()
    {
        $this->assertEquals(
            'fermio_trait_injection',
            (new FermioTraitInjectionExtension())->getAlias()
        );
    }

    public function testLoad()
    {
        $configs = [
            'fermio_trait_injection' => [
                'defaults' => false,
                'excludes' => [],
                'traits' => [
                    'container' => [
                        'trait'   => 'Fermio\Bundle\TraitInjectionBundle\Traits\ContainerAware',
                        'method'  => 'setContainer',
                        'service' => 'service_container',
                    ],
                ],
            ],
        ];

        (new FermioTraitInjectionExtension())->load($configs, $container = new ContainerBuilder());

        $this->assertEquals(
            array_merge_recursive(
                $configs['fermio_trait_injection'],
                [
                    'traits' => [
                        'container' => [
                            'invalid' => ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE
                        ],
                    ],
                ]
            ),
            $container->getParameter('fermio_trait_injection.config')
        );
    }
}
