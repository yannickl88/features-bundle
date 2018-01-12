<?php
namespace Yannickl88\FeaturesBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Reference;
use Yannickl88\FeaturesBundle\Feature\DeprecatedFeature;
use Yannickl88\FeaturesBundle\Feature\Feature;
use Yannickl88\FeaturesBundle\Feature\FeatureContainer;

/**
 * Compiler pass which create the feature tag services and replaces the tagged
 * services arguments with the correct feature.
 *
 * @author Yannick de Lange <yannick.l.88@gmail.com>
 */
final class FeaturesCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $tags = $container->getParameter('features.available_tags');

        // replace all tagged features with correct feature tag
        $this->replaceTaggedFeatures($container, $tags);

        $container->getParameterBag()->remove('features.available_tags');
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
        $services = $container->findTaggedServiceIds('features.tag');

        foreach ($services as $id => $options) {
            if (!isset($options[0]['tag'])) {
                throw new InvalidArgumentException(sprintf(
                    'The value for "tag" is missing in the tag "features.tag" for service "%s".',
                    $id
                ));
            }
            if (count($options) != 1) {
                throw new InvalidArgumentException(sprintf(
                    'Multiple "features.tag" tags found for service "%s", only one is allowed per service.',
                    $id
                ));
            }

            $tag = $options[0]['tag'];

            if (!array_key_exists($tag, $tags)) {
                throw new InvalidArgumentException(sprintf(
                    'Unknown tag "%s" used in the "feature.tag" of service "%s".',
                    $tag,
                    $id
                ));
            }

            $definition = $container->getDefinition($id);

            foreach ($definition->getArguments() as $index => $argument) {
                if (! $argument instanceof Reference
                    || !in_array($argument->__toString(), ['features.tag', DeprecatedFeature::class])
                ) {
                    continue;
                }

                $definition->replaceArgument($index, new Reference('features.tag.' . $tags[$tag]));
            }
        }
    }
}
