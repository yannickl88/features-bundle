<?php
namespace Yannickl88\FeaturesBundle\Feature\Exception;

/**
 * Exception thrown when a resolver was requested but not found.
 */
class ResolverNotFoundException extends \RuntimeException
{
    /**
     * @param string $resolver_name
     * @param mixed  $previous
     */
    public function __construct($resolver_name, $previous = null)
    {
        parent::__construct(sprintf(
            'Resolver "%s" was not found, did you forget to tag it with "features.resolver"?',
            $resolver_name
        ), null, $previous);
    }
}
