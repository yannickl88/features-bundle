<?php
namespace Yannickl88\FeaturesBundle\Feature;

/**
 * @covers Yannickl88\FeaturesBundle\Feature\FeatureContainer
 */
class FeatureContainerTest extends \PHPUnit_Framework_TestCase
{
    private $feature;
    private $features;

    /**
     * @var FeatureContainer
     */
    private $feature_container;

    protected function setUp()
    {
        $this->feature = $this->prophesize(Feature::class);

        $this->features = [
            'test' => $this->feature->reveal()
        ];

        $this->feature_container = new FeatureContainer(
            $this->features
        );
    }

    public function testGet()
    {
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
