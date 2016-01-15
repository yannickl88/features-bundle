<?php
namespace Yannickl88\FeaturesBundle;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Yannickl88\FeaturesBundle\Feature\DeprecatedFeature;

class ConfigurationTest extends KernelTestCase
{
    protected function setUp()
    {
        static::bootKernel();
    }

    public function testFeatures()
    {
        $container = static::$kernel->getContainer();

        // check if all the feature services are correcly configured
        $feature_test  = $container->get('features.tag.test');
        $feature_test2 = $container->get('features.tag.test2');
        $feature_test3 = $container->get('features.tag.test3');
        $feature_test4 = $container->get('features.tag.test4');

        self::assertFalse($feature_test->isActive());
        self::assertEquals('test', $feature_test->getName());
        self::assertTrue($feature_test2->isActive());
        self::assertEquals('test2', $feature_test2->getName());
        self::assertFalse($feature_test3->isActive());
        self::assertEquals('test3', $feature_test3->getName());
        self::assertTrue($feature_test4->isActive());
        self::assertEquals('test4', $feature_test4->getName());

        // check if all the feature services are correcly injected
        self::assertEquals($feature_test, $container->get('app.test')->feature);
        self::assertEquals($feature_test2, $container->get('app.test2')->feature);
        self::assertEquals(new DeprecatedFeature(), $container->get('app.test_no_tag')->feature);
    }

    public function testTwigFeature()
    {
        $container = static::$kernel->getContainer();

        self::assertEquals(
            "feature is off",
            $container->get("twig")->render('/test.html.twig', ['feature_name' => 'test'])
        );
        self::assertEquals(
            "feature is on",
            $container->get("twig")->render('/test.html.twig', ['feature_name' => 'test2'])
        );
    }
}
