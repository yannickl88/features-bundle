<?php
namespace Yannickl88\FeaturesBundle\Feature\Exception;

/**
 * @covers Yannickl88\FeaturesBundle\Feature\Exception\ResolverNotFoundException
 */
class ResolverNotFoundExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $e = new ResolverNotFoundException('foo');

        self::assertEquals(
            'Resolver "foo" was not found, did you forget to tag it with "features.resolver"?',
            $e->getMessage()
        );
    }
}
