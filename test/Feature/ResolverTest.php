<?php
namespace Yannickl88\FeaturesBundle\Feature;

use PHPUnit\Framework\TestCase;

/**
 * @covers Yannickl88\FeaturesBundle\Feature\Resolver
 */
class ResolverTest extends TestCase
{
    /**
     * @var Resolver
     */
    private $resolver;

    protected function setUp()
    {
        $this->resolver = new Resolver();
    }

    public function testAddResolver()
    {
        $feature_resolver = $this->prophesize(FeatureResolverInterface::class);

        $feature_resolver->isActive(['henk'])->willReturn(true);

        $this->resolver->addResolver($feature_resolver->reveal(), ['henk']);
        self::assertTrue($this->resolver->resolve());
    }

    public function testResolveUnanimous()
    {
        $feature_resolver1 = $this->prophesize(FeatureResolverInterface::class);
        $feature_resolver2 = $this->prophesize(FeatureResolverInterface::class);

        $feature_resolver1->isActive(['henk'])->willReturn(false);
        $feature_resolver2->isActive(['hans'])->willReturn(true);

        $this->resolver->addResolver($feature_resolver1->reveal(), ['henk']);
        $this->resolver->addResolver($feature_resolver2->reveal(), ['hans']);

        self::assertFalse($this->resolver->resolve(Resolver::STRATEGY_UNANIMOUS));
    }

    public function testResolveAffirmative()
    {
        $feature_resolver1 = $this->prophesize(FeatureResolverInterface::class);
        $feature_resolver2 = $this->prophesize(FeatureResolverInterface::class);

        $feature_resolver1->isActive(['henk'])->willReturn(false);
        $feature_resolver2->isActive(['hans'])->willReturn(true);

        $this->resolver->addResolver($feature_resolver1->reveal(), ['henk']);
        $this->resolver->addResolver($feature_resolver2->reveal(), ['hans']);

        self::assertTrue($this->resolver->resolve(Resolver::STRATEGY_AFFIRMATIVE));
    }

    public function testResolveAffirmativeNoDecision()
    {
        $feature_resolver1 = $this->prophesize(FeatureResolverInterface::class);
        $feature_resolver2 = $this->prophesize(FeatureResolverInterface::class);

        $feature_resolver1->isActive(['henk'])->willReturn(false);
        $feature_resolver2->isActive(['hans'])->willReturn(false);

        $this->resolver->addResolver($feature_resolver1->reveal(), ['henk']);
        $this->resolver->addResolver($feature_resolver2->reveal(), ['hans']);

        self::assertFalse($this->resolver->resolve(Resolver::STRATEGY_AFFIRMATIVE));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The strategy "henk" is not supported.
     */
    public function testResolveUnknown()
    {
        $this->resolver->addResolver($this->prophesize(FeatureResolverInterface::class)->reveal(), ['henk']);
        $this->resolver->addResolver($this->prophesize(FeatureResolverInterface::class)->reveal(), ['hans']);

        self::assertFalse($this->resolver->resolve('henk'));
    }
}
