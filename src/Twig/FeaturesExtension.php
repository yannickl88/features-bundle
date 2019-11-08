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
    private $container;

    public function __construct(FeatureContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions(): array
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
    public function getName(): string
    {
        return 'features_extension';
    }
}
