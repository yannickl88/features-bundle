<?php
namespace Yannickl88\FeaturesBundle\Feature\Exception;

/**
 * Exception thrown when a feature was requested but not found.
 */
class FeatureNotFoundException extends \RuntimeException
{
    public function __construct(string $feature_tag, ?\Throwable $previous = null)
    {
        parent::__construct(sprintf('Feature "%s" was not found.', $feature_tag), null, $previous);
    }
}
