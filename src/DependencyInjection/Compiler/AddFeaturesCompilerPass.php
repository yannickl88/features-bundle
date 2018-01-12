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
final class AddFeaturesCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        // configure the resolvers
        $resolvers = $this->configureResolvers($container);

        // configure the tags
        $container->setParameter('features.available_tags', $this->configureTags($container, $resolvers));
    }

    /**
     * Configure the resolvers in the service container.
     *
     * @param ContainerBuilder $container
     * @return string[]
     */
    private function configureResolvers(ContainerBuilder $container)
    {
        $services  = $container->findTaggedServiceIds('features.resolver');
        $resolvers = ['chain' => true];

        foreach ($services as $id => $options) {
            foreach ($options as $tag_options) {
                if (!isset($tag_options['config-key'])) {
                    throw new InvalidArgumentException(sprintf(
                        'The value for "config-key" is missing in the tag "features.tag" for service "%s".',
                        $id
                    ));
                }
                $config_key = $tag_options['config-key'];

                if (isset($resolvers[$config_key])) {
                    throw new InvalidArgumentException(sprintf(
                        'The config-key "%s" is already configured by resolver "%s".',
                        $config_key,
                        (string) $resolvers[$config_key]
                    ));
                }
                $resolvers[$config_key] = new Reference($id);
            }
        }
        $container
            ->getDefinition(FeatureContainer::class)
            ->replaceArgument(2, $resolvers);

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
        $all  = [];

        foreach ($tags as $tag => $param_name) {
            $options = $container->getParameter('features.tags.' . $param_name . '.options');

            // check if all the resolvers are actually configured
            if (count($missing = array_diff(array_keys($options), array_keys($configured_resolvers))) > 0) {
                throw new InvalidArgumentException(sprintf(
                    'Unknown resolver(s) %s configured for feature tag "%s".',
                    trim(json_encode(array_values($missing)), '[]'),
                    $tag
                ));
            }

            $definition = new Definition(Feature::class);
            $definition->setPublic(true);
            $definition->setFactory([new Reference('features.factory'), 'createFeature']);
            $definition->setArguments([$tag, $options]);

            $container->setDefinition('features.tag.' . $param_name, $definition);

            $all[$tag] = 'features.tag.' . $param_name;
        }

        $container
            ->getDefinition(FeatureContainer::class)
            ->replaceArgument(1, $all);

        return $tags;
    }
}
