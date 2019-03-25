<?php
namespace Yannickl88\FeaturesBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Yannickl88\FeaturesBundle\Feature\FeatureContainerInterface;

/**
 * Twig extension for feature support in twig templates.
 */
class FeaturesExtension extends AbstractExtension
{
    /**
     * @var FeatureContainerInterface
     */
    private $container;

    /**
     * @param FeatureContainerInterface $container
     */
    public function __construct(FeatureContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('feature', function ($tag) {
                return $this->container->get($tag)->isActive();
            }),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'features_extension';
    }
}
