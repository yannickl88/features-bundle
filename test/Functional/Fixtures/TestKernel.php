<?php
namespace Yannickl88\FeaturesBundle\Functional\Fixtures;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

class TestKernel extends Kernel
{
    public function registerBundles(): array
    {
        return [
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Symfony\Bundle\TwigBundle\TwigBundle(),
            new \Yannickl88\FeaturesBundle\FeaturesBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load(__DIR__ . '/config/config.yml');
    }

    public function getLogDir(): string
    {
        return __DIR__ . '/../../../var/log';
    }

    public function getCacheDir(): string
    {
        return __DIR__ . '/../../../var/cache';
    }
}
