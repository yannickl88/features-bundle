<?php
namespace Yannickl88\FeaturesBundle\Feature;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

/**
 * @covers \Yannickl88\FeaturesBundle\Feature\ResolvableFeature
 */
class ResolvableFeatureTest extends TestCase
{
    use ProphecyTrait;

    private $name;
    private $resolver;

    /**
     * @var ResolvableFeature
     */
    private $resolvable_feature;

    protected function setUp(): void
    {
        $this->name     = 'foo';
        $this->resolver = $this->prophesize(Resolver::class);

        $this->resolvable_feature = new ResolvableFeature(
            $this->name,
            $this->resolver->reveal()
        );
    }

    public function testGetName(): void
    {
        self::assertSame($this->name, $this->resolvable_feature->getName());
    }

    /**
     * @dataProvider resolverProvider
     */
    public function testIsActive(bool $expected_active, bool $resolver_active): void
    {
        $this->resolver->resolve()->willReturn($resolver_active);

        self::assertSame($expected_active, $this->resolvable_feature->isActive());
    }

    public function resolverProvider(): iterable
    {
        return [
            [true, true],
            [false, false]
        ];
    }
}
