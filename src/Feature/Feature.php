<?php
namespace Yannickl88\FeaturesBundle\Feature;

/**
 * Implementations represent a feature that can be active or not.
 *
 * @author Yannick de Lange <yannick.l.88@gmail.com>
 */
interface Feature
{
    /**
     * Check if the feature is active for the current request.
     */
    public function isActive(): bool;
}
