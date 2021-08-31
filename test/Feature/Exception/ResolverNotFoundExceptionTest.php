<?php
namespace Yannickl88\FeaturesBundle\Feature\Exception;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Yannickl88\FeaturesBundle\Feature\Exception\ResolverNotFoundException
 */
class ResolverNotFoundExceptionTest extends TestCase
{
    public function testConstruct(): void
    {
        $e = new ResolverNotFoundException('foo');

        self::assertEquals(
            'Resolver "foo" was not found, did you forget to tag it with "features.resolver"?',
            $e->getMessage()
        );
    }
}
