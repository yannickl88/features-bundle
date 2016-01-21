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
     * @param string $tag
     * @return Feature
     * @throws FeatureNotFoundException when feature was not found
     */
    public function get($tag);

    /**
     * Return a resolver by given name. If the resolver was not found, an
     * exception is thrown.
     *
     * @param string $name
     * @return FeatureResolverInterface
     * @throws ResolverNotFoundException when resolver was not found
     */
    public function getResolver($name);
}
