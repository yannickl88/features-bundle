<?php
namespace Yannickl88\FeaturesBundle\Feature\Exception;

use PHPUnit\Framework\TestCase;

/**
 * @covers Yannickl88\FeaturesBundle\Feature\Exception\FeatureNotFoundException
 */
class FeatureNotFoundExceptionTest extends TestCase
{
    public function testConstruct(): void
    {
        $e = new FeatureNotFoundException('foo');

        self::assertEquals('Feature "foo" was not found.', $e->getMessage());
    }
}
