<?php
namespace Yannickl88\FeaturesBundle\Feature;

interface FeatureResolverInterface
{
    public function isActive(array $options = []);
}