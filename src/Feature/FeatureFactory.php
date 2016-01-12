<?php
namespace Yannickl88\FeaturesBundle\Feature;

/**
 * Factory for creating features. This will resolve if a feature is active on
 * creation.
 */
class FeatureFactory
{
    /**
     * @var FeatureResolverInterface[]
     */
    private $resolvers;

    /**
     * @param FeatureResolverInterface[] $resolvers
     */
    public function __construct(array $resolvers)
    {
        $this->resolvers = $resolvers;
    }

    /**
     * Create and resolve a feature based on the name and the options.
     *
     * Options array should be structured as follows:
     * [
     *     resolver1 => [<resolver specific options>],
     *     resolver2 => [<resolver specific options>],
     *     // etc.
     * ]
     *
     * @param string $name
     * @return Feature
     */
    public function createFeature($name, array $options = [])
    {
        $active = true;

        foreach ($options as $resolver => $resolver_options) {
            if (!isset($this->resolvers[$resolver])) {
                throw new \RuntimeException(sprintf(
                    'Resolver "%s" was not found, did you forget to tag it with "features.resolver".',
                    $resolver
                ));
            }

            if (false === ($active = $active && $this->resolvers[$resolver]->isActive($resolver_options))) {
                // No need to keep resolving when active becomes false.
                break;
            }
        }

        return new ResolvedFeature($name, $active);
    }
}
