<?php
namespace Yannickl88\FeaturesBundle\Feature;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Yannickl88\FeaturesBundle\Feature\DeprecatedFeature
 */
class DeprecatedFeatureTest extends TestCase
{
    /**
     * @var DeprecatedFeature
     */
    private $deprecated_feature;

    protected function setUp(): void
    {
        $this->deprecated_feature = new DeprecatedFeature();
    }

    public function testIsActive(): void
    {
        self::assertTrue($this->deprecated_feature->isActive());
    }

    public function testIsActiveNotice(): void
    {
        set_error_handler(function ($errno, $errstr) {
            self::assertEquals('Feature with no tag was used, please remove or configure the tag.', $errstr);
        });

        self::assertTrue($this->deprecated_feature->isActive());

        restore_error_handler();
    }
}
