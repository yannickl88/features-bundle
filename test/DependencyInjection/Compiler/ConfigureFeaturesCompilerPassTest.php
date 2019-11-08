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
 * @covers \Yannickl88\FeaturesBundle\DependencyInjection\Compiler\ConfigureFeaturesCompilerPass
 */
class ConfigureFeaturesCompilerPassTest extends TestCase
{
    /**
     * @var ConfigureFeaturesCompilerPass
     */
    private $configure_features_compiler_pass;

    protected function setUp(): void
    {
        $this->configure_features_compiler_pass = new ConfigureFeaturesCompilerPass();
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

        $this->configure_features_compiler_pass->process($container);

        self::assertTrue($container->hasDefinition('features.tag.foo'));
        self::assertTrue($container->hasDefinition('features.tag.bar'));

        self::assertEquals('features.tag', $target->getArgument(1)->__toString());
    }

    public function testProcessMissingConfigKey(): void
    {
        $container = new ContainerBuilder();

        $factory = new Definition(FeatureFactory::class);
        $factory->setArguments(['']);

        $resolver = new Definition(FeatureResolverInterface::class);
        $resolver->addTag('features.resolver', []);

        $container->setDefinition('test.resolver', $resolver);

        $container->setDefinition(FeatureFactory::class, $factory);
        $container->setParameter('features.tags', ['foo' => 'foo']);
        $container->setParameter('features.tags.foo.options', ['resolver' => []]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'The value for "config-key" is missing in the tag "features.tag" for service "test.resolver".'
        );
        $this->configure_features_compiler_pass->process($container);
    }

    public function testProcessDuplicateConfigKey(): void
    {
        $container = new ContainerBuilder();

        $factory = new Definition(FeatureFactory::class);
        $factory->setArguments(['']);

        $resolver1 = new Definition(FeatureResolverInterface::class);
        $resolver1->addTag('features.resolver', ['config-key' => 'resolver']);

        $resolver2 = new Definition(FeatureResolverInterface::class);
        $resolver2->addTag('features.resolver', ['config-key' => 'resolver']);

        $container->setDefinition('test.resolver1', $resolver1);
        $container->setDefinition('test.resolver2', $resolver1);

        $container->setDefinition(FeatureFactory::class, $factory);
        $container->setParameter('features.tags', ['foo' => 'foo']);
        $container->setParameter('features.tags.foo.options', ['resolver' => []]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'The config-key "resolver" is already configured by resolver "test.resolver1".'
        );
        $this->configure_features_compiler_pass->process($container);
    }

    public function testProcessMissingResolver(): void
    {
        $container = new ContainerBuilder();

        $factory = new Definition(FeatureFactory::class);
        $factory->setArguments(['']);

        $feature_container = new Definition(FeatureContainer::class);
        $feature_container->setArguments([new Reference('service_container'), [], []]);

        $resolver = new Definition(FeatureResolverInterface::class);
        $resolver->addTag('features.resolver', ['config-key' => 'resolver']);

        $container->setDefinition('test.resolver', $resolver);

        $container->setDefinition(FeatureFactory::class, $factory);
        $container->setDefinition(FeatureContainer::class, $feature_container);
        $container->setParameter('features.tags', ['foo' => 'foo']);
        $container->setParameter('features.tags.foo.options', ['missing' => []]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Unknown resolver(s) "missing" configured for feature tag "foo".'
        );
        $this->configure_features_compiler_pass->process($container);
    }
}
