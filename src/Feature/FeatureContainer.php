<?php
namespace Yannickl88\FeaturesBundle\Feature;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Yannickl88\FeaturesBundle\Feature\Exception\FeatureNotFoundException;

/**
 * A container that holds all the registred features.
 *
 * @author Yannick de Lange <yannick.l.88@gmail.com>
 */
final class FeatureContainer
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var string[]
     */
    private $features;

    /**
     * @param ContainerInterface $container
     * @param string[]           $features
     */
    public function __construct(ContainerInterface $container, array $features)
    {
        $this->container = $container;
        $this->features  = $features;
    }

    /**
     * Return a features by give name. If the feature was not found, an exception is thrown.
     *
     * @return Feature
     * @throws FeatureNotFoundException when feature was not found
     */
    public function get($tag)
    {
        if (!isset($this->features[$tag])) {
            throw new FeatureNotFoundException($tag);
        }

        return $this->container->get($this->features[$tag]);
    }
}
