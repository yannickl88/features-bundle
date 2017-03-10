<?php
namespace Yannickl88\FeaturesBundle\Feature;

use PHPUnit\Framework\TestCase;
use Yannickl88\FeaturesBundle\Feature\Resolver;

/**
 * @covers Yannickl88\FeaturesBundle\Feature\ResolvableFeature
 */
class ResolvableFeatureTest extends TestCase
{
    private $name;
    private $resolver;

    /**
     * @var ResolvableFeature
     */
    private $resolvable_feature;

    protected function setUp()
    {
        $this->name     = 'foo';
        $this->resolver = $this->prophesize(Resolver::class);

        $this->resolvable_feature = new ResolvableFeature(
            $this->name,
            $this->resolver->reveal()
        );
    }

    public function testGetName()
    {
        self::assertEquals($this->name, $this->resolvable_feature->getName());
    }

    /**
     * @dataProvider resolverProvider
     */
    public function testisActive($expected_active, $resolver_active)
    {
        $this->resolver->resolve()->willReturn($resolver_active);

        self::assertEquals($expected_active, $this->resolvable_feature->isActive());
    }

    public function resolverProvider()
    {
        return [
            [true, true],
            [false, false]
        ];
    }
}
