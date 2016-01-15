<?php
namespace Yannickl88\FeaturesBundle\Twig;

use Yannickl88\FeaturesBundle\Feature\Feature;
use Yannickl88\FeaturesBundle\Feature\FeatureContainer;

/**
 * @covers Yannickl88\FeaturesBundle\Twig\FeaturesExtension
 */
class FeaturesExtensionTest extends \PHPUnit_Framework_TestCase
{
    private $feature;
    private $container;

    /**
     * @var FeaturesExtension
     */
    private $features_extension;

    protected function setUp()
    {
        $this->feature   = $this->prophesize(Feature::class);
        $this->container = new FeatureContainer(['foo' => $this->feature->reveal()]);

        $this->features_extension = new FeaturesExtension($this->container);
    }

    public function testGetFunctions()
    {
        /* @var $functions \Twig_SimpleFunction[] */
        $functions = $this->features_extension->getFunctions();

        $this->feature->isActive()->willReturn(true)->shouldBeCalled();

        self::assertEquals('feature', $functions[0]->getName());
        self::assertEquals(true, call_user_func($functions[0]->getCallable(), 'foo'));
    }

    public function testGetName()
    {
        self::assertEquals('features_extension', $this->features_extension->getName());
    }
}
