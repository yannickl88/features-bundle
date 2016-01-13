<?php
namespace Yannickl88\FeaturesBundle\Feature;

/**
 * @covers Yannickl88\FeaturesBundle\Feature\FeatureFactory
 */
class FeatureFactoryTest extends \PHPUnit_Framework_TestCase
{
    private $resolvers;

    private $resolver1;
    private $resolver2;

    /**
     * @var FeatureFactory
     */
    private $feature_factory;

    protected function setUp()
    {
        $this->resolver1 = $this->prophesize(FeatureResolverInterface::class);
        $this->resolver2 = $this->prophesize(FeatureResolverInterface::class);

        $this->resolvers = [
            'foo' => $this->resolver1->reveal(),
            'bar' => $this->resolver2->reveal(),
        ];

        $this->feature_factory = new FeatureFactory(
            $this->resolvers
        );
    }

    public function testCreateFeature()
    {
        $feature = $this->feature_factory->createFeature('foobar', ['foo' => [], 'bar' => []]);

        self::assertInstanceOf(ResolvableFeature::class, $feature);
        self::assertEquals('foobar', $feature->getName());
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Resolver "foz" was not found, did you forget to tag it with "features.resolver".
     */
    public function testCreateFeatureMissingResolver()
    {
        $this->feature_factory->createFeature('foobar', ['foz' => []]);
    }
}
