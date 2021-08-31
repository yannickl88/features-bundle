<?php
namespace Yannickl88\FeaturesBundle\Twig;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Twig\TwigFunction;
use Yannickl88\FeaturesBundle\Feature\Feature;
use Yannickl88\FeaturesBundle\Feature\FeatureContainerInterface;

/**
 * @covers \Yannickl88\FeaturesBundle\Twig\FeaturesExtension
 */
class FeaturesExtensionTest extends TestCase
{
    use ProphecyTrait;

    private $feature_container;

    /**
     * @var FeaturesExtension
     */
    private $features_extension;

    protected function setUp(): void
    {
        $this->feature_container = $this->prophesize(FeatureContainerInterface::class);

        $this->features_extension = new FeaturesExtension($this->feature_container->reveal());
    }

    public function testGetFunctions(): void
    {
        /* @var $functions TwigFunction[] */
        $functions = $this->features_extension->getFunctions();
        $feature   = $this->prophesize(Feature::class);

        $feature->isActive()->willReturn(true);

        $this->feature_container->get('foo')->willReturn($feature);

        self::assertSame('feature', $functions[0]->getName());
        self::assertSame(true, call_user_func($functions[0]->getCallable(), 'foo'));
    }

    public function testGetName(): void
    {
        self::assertSame('features_extension', $this->features_extension->getName());
    }
}
