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
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Reference;

class AddMethodCallsPass implements CompilerPassInterface
{
    /**
     * @var array
     */
    private $config;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->config = [
            'excludes' => [],
            'traits' => [],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        $this->loadConfiguration($container);
        foreach ($this->config['traits'] as $name => $config) {
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

        foreach (['excludes', 'traits'] as $key) {
            if (false === array_key_exists($key, $config)) {
                throw new \RuntimeException(sprintf('Configuration value for "%s" is missing.', $key));
            }

            $this->config[$key] = $config[$key];
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
        if (!$container->hasDefinition($service = $config['service'])) {
            throw new \LogicException(sprintf('Referenced service definition with id "%s" for trait injection "%s" does not exist.', $service, $name));
        }

        $reference = $container->getDefinition($service);
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
        if (in_array($id, $this->config['excludes'])) {
            return false;
        }

        if ($definition->isSynthetic()) {
            return false;
        }

        if (!in_array($config['trait'], (new \ReflectionClass($definition->getClass()))->getTraitNames())) {
            return false;
        }

        if ($this->hasMethodCall($config['method'], $definition, $container)) {
            return false;
        }

        return true;
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
