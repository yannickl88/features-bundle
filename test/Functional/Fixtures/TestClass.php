<?php
namespace Yannickl88\FeaturesBundle\Functional\Fixtures;

use Yannickl88\FeaturesBundle\Feature\Feature;

class TestClass
{
    public $feature;

    public function __construct(Feature $feature)
    {
        $this->feature = $feature;
    }
}
