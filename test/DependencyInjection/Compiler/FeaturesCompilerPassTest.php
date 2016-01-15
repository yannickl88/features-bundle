<?php
namespace Yannickl88\FeaturesBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Yannickl88\FeaturesBundle\Feature\FeatureContainer;
use Yannickl88\FeaturesBundle\Feature\FeatureFactory;
use Yannickl88\FeaturesBundle\Feature\FeatureResolverInterface;

/**
 * @covers Yannickl88\FeaturesBundle\DependencyInjection\Compiler\FeaturesCompilerPass
 */
class FeaturesCompilerPassTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FeaturesCompilerPass
     */
    private $features_compiler_pass;

    protected function setUp()
    {
        $this->features_compiler_pass = new FeaturesCompilerPass();
    }

    public function testProcess()
    {
        $container = new ContainerBuilder();

        $factory = new Definition(FeatureFactory::class);
        $factory->setArguments([[]]);

        $feature_container = new Definition(FeatureContainer::class);
        $feature_container->setArguments([[]]);

        $target = new Definition(\stdClass::class);
        $target->addArgument('foobar');
        $target->addArgument(new Reference('features.tag'));
        $target->addTag('features.tag', ['tag' => 'foo']);

        $container->setDefinition('test.service', $target);

        $resolver1 = new Definition(FeatureResolverInterface::class);
        $resolver2 = new Definition(FeatureResolverInterface::class);

        $resolver1->addTag('features.resolver', ['config-key' => 'resolver1']);
        $resolver2->addTag('features.resolver', ['config-key' => 'resolver2']);

        $container->setDefinition('test.resolver1', $resolver1);
        $container->setDefinition('test.resolver2', $resolver2);

        $container->setDefinition('features.factory', $factory);
        $container->setDefinition('features.container', $feature_container);
        $container->setParameter('features.tags', ['foo', 'bar']);
        $container->setParameter('features.tags.foo.options', ['resolver1' => []]);
        $container->setParameter('features.tags.bar.options', ['resolver2' => []]);

        $this->features_compiler_pass->process($container);

        self::assertTrue($container->hasDefinition('features.tag.foo'));
        self::assertTrue($container->hasDefinition('features.tag.bar'));

        self::assertEquals('features.tag.foo', $target->getArgument(1)->__toString());
    }

    /**
     * @expectedException Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     * @expectedExceptionMessage The value for "config-key" is missing in the tag "features.tag" for service "test.resolver".
     *
     */
    public function testProcessMissingConfigKey()
    {
        $container = new ContainerBuilder();

        $factory = new Definition(FeatureFactory::class);
        $factory->setArguments([[]]);

        $resolver = new Definition(FeatureResolverInterface::class);
        $resolver->addTag('features.resolver', []);

        $container->setDefinition('test.resolver', $resolver);

        $container->setDefinition('features.factory', $factory);
        $container->setParameter('features.tags', ['foo']);
        $container->setParameter('features.tags.foo.options', ['resolver' => []]);

        $this->features_compiler_pass->process($container);
    }

    /**
     * @expectedException Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     * @expectedExceptionMessage Unknown resolver(s) "missing" configured for feature tag "foo".
     *
     */
    public function testProcessMissingProvider()
    {
        $container = new ContainerBuilder();

        $factory = new Definition(FeatureFactory::class);
        $factory->setArguments([[]]);

        $resolver = new Definition(FeatureResolverInterface::class);
        $resolver->addTag('features.resolver', ['config-key' => 'resolver']);

        $container->setDefinition('test.resolver', $resolver);

        $container->setDefinition('features.factory', $factory);
        $container->setParameter('features.tags', ['foo']);
        $container->setParameter('features.tags.foo.options', ['missing' => []]);

        $this->features_compiler_pass->process($container);
    }

    /**
     * @expectedException Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     * @expectedExceptionMessage The value for "tag" is missing in the tag "features.tag" for service "test.service".
     *
     */
    public function testProcessMissingTag()
    {
        $container = new ContainerBuilder();

        $factory = new Definition(FeatureFactory::class);
        $factory->setArguments([[]]);

        $feature_container = new Definition(FeatureContainer::class);
        $feature_container->setArguments([[]]);

        $target = new Definition(\stdClass::class);
        $target->addArgument('foobar');
        $target->addArgument(new Reference('features.tag'));
        $target->addTag('features.tag', []);

        $container->setDefinition('test.service', $target);

        $resolver = new Definition(FeatureResolverInterface::class);
        $resolver->addTag('features.resolver', ['config-key' => 'resolver']);

        $container->setDefinition('test.resolver', $resolver);

        $container->setDefinition('features.factory', $factory);
        $container->setDefinition('features.container', $feature_container);
        $container->setParameter('features.tags', ['foo']);
        $container->setParameter('features.tags.foo.options', ['resolver' => []]);

        $this->features_compiler_pass->process($container);
    }

    /**
     * @expectedException Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     * @expectedExceptionMessage Multiple "features.tag" tags found for service "test.service", only one is allowed per service.
     *
     */
    public function testProcessMultipleTags()
    {
        $container = new ContainerBuilder();

        $factory = new Definition(FeatureFactory::class);
        $factory->setArguments([[]]);

        $feature_container = new Definition(FeatureContainer::class);
        $feature_container->setArguments([[]]);

        $target = new Definition(\stdClass::class);
        $target->addArgument('foobar');
        $target->addArgument(new Reference('features.tag'));
        $target->addTag('features.tag', ['tag' => 'foo']);
        $target->addTag('features.tag', ['tag' => 'foo']);

        $container->setDefinition('test.service', $target);

        $resolver = new Definition(FeatureResolverInterface::class);
        $resolver->addTag('features.resolver', ['config-key' => 'resolver']);

        $container->setDefinition('test.resolver', $resolver);

        $container->setDefinition('features.factory', $factory);
        $container->setDefinition('features.container', $feature_container);
        $container->setParameter('features.tags', ['foo']);
        $container->setParameter('features.tags.foo.options', ['resolver' => []]);

        $this->features_compiler_pass->process($container);
    }

    /**
     * @expectedException Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     * @expectedExceptionMessage Unknown tag "bar" used in the "feature.tag" of service "test.service".
     *
     */
    public function testProcessUnknownTag()
    {
        $container = new ContainerBuilder();

        $factory = new Definition(FeatureFactory::class);
        $factory->setArguments([[]]);

        $feature_container = new Definition(FeatureContainer::class);
        $feature_container->setArguments([[]]);

        $target = new Definition(\stdClass::class);
        $target->addArgument('foobar');
        $target->addArgument(new Reference('features.tag'));
        $target->addTag('features.tag', ['tag' => 'bar']);

        $container->setDefinition('test.service', $target);

        $resolver = new Definition(FeatureResolverInterface::class);
        $resolver->addTag('features.resolver', ['config-key' => 'resolver']);

        $container->setDefinition('test.resolver', $resolver);

        $container->setDefinition('features.factory', $factory);
        $container->setDefinition('features.container', $feature_container);
        $container->setParameter('features.tags', ['foo']);
        $container->setParameter('features.tags.foo.options', ['resolver' => []]);

        $this->features_compiler_pass->process($container);
    }
}
