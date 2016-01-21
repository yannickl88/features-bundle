<?php
namespace Yannickl88\FeaturesBundle\Twig;

use Yannickl88\FeaturesBundle\Feature\Feature;
use Yannickl88\FeaturesBundle\Feature\FeatureContainerInterface;

/**
 * @covers Yannickl88\FeaturesBundle\Twig\FeaturesExtension
 */
class FeaturesExtensionTest extends \PHPUnit_Framework_TestCase
{
    private $feature_container;

    /**
     * @var FeaturesExtension
     */
    private $features_extension;

    protected function setUp()
    {
        $this->feature_container = $this->prophesize(FeatureContainerInterface::class);

        $this->features_extension = new FeaturesExtension($this->feature_container->reveal());
    }

    public function testGetFunctions()
    {
        /* @var $functions \Twig_SimpleFunction[] */
        $functions = $this->features_extension->getFunctions();
        $feature   = $this->prophesize(Feature::class);

        $feature->isActive()->willReturn(true);

        $this->feature_container->get('foo')->willReturn($feature);

        self::assertEquals('feature', $functions[0]->getName());
        self::assertEquals(true, call_user_func($functions[0]->getCallable(), 'foo'));
    }

    public function testGetName()
    {
        self::assertEquals('features_extension', $this->features_extension->getName());
    }
}
