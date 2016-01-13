<?php
namespace Yannickl88\FeaturesBundle\Feature;

/**
 * @covers Yannickl88\FeaturesBundle\Feature\Resolver
 */
class ResolverTest extends \PHPUnit_Framework_TestCase
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

    public function testResolve()
    {
        $feature_resolver1 = $this->prophesize(FeatureResolverInterface::class);
        $feature_resolver2 = $this->prophesize(FeatureResolverInterface::class);

        $feature_resolver1->isActive(['henk'])->willReturn(true);
        $feature_resolver2->isActive(['hans'])->willReturn(false);

        $this->resolver->addResolver($feature_resolver1->reveal(), ['henk']);
        $this->resolver->addResolver($feature_resolver2->reveal(), ['hans']);

        self::assertFalse($this->resolver->resolve());
    }
}
