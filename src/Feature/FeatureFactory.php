<?php
namespace Yannickl88\FeaturesBundle\Feature;

/**
 * Factory for creating features. This will resolve if a feature is active on
 * creation.
 *
 * @author Yannick de Lange <yannick.l.88@gmail.com>
 */
final class FeatureFactory
{
    /**
     * @var FeatureContainer
     */
    private $feature_container;

    /**
     * @param FeatureContainerInterface $feature_container
     */
    public function __construct(FeatureContainerInterface $feature_container)
    {
        $this->feature_container = $feature_container;
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
     * @param string $feature_name
     * @param array  $options
     * @return Feature
     */
    public function createFeature($feature_name, array $options = [])
    {
        $resolver = new Resolver();

        foreach ($options as $name => $resolver_options) {
            $resolver->addResolver($this->feature_container->getResolver($name), $resolver_options);
        }

        return new ResolvableFeature($feature_name, $resolver);
    }
}
