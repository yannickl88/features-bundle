<?php
namespace Yannickl88\FeaturesBundle\Feature;

/**
 * Resolved feature. Use the self::isActive() method to check if the feature is
 * enabled for this request.
 *
 * @author Yannick de Lange <yannick.l.88@gmail.com>
 */
final class ResolvableFeature implements Feature
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var Resolver
     */
    private $resolver;

    /**
     * @param string $name
     * @param bool   $active
     */
    public function __construct($name, Resolver $resolver)
    {
        $this->name     = $name;
        $this->resolver = $resolver;
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
        return $this->resolver->resolve();
    }
}
