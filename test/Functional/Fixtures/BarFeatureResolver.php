<?php
namespace Yannickl88\FeaturesBundle\Functional\Fixtures;

use Yannickl88\FeaturesBundle\Feature\FeatureResolverInterface;

class BarFeatureResolver implements FeatureResolverInterface
{
    public function isActive(array $options = []): bool
    {
        return in_array(13, $options, true);
    }
}
