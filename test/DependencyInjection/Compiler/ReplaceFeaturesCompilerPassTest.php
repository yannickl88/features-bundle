<?php
namespace Yannickl88\FeaturesBundle\DependencyInjection\Compiler;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Reference;
use Yannickl88\FeaturesBundle\Feature\FeatureContainer;
use Yannickl88\FeaturesBundle\Feature\FeatureFactory;
use Yannickl88\FeaturesBundle\Feature\FeatureResolverInterface;

/**
 * @covers \Yannickl88\FeaturesBundle\DependencyInjection\Compiler\ReplaceFeaturesCompilerPass
 */
class ReplaceFeaturesCompilerPassTest extends TestCase
{
    /**
     * @var ReplaceFeaturesCompilerPass
     */
    private $features_compiler_pass;

    protected function setUp(): void
    {
        $this->features_compiler_pass = new ReplaceFeaturesCompilerPass();
    }

    public function testProcess(): void
    {
        $container = new ContainerBuilder();

        $factory = new Definition(FeatureFactory::class);
        $factory->setArguments(['']);

        $feature_container = new Definition(FeatureContainer::class);
        $feature_container->setArguments([new Reference('service_container'), [], []]);

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

        $container->setDefinition(FeatureFactory::class, $factory);
        $container->setDefinition(FeatureContainer::class, $feature_container);
        $container->setParameter('features.tags', ['foo' => 'foo', 'bar' => 'bar']);
        $container->setParameter('features.tags.foo.options', ['resolver1' => []]);
        $container->setParameter('features.tags.bar.options', ['resolver2' => []]);

        $this->features_compiler_pass->process($container);

        self::assertEquals('features.tag.foo', $target->getArgument(1)->__toString());
    }

    public function testProcessMissingTag(): void
    {
        $container = new ContainerBuilder();

        $factory = new Definition(FeatureFactory::class);
        $factory->setArguments(['']);

        $feature_container = new Definition(FeatureContainer::class);
        $feature_container->setArguments([new Reference('service_container'), [], []]);

        $target = new Definition(\stdClass::class);
        $target->addArgument('foobar');
        $target->addArgument(new Reference('features.tag'));
        $target->addTag('features.tag', []);

        $container->setDefinition('test.service', $target);

        $resolver = new Definition(FeatureResolverInterface::class);
        $resolver->addTag('features.resolver', ['config-key' => 'resolver']);

        $container->setDefinition('test.resolver', $resolver);

        $container->setDefinition(FeatureFactory::class, $factory);
        $container->setDefinition(FeatureContainer::class, $feature_container);
        $container->setParameter('features.tags', ['foo' => 'foo']);
        $container->setParameter('features.tags.foo.options', ['resolver' => []]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'The value for "tag" is missing in the tag "features.tag" for service "test.service".'
        );
        $this->features_compiler_pass->process($container);
    }

    public function testProcessMultipleTags(): void
    {
        $container = new ContainerBuilder();

        $factory = new Definition(FeatureFactory::class);
        $factory->setArguments(['']);

        $feature_container = new Definition(FeatureContainer::class);
        $feature_container->setArguments([new Reference('service_container'), [], []]);

        $target = new Definition(\stdClass::class);
        $target->addArgument('foobar');
        $target->addArgument(new Reference('features.tag'));
        $target->addTag('features.tag', ['tag' => 'foo']);
        $target->addTag('features.tag', ['tag' => 'foo']);

        $container->setDefinition('test.service', $target);

        $resolver = new Definition(FeatureResolverInterface::class);
        $resolver->addTag('features.resolver', ['config-key' => 'resolver']);

        $container->setDefinition('test.resolver', $resolver);

        $container->setDefinition(FeatureFactory::class, $factory);
        $container->setDefinition(FeatureContainer::class, $feature_container);
        $container->setParameter('features.tags', ['foo' => 'foo']);
        $container->setParameter('features.tags.foo.options', ['resolver' => []]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Multiple "features.tag" tags found for service "test.service", only one is allowed per service.'
        );
        $this->features_compiler_pass->process($container);
    }

    public function testProcessUnknownTag(): void
    {
        $container = new ContainerBuilder();

        $factory = new Definition(FeatureFactory::class);
        $factory->setArguments(['']);

        $feature_container = new Definition(FeatureContainer::class);
        $feature_container->setArguments([new Reference('service_container'), [], []]);

        $target = new Definition(\stdClass::class);
        $target->addArgument('foobar');
        $target->addArgument(new Reference('features.tag'));
        $target->addTag('features.tag', ['tag' => 'bar']);

        $container->setDefinition('test.service', $target);

        $resolver = new Definition(FeatureResolverInterface::class);
        $resolver->addTag('features.resolver', ['config-key' => 'resolver']);

        $container->setDefinition('test.resolver', $resolver);

        $container->setDefinition(FeatureFactory::class, $factory);
        $container->setDefinition(FeatureContainer::class, $feature_container);
        $container->setParameter('features.tags', ['foo' => 'foo']);
        $container->setParameter('features.tags.foo.options', ['resolver' => []]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Unknown tag "bar" used in the "feature.tag" of service "test.service".'
        );
        $this->features_compiler_pass->process($container);
    }
}
