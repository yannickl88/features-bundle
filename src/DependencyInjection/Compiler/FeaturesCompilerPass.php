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
        // configure the resolvers
        $resolvers = $this->configureResolvers($container);

        // configure the tags
        $tags = $this->configureTags($container, $resolvers);

        // replace all tagged features with correct feature tag
        $this->replaceTaggedFeatures($container, $tags);
    }

    /**
     * Configure the resolvers in the service container.
     *
     * @param ContainerBuilder $container
     * @return string[]
     */
    private function configureResolvers(ContainerBuilder $container)
    {
        $services  = $container->findTaggedServiceIds('feature.resolver');
        $resolvers = [];

        foreach ($services as $id => $options) {
            if (!isset($options[0]['config-key'])) {
                throw new InvalidArgumentException(sprintf(
                    'The value for "config-key" is missing in the tag "features.tag" for service "%s".',
                    $id
                ));
            }
            $config_key = $options[0]['config-key'];

            $resolvers[$config_key] = new Reference($id);
        }
        $container
            ->getDefinition('features.factory')
            ->replaceArgument(0, $resolvers);

        return $resolvers;
    }

    /**
     * Configure the tags in the service container.
     *
     * @param ContainerBuilder $container
     * @param string[]         $configured_resolvers
     * @return string[]
     */
    private function configureTags(ContainerBuilder $container, array $configured_resolvers)
    {
        $tags = $container->getParameter('features.tags');

        foreach ($tags as $tag) {
            $options = $container->getParameter('features.tags.' . $tag . '.options');

            // check if all the resolvers are actually configured
            if (count($missing = array_diff(array_keys($options), array_keys($configured_resolvers))) > 0) {
                throw new InvalidArgumentException(sprintf(
                    'Unknown resolver(s) %s configured for feature tag "%s".',
                    trim(json_encode(array_values($missing)), '[]'),
                    $tag
                ));
            }

            $definition = new Definition(Feature::class);
            $definition->setFactory([new Reference('features.factory'), 'createFeature']);
            $definition->setArguments([$tag, $options]);

            $container->setDefinition('features.tag.' . $tag, $definition);
        }

        return $tags;
    }

    /**
     * Replace default feature service arguments with the feature correct
     * feature service for all tagged services.
     *
     * @param ContainerBuilder $container
     * @param string[]         $tags
     */
    private function replaceTaggedFeatures(ContainerBuilder $container, array $tags)
    {
        $services = $container->findTaggedServiceIds('feature.tag');

        foreach ($services as $id => $options) {
            if (!isset($options[0]['tag'])) {
                throw new InvalidArgumentException(sprintf(
                    'The value for "tag" is missing in the tag "features.tag" for service "%s".',
                    $id
                ));
            }

            $tag = $options[0]['tag'];

            if (!in_array($tag, $tags)) {
                throw new InvalidArgumentException(sprintf(
                    'Unknown tag "%s" used in the "feature.tag" of service "%s".',
                    $tag,
                    $id
                ));
            }

            $definition = $container->getDefinition($id);

            foreach ($definition->getArguments() as $index => $argument) {
                if (! $argument instanceof Reference || $argument->__toString() !== 'features.tag') {
                    continue;
                }

                $definition->replaceArgument($index, new Reference('features.tag.' . $tag));
            }
        }
    }
}
