<?php
namespace Yannickl88\FeaturesBundle\Feature;

/**
 * Container for multiple FeatureResolverInterface instances, when resolve is
 * called the final value is calculated.
 *
 * @author Yannick de Lange <yannick.l.88@gmail.com>
 */
class Resolver
{
    /**
     * @var FeatureResolverInterface[]
     */
    private $resolvers = [];

    /**
     * @var array
     */
    private $resolver_options = [];

    /**
     * @param FeatureResolverInterface $resolver
     * @param array                    $options
     */
    public function addResolver(FeatureResolverInterface $resolver, array $options = [])
    {
        $key = spl_object_hash($resolver);

        $this->resolvers[$key]        = $resolver;
        $this->resolver_options[$key] = $options;
    }

    /**
     * Resolve the final value.
     *
     * @return bool
     */
    public function resolve()
    {
        foreach ($this->resolvers as $key => $resolver) {
            if (!$resolver->isActive($this->resolver_options[$key])) {
                return false;
            }
        }

        return true;
    }
}