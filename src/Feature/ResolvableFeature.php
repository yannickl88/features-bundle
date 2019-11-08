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
    private $name;
    private $resolver;

    public function __construct(string $name, Resolver $resolver)
    {
        $this->name     = $name;
        $this->resolver = $resolver;
    }

    /**
     * Return the name of the feature.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function isActive(): bool
    {
        return $this->resolver->resolve();
    }
}
