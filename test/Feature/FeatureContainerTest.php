<?php
namespace Yannickl88\FeaturesBundle\Feature;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @covers Yannickl88\FeaturesBundle\Feature\FeatureContainer
 */
class FeatureContainerTest extends TestCase
{
    private $container;
    private $feature;
    private $features;
    private $resolver;
    private $resolvers;

    /**
     * @var FeatureContainer
     */
    private $feature_container;

    protected function setUp()
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

    public function testGet()
    {
        $this->container->get('features.tag.test')->willReturn($this->feature);

        self::assertSame($this->feature->reveal(), $this->feature_container->get('test'));
    }

    /**
     * @expectedException Yannickl88\FeaturesBundle\Feature\Exception\FeatureNotFoundException
     */
    public function testGetUnknownFeature()
    {
        $this->feature_container->get('phpunit');
    }

    public function testGetResolver()
    {
        self::assertEquals($this->resolver->reveal(), $this->feature_container->getResolver('test'));
    }

    public function testGetResolverChain()
    {
        self::assertInstanceOf(ChainFeatureResolver::class, $this->feature_container->getResolver('chain'));
    }

    /**
     * @expectedException Yannickl88\FeaturesBundle\Feature\Exception\ResolverNotFoundException
     */
    public function testCreateFeatureMissingResolver()
    {
        $this->feature_container->getResolver('foz');
    }
}
