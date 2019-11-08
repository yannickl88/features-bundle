<?php
namespace Yannickl88\FeaturesBundle\Feature;

/**
 * Resolver for a feature. This will be able to resolve if a feature is active
 * or not. Resolvers are linked to a tag in the config. Multiple resolvers can
 * be linked to a single tag.
 *
 * @author Yannick de Lange <yannick.l.88@gmail.com>
 */
interface FeatureResolverInterface
{
    /**
     * Check if this feature is active.
     */
    public function isActive(array $options = []): bool;
}
