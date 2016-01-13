<?php
namespace Yannickl88\FeaturesBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Yannickl88\FeaturesBundle\DependencyInjection\Compiler\FeaturesCompilerPass;

/**
 * @covers Yannickl88\FeaturesBundle\FeaturesBundle
 */
class FeaturesBundleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FeaturesBundle
     */
    private $features_bundle;

    protected function setUp()
    {
        $this->features_bundle = new FeaturesBundle();
    }

    public function testBuild()
    {
        $container = new ContainerBuilder();

        $this->features_bundle->build($container);

        self::assertInstanceOf(
            FeaturesCompilerPass::class,
            current($container->getCompilerPassConfig()->getBeforeOptimizationPasses())
        );
    }
}
