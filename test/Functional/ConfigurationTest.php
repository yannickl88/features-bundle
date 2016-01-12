<?php
namespace Yannickl88\FeaturesBundle;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ConfigurationTest extends KernelTestCase
{
    protected function setUp()
    {
        static::bootKernel();
    }

    public function testBoot()
    {
        static::$kernel->getContainer();
    }
}
