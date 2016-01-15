<?php
namespace Yannickl88\FeaturesBundle\Feature;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @covers Yannickl88\FeaturesBundle\Feature\FeatureContainer
 */
class FeatureContainerTest extends \PHPUnit_Framework_TestCase
{
    private $container;
    private $feature;
    private $features;

    /**
     * @var FeatureContainer
     */
    private $feature_container;

    protected function setUp()
    {
        $this->container = $this->prophesize(ContainerInterface::class);
        $this->feature   = $this->prophesize(Feature::class);

        $this->features = [
            'test' => 'features.tag.test'
        ];

        $this->feature_container = new FeatureContainer(
            $this->container->reveal(),
            $this->features
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
}
