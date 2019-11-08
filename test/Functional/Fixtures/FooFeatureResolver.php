<?php
namespace Yannickl88\FeaturesBundle\Functional\Fixtures;

use Yannickl88\FeaturesBundle\Feature\FeatureResolverInterface;

class FooFeatureResolver implements FeatureResolverInterface
{
    public function isActive(array $options = []): bool
    {
        return in_array(42, $options, true);
    }
}
