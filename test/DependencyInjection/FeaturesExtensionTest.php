<?php
namespace Yannickl88\FeaturesBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @covers Yannickl88\FeaturesBundle\DependencyInjection\FeaturesExtension
 * @covers Yannickl88\FeaturesBundle\DependencyInjection\Configuration
 */
class FeaturesExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FeaturesExtension
     */
    private $features_extension;

    protected function setUp()
    {
        $this->features_extension = new FeaturesExtension();
    }

    public function testLoadInternal()
    {
        $configs   = [
            [
                'tags' => [
                    'foo' => [
                        'resolver' => []
                    ],
                    'bar' => [
                        'resolver' => ['henk']
                    ]
                ]
            ]
        ];
        $container = new ContainerBuilder();

        $this->features_extension->load($configs, $container);

        self::assertEquals(['foo', 'bar'], $container->getParameter('features.tags'));
        self::assertEquals(['resolver' => []], $container->getParameter('features.tags.foo.options'));
        self::assertEquals(['resolver' => ['henk']], $container->getParameter('features.tags.bar.options'));
    }
}
