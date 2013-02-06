<?php

/*
 * This file is part of the FermioTraitInjectionBundle package.
 *
 * (c) Pierre Minnieur <pierre@ferm.io>
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Fermio\Bundle\TraitInjectionBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * @var array
     */
    protected static $behaviors = [
        ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE => 'exception',
        ContainerInterface::NULL_ON_INVALID_REFERENCE => 'null',
        ContainerInterface::IGNORE_ON_INVALID_REFERENCE => 'ignore',
    ];

    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $treeBuilder
            ->root('fermio_trait_injection')
            ->fixXmlConfig('exclude', 'excludes')
            ->fixXmlConfig('trait', 'traits')
            ->children()
                ->arrayNode('excludes')
                    ->defaultValue([])
                    ->prototype('scalar')
                        ->example(['my.service.id'])
                    ->end()
                ->end()
                ->arrayNode('traits')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('trait')
                                ->example('Fermio\\Bundle\\TraitInjectionBundle\\Traits\\ContainerAware')
                                ->isRequired()
                                ->cannotBeEmpty()
                                ->validate()
                                    ->ifTrue(function($trait) {
                                        return !trait_exists($trait);
                                    })
                                    ->then(function($trait) {
                                        throw new \InvalidArgumentException(sprintf('Trait "%s" does not exist.', $trait));
                                    })
                                ->end()
                            ->end()
                            ->scalarNode('method')
                                ->example('setContainer')
                                ->isRequired()
                                ->cannotBeEmpty()
                            ->end()
                            ->scalarNode('service')
                                ->example('service_container')
                                ->isRequired()
                                ->cannotBeEmpty()
                            ->end()
                            ->scalarNode('invalid')
                                ->example('exception')
                                ->defaultValue(ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE)
                                ->beforeNormalization()
                                    ->ifTrue(function($invalid) {
                                        return is_string($invalid);
                                    })
                                    ->then(function($invalid) {
                                        if (false === $integer = array_search(strtolower(trim($invalid)), self::$behaviors)) {
                                            throw new \InvalidArgumentException(sprintf('Behavior "%s" is not available, please choose one of: %s.', $invalid, implode(', ', array_values(self::$behaviors))));
                                        }

                                        return $integer;
                                    })
                                ->end()
                            ->end()
                        ->end()
                        ->validate()
                            ->ifTrue(function($config) {
                                return !(new \ReflectionClass($config['trait']))->hasMethod($config['method']);
                            })
                            ->then(function($config) {
                                throw new \InvalidArgumentException(sprintf('Method "%s::%s" does not exist.', $config['trait'], $config['method']));
                            })
                        ->end()
                        ->validate()
                            ->ifTrue(function($config) {
                                return !in_array($config['invalid'], array_keys(self::$behaviors)) && !in_array($config['invalid'], array_values(self::$behaviors));
                            })
                            ->then(function($config) {
                                throw new \InvalidArgumentException(sprintf('Behavior "%s" is not available, please choose one of: %s.', (string) $config['invalid'], implode(', ', array_values(self::$behaviors))));
                            })
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
