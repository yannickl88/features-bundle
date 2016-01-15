<?php
namespace Yannickl88\FeaturesBundle\Feature\Exception;

/**
 * @covers Yannickl88\FeaturesBundle\Feature\Exception\FeatureNotFoundException
 */
class FeatureNotFoundExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $e = new FeatureNotFoundException('foo');

        self::assertEquals('Feature "foo" was not found.', $e->getMessage());
    }
}
