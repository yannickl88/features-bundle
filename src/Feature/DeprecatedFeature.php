<?php
namespace Yannickl88\FeaturesBundle\Feature;

/**
 * Deprecated feature tag. This is injected when there are no longer resolvers
 * for a given feature. This will trigger a deprecation notice when injected.
 */
final class DeprecatedFeature implements FeatureInterface
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

        @trigger_error(
            'Feature "' . $name . '" is deprecated, please remove or configure resolvers.',
            E_USER_DEPRECATED
        );
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
