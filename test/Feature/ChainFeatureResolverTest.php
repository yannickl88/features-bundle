<?php
namespace Yannickl88\FeaturesBundle\Feature;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

/**
 * @covers \Yannickl88\FeaturesBundle\Feature\ChainFeatureResolver
 */
class ChainFeatureResolverTest extends TestCase
{
    use ProphecyTrait;

    private $feature_container;

    /**
     * @var ChainFeatureResolver
     */
    private $chain_feature_resolver;

    protected function setUp(): void
    {
        $this->feature_container = $this->prophesize(FeatureContainerInterface::class);

        $this->chain_feature_resolver = new ChainFeatureResolver(
            $this->feature_container->reveal()
        );
    }

    public function testIsActive(): void
    {
        $resolver1 = $this->prophesize(FeatureResolverInterface::class);
        $resolver2 = $this->prophesize(FeatureResolverInterface::class);

        $resolver1->isActive([])->willReturn(true);
        $resolver2->isActive([42])->willReturn(false);

        $this->feature_container->getResolver('foo')->willReturn($resolver1);
        $this->feature_container->getResolver('bar')->willReturn($resolver2);

        self::assertTrue($this->chain_feature_resolver->isActive(['foo' => [], 'bar' => [42]]));
    }
}
