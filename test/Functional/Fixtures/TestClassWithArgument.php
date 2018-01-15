<?php
namespace Yannickl88\FeaturesBundle\Functional\Fixtures;

use Yannickl88\FeaturesBundle\Feature\Feature;

class TestClassWithArgument
{
    public $feature;
    public $dummy;

    public function __construct(Feature $feature, Dummy $dummy)
    {
        $this->feature = $feature;
        $this->dummy   = $dummy;
    }
}
