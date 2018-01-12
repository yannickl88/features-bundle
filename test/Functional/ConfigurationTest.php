<?php
namespace Yannickl88\FeaturesBundle;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Yannickl88\FeaturesBundle\Feature\DeprecatedFeature;
use Yannickl88\FeaturesBundle\Functional\Fixtures\TestClass;
use Yannickl88\FeaturesBundle\Functional\Fixtures\TestKernel;

class ConfigurationTest extends KernelTestCase
{
    protected function setUp()
    {
        static::bootKernel();
    }

    public function testFeatures()
    {
        $container = static::$kernel->getContainer();
        $features  = $container->get('features.container');

        // check if all the feature services are correcly configured
        $feature_test  = $features->get('test');
        $feature_test2 = $features->get('test2');
        $feature_test3 = $features->get('test3');
        $feature_test4 = $features->get('test4');
        $feature_test5 = $features->get('test5');
        $feature_test6 = $features->get('test6');
        $feature_test7 = $features->get('test7');
        $feature_test8 = $features->get('test-with-strange_name');

        self::assertFalse($feature_test->isActive());
        self::assertEquals('test', $feature_test->getName());
        self::assertTrue($feature_test2->isActive());
        self::assertEquals('test2', $feature_test2->getName());
        self::assertFalse($feature_test3->isActive());
        self::assertEquals('test3', $feature_test3->getName());
        self::assertTrue($feature_test4->isActive());
        self::assertEquals('test4', $feature_test4->getName());
        self::assertTrue($feature_test5->isActive());
        self::assertEquals('test5', $feature_test5->getName());
        self::assertTrue($feature_test6->isActive());
        self::assertEquals('test6', $feature_test6->getName());
        self::assertFalse($feature_test7->isActive());
        self::assertEquals('test7', $feature_test7->getName());
        self::assertTrue($feature_test8->isActive());
        self::assertEquals('test-with-strange_name', $feature_test8->getName());

        // check if all the feature services are correcly injected
        self::assertEquals($feature_test, $container->get(TestClass::class)->feature);
        self::assertEquals($feature_test2, $container->get('app.test2')->feature);
        self::assertEquals($feature_test8, $container->get('app.test3')->feature);
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
        self::assertEquals(
            "feature is on",
            $container->get("twig")->render('/test.html.twig', ['feature_name' => 'test-with-strange_name'])
        );
    }
}
