<?php
namespace Yannickl88\FeaturesBundle\Feature;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

/**
 * @covers \Yannickl88\FeaturesBundle\Feature\FeatureFactory
 */
class FeatureFactoryTest extends TestCase
{
    use ProphecyTrait;

    private $feature_container;

    /**
     * @var FeatureFactory
     */
    private $feature_factory;

    protected function setUp(): void
    {
        $this->feature_container = $this->prophesize(FeatureContainerInterface::class);

        $this->feature_factory = new FeatureFactory(
            $this->feature_container->reveal()
        );
    }

    public function testCreateFeature(): void
    {
        $resolver1 = $this->prophesize(FeatureResolverInterface::class);
        $resolver2 = $this->prophesize(FeatureResolverInterface::class);

        $this->feature_container->getResolver('foo')->willReturn($resolver1);
        $this->feature_container->getResolver('bar')->willReturn($resolver2);

        $feature = $this->feature_factory->createFeature('foobar', ['foo' => [], 'bar' => []]);

        self::assertInstanceOf(ResolvableFeature::class, $feature);
        self::assertSame('foobar', $feature->getName());
    }
}
