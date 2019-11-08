<?php
namespace Yannickl88\FeaturesBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Features configuration. Basic example of the config is:
 *
 * features:
 *     tags:
 *         my_feature:
 *             my_resolver: ~
 *
 * @author Yannick de Lange <yannick.l.88@gmail.com>
 */
final class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder('features');

        $builder->getRootNode()
            ->children()
                ->arrayNode('tags')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->prototype('array')
                            ->prototype('variable')->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $builder;
    }
}
