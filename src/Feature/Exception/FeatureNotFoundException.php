<?php
namespace Yannickl88\FeaturesBundle\Feature\Exception;

/**
 * Exception thrown when a feature was requested but not found.
 */
class FeatureNotFoundException extends \RuntimeException
{
    /**
     * @param string $feature_tag
     * @param mixed  $previous
     */
    public function __construct($feature_tag, $previous = null)
    {
        parent::__construct(sprintf('Feature "%s" was not found.', $feature_tag), null, $previous);
    }
}
