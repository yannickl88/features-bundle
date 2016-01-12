<?php
namespace Yannickl88\FeaturesBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

/**
 * Features Extension, loads the services and creates parameters for the
 * configured tags.
 *
 * @author Yannick de Lange <yannick.l.88@gmail.com>
 */
class FeaturesExtension extends ConfigurableExtension
{
    /**
     * {@inheritdoc}
     */
    protected function loadInternal(array $config, ContainerBuilder $container)
    {
        $container->setParameter('features.tags', array_keys($config['tags']));

        foreach ($config['tags'] as $name => $options) {
            $container->setParameter('features.tags.' . $name . '.options', $options);
        }

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
