<?php

/*
 * This file is part of the FermioTraitInjectionBundle package.
 *
 * (c) Pierre Minnieur <pierre@ferm.io>
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Fermio\Bundle\TraitInjectionBundle\Tests\DependencyInjection\Compiler;

use Fermio\Bundle\TraitInjectionBundle\DependencyInjection\Compiler\AddMethodCallsPass;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class AddMethodCallsPassTest extends \PHPUnit_Framework_TestCase
{
    public function testCompilerPass()
    {
        $this->assertInstanceOf(
            'Symfony\\Component\\DependencyInjection\\Compiler\\CompilerPassInterface',
            new AddMethodCallsPass()
        );
    }

    public function testProcess()
    {
        $traits = [
            'container_aware' => [
                'trait'   => 'Fermio\Bundle\TraitInjectionBundle\Traits\ContainerAware',
                'method'  => 'setContainer',
                'service' => 'service_container',
                'invalid' => ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE,
            ],
            'event_dispatcher_aware' => [
                'trait'   => 'Fermio\Bundle\TraitInjectionBundle\Traits\EventDispatcherAware',
                'method'  => 'setEventDispatcher',
                'service' => 'event_dispatcher',
                'invalid' => ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE,
            ],
            'form_builder_aware' => [
                'trait'   => 'Fermio\Bundle\TraitInjectionBundle\Traits\FormBuilderAware',
                'method'  => 'setFormBuilder',
                'service' => 'form_builder',
                'invalid' => ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE,
            ],
            'kernel_aware' => [
                'trait'   => 'Fermio\Bundle\TraitInjectionBundle\Traits\KernelAware',
                'method'  => 'setKernel',
                'service' => 'kernel',
                'invalid' => ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE,
            ],
            'logger_aware' => [
                'trait'   => 'Fermio\Bundle\TraitInjectionBundle\Traits\LoggerAware',
                'method'  => 'setLogger',
                'service' => 'logger',
                'invalid' => ContainerInterface::IGNORE_ON_INVALID_REFERENCE,
            ],
            'request_aware' => [
                'trait'   => 'Fermio\Bundle\TraitInjectionBundle\Traits\RequestAware',
                'method'  => 'setRequest',
                'service' => 'request',
                'invalid' => ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE,
            ],
            'router_aware' => [
                'trait'   => 'Fermio\Bundle\TraitInjectionBundle\Traits\RouterAware',
                'method'  => 'setRouter',
                'service' => 'router',
                'invalid' => ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE,
            ],
            'security_context_aware' => [
                'trait'   => 'Fermio\Bundle\TraitInjectionBundle\Traits\SecurityContextAware',
                'method'  => 'setSecurityContext',
                'service' => 'security_context',
                'invalid' => ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE,
            ],
            'swiftmailer_aware' => [
                'trait'   => 'Fermio\Bundle\TraitInjectionBundle\Traits\SwiftmailerAware',
                'method'  => 'setMailer',
                'service' => 'mailer',
                'invalid' => ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE,
            ],
            'templating_aware' => [
                'trait'   => 'Fermio\Bundle\TraitInjectionBundle\Traits\TemplatingAware',
                'method'  => 'setTemplating',
                'service' => 'templating',
                'invalid' => ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE,
            ],
            'translator_aware' => [
                'trait'   => 'Fermio\Bundle\TraitInjectionBundle\Traits\TranslatorAware',
                'method'  => 'setTranslator',
                'service' => 'translator',
                'invalid' => ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE,
            ],
            'validator_aware' => [
                'trait'   => 'Fermio\Bundle\TraitInjectionBundle\Traits\ValidatorAware',
                'method'  => 'setValidator',
                'service' => 'validator',
                'invalid' => ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE,
            ],
        ];

        $container = new ContainerBuilder();
        $container->setParameter('fermio_trait_injection.config', [
            'excludes' => [],
            'traits' => $traits,
        ]);

        (new XmlFileLoader($container, new FileLocator(__DIR__.'/../../Resources/config')))->load('services.xml');
        (new AddMethodCallsPass())->process($container);

        foreach ($traits as $name => $config) {
            $this->assertDefinition($container, $name, $config['method'], $config['service'], $config['invalid']);
        }
    }

    public function testServiceSkippingById()
    {
        $container = new ContainerBuilder();
        $container->setParameter('fermio_trait_injection.config', [
            'excludes' => ['container_aware'],
            'traits' => [
                'container' => [
                    'trait'   => 'Fermio\Bundle\TraitInjectionBundle\Traits\ContainerAware',
                    'method'  => 'setContainer',
                    'service' => 'service_container',
                    'invalid' => ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE,
                ],
            ],
        ]);

        (new XmlFileLoader($container, new FileLocator(__DIR__.'/../../Resources/config')))->load('services.xml');
        (new AddMethodCallsPass())->process($container);

        $this->assertFalse($container->getDefinition('container_aware')->hasMethodCall('setContainer'));
    }

    public function testTraitsParameterIsRemoved()
    {
        $container = new ContainerBuilder();
        $container->setParameter('fermio_trait_injection.config', [
            'excludes' => [],
            'traits' => [
                'container' => [
                    'trait'   => 'Fermio\Bundle\TraitInjectionBundle\Traits\ContainerAware',
                    'method'  => 'setContainer',
                    'service' => 'service_container',
                    'invalid' => ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE,
                ],
            ],
        ]);

        (new XmlFileLoader($container, new FileLocator(__DIR__.'/../../Resources/config')))->load('services.xml');
        (new AddMethodCallsPass())->process($container);

        $this->assertFalse($container->hasParameter('fermio_trait_injection.config'));
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage Referenced service definition with id "does_not_exist" for trait injection "container" does not exist
     */
    public function testProcessWithUnknownDefinitionAsReference()
    {
        $container = new ContainerBuilder();
        $container->setParameter('fermio_trait_injection.config', [
            'excludes' => [],
            'traits' => [
                'container' => [
                    'trait'   => 'Fermio\Bundle\TraitInjectionBundle\Traits\ContainerAware',
                    'method'  => 'setContainer',
                    'service' => 'does_not_exist',
                    'invalid' => ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE,
                ],
            ],
        ]);

        (new XmlFileLoader($container, new FileLocator(__DIR__.'/../../Resources/config')))->load('services.xml');
        (new AddMethodCallsPass())->process($container);
    }

    /**
     * Assert a method call with a given reference for a service id.
     *
     * @param ContainerBuilder $container The container builder
     * @param string           $id        The modified service definition identifier
     * @param string           $method    The method call to expect
     * @param string           $reference The references service definition identifier
     * @param integer          $invalid   The invalid behavior
     */
    private function assertDefinition(ContainerBuilder $container, $id, $method, $reference, $invalid)
    {
        $this->assertTrue($container->hasDefinition($id));
        $this->assertTrue($container->hasDefinition($reference));

        $hasMethodCall = false;
        $definition = $container->getDefinition($id);

        // check method call somewhat thoroughly
        $this->assertTrue($definition->hasMethodCall($method));
        foreach ($definition->getMethodCalls() as $methodCall) {
            if ($methodCall[0] == $method && $methodCall[1][0] == $reference && $methodCall[1][0]->getInvalidBehavior() == $invalid) {
                $hasMethodCall = true;
            }
        }

        $this->assertTrue($hasMethodCall);
        $this->assertEquals($definition->getScope(), $container->getDefinition($reference)->getScope());
    }
}
