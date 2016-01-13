<?php
namespace Yannickl88\FeaturesBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;
use Symfony\Component\DependencyInjection\Container;

/**
 * Features Extension, loads the services and creates parameters for the
 * configured tags.
 *
 * @author Yannick de Lange <yannick.l.88@gmail.com>
 */
final class FeaturesExtension extends ConfigurableExtension
{
    /**
     * {@inheritdoc}
     */
    protected function loadInternal(array $config, ContainerBuilder $container)
    {
        $tags = [];
        foreach ($config['tags'] as $name => $options) {
            $escaped_name = Container::underscore($name);
            $container->setParameter('features.tags.' . $escaped_name . '.options', $options);

            $tags[] = $escaped_name;
        }

        $container->setParameter('features.tags', $tags);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
