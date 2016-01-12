<?php
namespace Yannickl88\FeaturesBundle\Feature;

/**
 * Feature tag. Use the ::isActive() method to check if the feature is enabled
 * for this request.
 */
final class Feature implements FeatureInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function isActive()
    {
        return true;
    }
}
