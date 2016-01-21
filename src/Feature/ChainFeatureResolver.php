<?php
namespace Yannickl88\FeaturesBundle\Feature;

use Yannickl88\FeaturesBundle\Feature\FeatureResolverInterface;

/**
 * Resolve if a feature is enabled for the given environments.
 */
class ChainFeatureResolver implements FeatureResolverInterface
{
    /**
     * @var FeatureContainerInterface
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
     * {@inheritdoc}
     */
    public function isActive(array $options = [])
    {
        $resolver = new Resolver();

        foreach ($options as $name => $resolver_options) {
            $resolver->addResolver($this->feature_container->getResolver($name), $resolver_options);
        }

        return $resolver->resolve(Resolver::STRATEGY_AFFIRMATIVE);
    }
}
