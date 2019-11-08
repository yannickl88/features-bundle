<?php
namespace Yannickl88\FeaturesBundle\Feature;

use Yannickl88\FeaturesBundle\Feature\Exception\FeatureNotFoundException;
use Yannickl88\FeaturesBundle\Feature\Exception\ResolverNotFoundException;

/**
 * Container which holds all the features and resolvers.
 */
interface FeatureContainerInterface
{
    /**
     * Return a features by give name. If the feature was not found, an
     * exception is thrown.
     *
     * @throws FeatureNotFoundException when feature was not found
     */
    public function get(string $tag): Feature;

    /**
     * Return a resolver by given name. If the resolver was not found, an
     * exception is thrown.
     *
     * @throws ResolverNotFoundException when resolver was not found
     */
    public function getResolver(string $name): FeatureResolverInterface;
}
