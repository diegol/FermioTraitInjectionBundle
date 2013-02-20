<?php

/*
 * This file is part of the FermioTraitInjectionBundle package.
 *
 * (c) Pierre Minnieur <pierre@ferm.io>
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Fermio\Bundle\TraitInjectionBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Reference;

class AddMethodCallsPass implements CompilerPassInterface
{
    /**
     * @var array
     */
    private $excludes;

    /**
     * @var array
     */
    private $traits;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->excludes = [];
        $this->traits = [];
    }

    /**
     * Returns the list of default traits configuration.
     *
     * @return array The list of default traits configuration
     */
    public static function getDefaultTraitsConfiguration()
    {
        return [
            'fermio.container_aware' => [
                'trait'   => 'Fermio\\Bundle\\TraitInjectionBundle\\Traits\\ContainerAware',
                'method'  => 'setContainer',
                'service' => 'service_container',
                'invalid' => ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE,
            ],
            'fermio.event_dispatcher_aware' => [
                'trait'   => 'Fermio\\Bundle\\TraitInjectionBundle\\Traits\\EventDispatcherAware',
                'method'  => 'setEventDispatcher',
                'service' => 'event_dispatcher',
                'invalid' => ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE,
            ],
            'fermio.form_factory_aware' => [
                'trait'   => 'Fermio\\Bundle\\TraitInjectionBundle\\Traits\\FormFactoryAware',
                'method'  => 'setFormFactory',
                'service' => 'form.factory',
                'invalid' => ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE,
            ],
            'fermio.kernel_aware' => [
                'trait'   => 'Fermio\\Bundle\\TraitInjectionBundle\\Traits\\KernelAware',
                'method'  => 'setKernel',
                'service' => 'kernel',
                'invalid' => ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE,
            ],
            'fermio.logger_aware' => [
                'trait'   => 'Fermio\\Bundle\\TraitInjectionBundle\\Traits\\LoggerAware',
                'method'  => 'setLogger',
                'service' => 'logger',
                'invalid' => ContainerInterface::IGNORE_ON_INVALID_REFERENCE,
            ],
            'fermio.request_aware' => [
                'trait'   => 'Fermio\\Bundle\\TraitInjectionBundle\\Traits\\RequestAware',
                'method'  => 'setRequest',
                'service' => 'request',
                'invalid' => ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE,
            ],
            'fermio.router_aware' => [
                'trait'   => 'Fermio\\Bundle\\TraitInjectionBundle\\Traits\\RouterAware',
                'method'  => 'setRouter',
                'service' => 'router',
                'invalid' => ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE,
            ],
            'fermio.security_context_aware' => [
                'trait'   => 'Fermio\\Bundle\\TraitInjectionBundle\\Traits\\SecurityContextAware',
                'method'  => 'setSecurityContext',
                'service' => 'security.context',
                'invalid' => ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE,
            ],
            'fermio.swiftmailer_aware' => [
                'trait'   => 'Fermio\\Bundle\\TraitInjectionBundle\\Traits\\SwiftmailerAware',
                'method'  => 'setMailer',
                'service' => 'mailer',
                'invalid' => ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE,
            ],
            'fermio.templating_aware' => [
                'trait'   => 'Fermio\\Bundle\\TraitInjectionBundle\\Traits\\TemplatingAware',
                'method'  => 'setTemplating',
                'service' => 'templating',
                'invalid' => ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE,
            ],
            'fermio.translator_aware' => [
                'trait'   => 'Fermio\\Bundle\\TraitInjectionBundle\\Traits\\TranslatorAware',
                'method'  => 'setTranslator',
                'service' => 'translator',
                'invalid' => ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE,
            ],
            'fermio.validator_aware' => [
                'trait'   => 'Fermio\\Bundle\\TraitInjectionBundle\\Traits\\ValidatorAware',
                'method'  => 'setValidator',
                'service' => 'validator',
                'invalid' => ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE,
            ],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        $this->loadConfiguration($container);
        foreach ($this->traits as $name => $config) {
            $this->addMethodCalls($name, $config, $container);
        }
    }

    /**
     * Loads the configuration and removes it from the container builder
     * parameter bag.
     *
     * @param  ContainerBuilder $container The container builder
     * @return void
     */
    private function loadConfiguration(ContainerBuilder $container)
    {
        $config = $container->getParameter('fermio_trait_injection.config');
        $container->getParameterBag()->remove('fermio_trait_injection.config');

        $this->excludes = $config['excludes'];
        $this->traits = $config['traits'];

        if (true === $config['defaults']) {
            $this->mergeDefaultTraitsConfiguration();
        }
    }

    /**
     * Merges the default trait configurations list.
     *
     * @return void
     */
    private function mergeDefaultTraitsConfiguration()
    {
        foreach (self::getDefaultTraitsConfiguration() as $name => $config) {
            if (!array_key_exists($name, $this->traits)) {
                $this->traits[$name] = $config;
                continue;
            }

            foreach ($config as $key => $value) {
                if (!array_key_exists($key, $this->traits[$name])) {
                    $this->traits[$name][$key] = $value;
                }
            }
        }
    }

    /**
     * Adds a method call configuration for referenced service injection on
     * service definitions where the class implements the given trait.
     *
     * @param  string           $name      The trait injection name
     * @param  array            $config    The trait injection configuration
     * @param  ContainerBuilder $container The container builder
     * @return void
     *
     * @throws \LogicException If the referenced service to be injected does not exist
     */
    private function addMethodCalls($name, array $config, ContainerBuilder $container)
    {
        if (!$container->hasDefinition($service = $config['service']) && !$container->hasAlias($service)) {
            throw new \LogicException(sprintf('Referenced service definition with id "%s" for trait injection "%s" does not exist.', $service, $name));
        }

        // reference may be an aliased service
        $reference = $container->hasAlias($service)
            ? $container->getDefinition($container->getAlias($service))
            : $container->getDefinition($service)
        ;

        $arguments = [new Reference($service, $config['invalid'])];
        foreach ($this->findDefinitions($config, $container) as $definition) {
            $definition->addMethodCall($config['method'], $arguments);
            $definition->setScope($reference->getScope());
        }
    }

    /**
     * Returns a list of definitions where the class implements the given trait.
     *
     * @param  array            $config    The trait injection configuration
     * @param  ContainerBuilder $container The container builder
     * @return Definition[]     The list of definitions implementing the trait
     */
    private function findDefinitions(array $config, ContainerBuilder $container)
    {
        $definitions = [];
        foreach ($container->getDefinitions() as $id => $definition) {
            if ($this->isTraitInjectable($config, $id, $definition, $container)) {
                $definitions[] = $definition;
            }
        }

        return $definitions;
    }

    /**
     * Whether the service configuration has an injectable trait.
     *
     * @param  array            $config     The trait injection configuration
     * @param  string           $id         The service identifier
     * @param  Definition       $definition The service definition
     * @param  ContainerBuilder $container  The container builder
     * @return boolean          Whether the service configuration has an injectable trait
     */
    private function isTraitInjectable(array $config, $id, Definition $definition, $container)
    {
        if (in_array($id, $this->excludes)) {
            return false;
        }

        if ($definition->isSynthetic()) {
            return false;
        }

        if (!$this->usesTrait(new \ReflectionClass($definition->getClass()), $config['trait'])) {
            return false;
        }

        if ($this->hasMethodCall($config['method'], $definition, $container)) {
            return false;
        }

        return true;
    }

    /**
     * Whether a class uses a trait (or a trait used uses the trait).
     *
     * @param  string  $class The fully qualified class name
     * @param  string  $trait The fully qualified class name of the trait
     * @return boolean Whether the classes uses the trait
     */
    private function usesTrait(\ReflectionClass $class, $trait)
    {
        if (in_array($trait, $class->getTraitNames())) {
            return true;
        }

        // check trait inheritance (trait uses trait)
        foreach ($class->getTraits() as $reflTrait) {
            if ($this->usesTrait($reflTrait, $trait)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Whether the method call is already configured.
     *
     * @param  string           $method     The method
     * @param  Definition       $definition The service definition
     * @param  ContainerBuilder $container  The container builder
     * @return boolean          Whether the method call is already configured
     */
    private function hasMethodCall($method, Definition $definition, ContainerBuilder $container)
    {
        if ($definition->hasMethodCall($method)) {
            return true;
        }

        // recursive check for parent definitions
        if ($definition instanceof DefinitionDecorator) {
            return $this->hasMethodCall($method, $container->getDefinition($definition->getParent()), $container);
        }

        return false;
    }
}
