<?php
namespace Yannickl88\FeaturesBundle\Twig;

use Yannickl88\FeaturesBundle\Feature\FeatureContainerInterface;

/**
 * Twig extention for feature support in twig templates.
 */
class FeaturesExtension extends \Twig_Extension
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
            new \Twig_SimpleFunction('feature', function ($tag) {
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
