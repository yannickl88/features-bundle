<?php
namespace Yannickl88\FeaturesBundle\Feature;

/**
 * Resolver for features.
 */
class FeatureFactory
{
    /**
     * @param string $name
     * @return Feature
     */
    public function createFeature($name)
    {
        return new Feature($name);
    }
}
