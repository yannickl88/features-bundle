<?php
namespace Yannickl88\FeaturesBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Reference;
use Yannickl88\FeaturesBundle\Feature\Feature;

/**
 * Compiler pass which create the feature tag services and replaces the tagged
 * services arguments with the correct feature.
 *
 * @author Yannick de Lange <yannick.l.88@gmail.com>
 */
class FeaturesCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        // find all resolvers
        $services  = $container->findTaggedServiceIds('feature.resolver');
        $resolvers = [];

        foreach ($services as $id => $options) {
            $config_key = $options[0]['config-key'];

            $resolvers[$config_key] = new Reference($id);
        }
        $container
            ->getDefinition('features.factory')
            ->replaceArgument(0, $resolvers);

        // configure the tags
        $tags = $container->getParameter('features.tags');

        foreach ($tags as $tag) {
            $options = $container->getParameter('features.tags.' . $tag . '.options');

            $definition = new Definition(Feature::class);
            $definition->setFactory([new Reference('features.factory'), 'createFeature']);
            $definition->setArguments([$tag, $options]);

            $container->setDefinition('features.tag.' . $tag, $definition);
        }

        // replace all tagged features with correct feature tag
        $services = $container->findTaggedServiceIds('feature.tag');

        foreach ($services as $id => $options) {
            $tag = $options[0]['tag'];

            if (!in_array($tag, $tags)) {
                throw new InvalidArgumentException(sprintf(
                    'Unknown tag "%s" used in the "feature.tag" of service "%s".',
                    $tag,
                    $id
                ));
            }

            $this->replaceFeatureTags($container->getDefinition($id), 'features.tag.' . $tag);
        }
    }

    /**
     * Replace feature tag the arguments with the correct feature tag services.
     *
     * @param Definition $definition
     * @param string     $tag
     */
    private function replaceFeatureTags(Definition $definition, $tag)
    {
        $arguments = $definition->getArguments();

        foreach ($arguments as $index => $argument) {
            if (! $argument instanceof Reference || $argument->__toString() !== 'features.tag') {
                continue;
            }

            $definition->replaceArgument($index, new Reference($tag));
        }
    }
}
