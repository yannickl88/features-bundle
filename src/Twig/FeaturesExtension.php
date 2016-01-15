<?php
namespace Yannickl88\FeaturesBundle\Twig;

use Yannickl88\FeaturesBundle\Feature\FeatureContainer;

/**
 * Twig extention for feature support in twig templates.
 */
class FeaturesExtension extends \Twig_Extension
{
    /**
     * @var FeatureContainer
     */
    private $container;

    /**
     * @param FeatureContainer $container
     */
    public function __construct(FeatureContainer $container)
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
