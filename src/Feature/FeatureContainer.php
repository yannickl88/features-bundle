<?php
namespace Yannickl88\FeaturesBundle\Feature;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Yannickl88\FeaturesBundle\Feature\Exception\FeatureNotFoundException;
use Yannickl88\FeaturesBundle\Feature\Exception\ResolverNotFoundException;

/**
 * A container that holds all the registred features.
 *
 * @author Yannick de Lange <yannick.l.88@gmail.com>
 */
final class FeatureContainer implements FeatureContainerInterface
{
    private $container;
    private $features;
    private $resolvers;

    /**
     * @param ContainerInterface         $container
     * @param string[]                   $features
     * @param FeatureResolverInterface[] $resolvers
     */
    public function __construct(ContainerInterface $container, array $features, array $resolvers)
    {
        $this->container = $container;
        $this->features  = $features;
        $this->resolvers = $resolvers;
    }

    /**
     * {@inheritdoc}
     */
    public function get($tag): Feature
    {
        if (!isset($this->features[$tag])) {
            throw new FeatureNotFoundException($tag);
        }

        return $this->container->get($this->features[$tag]);
    }

    /**
     * {@inheritdoc}
     */
    public function getResolver(string $name): FeatureResolverInterface
    {
        if ($name === 'chain') { // Special resolver.
            return new ChainFeatureResolver($this);
        }

        if (!isset($this->resolvers[$name])) {
            throw new ResolverNotFoundException($name);
        }

        return $this->resolvers[$name];
    }
}
