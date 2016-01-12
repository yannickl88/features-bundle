<?php
namespace Yannickl88\FeaturesBundle\Feature;

/**
 * Resolved feature. Use the self::isActive() method to check if the feature is
 * enabled for this request.
 */
final class ResolvedFeature implements Feature
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var bool
     */
    private $active;

    /**
     * @param string $name
     * @param bool   $active
     */
    public function __construct($name, $active)
    {
        $this->name   = $name;
        $this->active = $active;
    }

    /**
     * Return the name of the feature.
     *
     * @return string
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
        return $this->active;
    }
}
