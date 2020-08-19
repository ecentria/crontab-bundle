<?php

/*
 * This file is part of the ecentria group, inc. software.
 *
 * (c) 2017, ecentria group, inc.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ecentria\Bundle\CrontabBundle\DependencyInjection;

use Ecentria\Bundle\CrontabBundle\EcentriaCrontabBundle;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * Configuration
 *
 * @author Sergey Chernecov <sergey.chernecov@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Get config tree builder
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder(EcentriaCrontabBundle::ALIAS);
        $rootNode = $builder->getRootNode();

        $rootNode
            ->children()
                ->scalarNode('path')->end()
                ->scalarNode('user')->end()
                ->scalarNode('mailto')->end()
                ->arrayNode('jobs')
                    ->normalizeKeys(false)
                    ->useAttributeAsKey('jobs')
                    ->prototype('array')
                        ->prototype('array')
                            ->children()
                                ->scalarNode('description')->cannotBeEmpty()->end()
                                ->scalarNode('frequency')->cannotBeEmpty()->end()
                                ->scalarNode('command')->cannotBeEmpty()->end()
                                ->scalarNode('parameters')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $builder;
    }
}
