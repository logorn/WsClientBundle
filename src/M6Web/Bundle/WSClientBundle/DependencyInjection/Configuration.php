<?php

namespace M6Web\Bundle\WSClientBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('m6_ws_client');

        $rootNode
            ->children()
                ->booleanNode('disable_data_collector')->defaultValue(false)->end()
            ->end();
        $rootNode
            ->children()
                ->arrayNode('clients')

                ->requiresAtLeastOneElement()
                ->useAttributeAsKey('alias')
                ->prototype('array')
                    ->children()
                        ->scalarNode('base_url')->end()
                        ->variableNode('config')
                            ->validate()
                                ->ifTrue(function ($v) {
                                    return !is_array($v);
                                })
                                ->thenInvalid('Invalid value %s for client config, it must be an array.')
                            ->end()
                        ->end()
                        ->arrayNode('cache')
                            ->children()
                                ->scalarNode('ttl')->defaultValue(86400)->end()
                                ->booleanNode('force_request_ttl')->defaultValue(false)->end()
                                ->scalarNode('service')->end()
                                ->scalarNode('adapter')->end()
                                ->scalarNode('storage')->end()
                                ->scalarNode('subscriber')->end()
                                ->arrayNode('can_cache')
                                    ->children()
                                        ->scalarNode('class')->end()
                                        ->scalarNode('method')->end()
                                    ->end()
                                ->end()
                                ->arrayNode('options')
                                    ->children()
                                        ->booleanNode('validate')->end()
                                        ->booleanNode('purge')->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()

            ->end();

        return $treeBuilder;
    }
}
