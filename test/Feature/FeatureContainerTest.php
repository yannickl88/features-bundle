<?php
namespace Yannickl88\FeaturesBundle\Feature;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Yannickl88\FeaturesBundle\Feature\Exception\FeatureNotFoundException;
use Yannickl88\FeaturesBundle\Feature\Exception\ResolverNotFoundException;

/**
 * @covers \Yannickl88\FeaturesBundle\Feature\FeatureContainer
 */
class FeatureContainerTest extends TestCase
{
    use ProphecyTrait;

    private $container;
    private $feature;
    private $features;
    private $resolver;
    private $resolvers;

    /**
     * @var FeatureContainer
     */
    private $feature_container;

    protected function setUp(): void
    {
        $this->container = $this->prophesize(ContainerInterface::class);
        $this->feature   = $this->prophesize(Feature::class);
        $this->resolver  = $this->prophesize(FeatureResolverInterface::class);

        $this->features  = [
            'test' => 'features.tag.test'
        ];
        $this->resolvers = [
            'test' => $this->resolver->reveal()
        ];

        $this->feature_container = new FeatureContainer(
            $this->container->reveal(),
            $this->features,
            $this->resolvers
        );
    }

    public function testGet(): void
    {
        $this->container->get('features.tag.test')->willReturn($this->feature);

        self::assertSame($this->feature->reveal(), $this->feature_container->get('test'));
    }

    public function testGetUnknownFeature(): void
    {
        $this->expectException(FeatureNotFoundException::class);
        $this->feature_container->get('phpunit');
    }

    public function testGetResolver(): void
    {
        self::assertEquals($this->resolver->reveal(), $this->feature_container->getResolver('test'));
    }

    public function testGetResolverChain(): void
    {
        self::assertInstanceOf(ChainFeatureResolver::class, $this->feature_container->getResolver('chain'));
    }

    public function testCreateFeatureMissingResolver(): void
    {
        $this->expectException(ResolverNotFoundException::class);
        $this->feature_container->getResolver('foz');
    }
}
