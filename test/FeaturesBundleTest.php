<?php
namespace Yannickl88\FeaturesBundle;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Yannickl88\FeaturesBundle\DependencyInjection\Compiler\ConfigureFeaturesCompilerPass;
use Yannickl88\FeaturesBundle\DependencyInjection\Compiler\ReplaceFeaturesCompilerPass;

/**
 * @covers Yannickl88\FeaturesBundle\FeaturesBundle
 */
class FeaturesBundleTest extends TestCase
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

        self::assertContains(
            ConfigureFeaturesCompilerPass::class,
            array_map("get_class", $container->getCompilerPassConfig()->getBeforeOptimizationPasses())
        );
        self::assertContains(
            ReplaceFeaturesCompilerPass::class,
            array_map("get_class", $container->getCompilerPassConfig()->getBeforeRemovingPasses())
        );
    }
}
