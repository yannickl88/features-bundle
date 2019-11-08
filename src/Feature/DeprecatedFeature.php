<?php
namespace Yannickl88\FeaturesBundle\Feature;

/**
 * Deprecated feature tag. This is injected when there are no longer resolvers
 * for a given feature. This will trigger a deprecation notice when injected.
 *
 * @author Yannick de Lange <yannick.l.88@gmail.com>
 */
final class DeprecatedFeature implements Feature
{
    /**
     * {@inheritdoc}
     */
    public function isActive(): bool
    {
        @trigger_error(
            'Feature with no tag was used, please remove or configure the tag.',
            E_USER_NOTICE
        );

        return true;
    }
}
