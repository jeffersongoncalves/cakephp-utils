<?php

use PHPUnit\Framework\TestCase;

/**
 * Every class the plugin ships autoloads (catches syntax errors, bad namespaces and missing dependencies).
 */
class SmokeTest extends TestCase
{
    /**
     * @dataProvider classes
     */
    public function testClassLoads(string $class): void
    {
        $this->assertTrue(class_exists($class) || interface_exists($class) || trait_exists($class), $class);
    }

    public function classes(): array
    {
        return [
            ['JeffersonSimaoGoncalves\\Utils\\CallbackTrait'],
            ['JeffersonSimaoGoncalves\\Utils\\Lib\\CallbackFunction'],
            ['JeffersonSimaoGoncalves\\Utils\\Lib\\HtmlTrait'],
            ['JeffersonSimaoGoncalves\\Utils\\Lib\\RenderTrait'],
            ['JeffersonSimaoGoncalves\\Utils\\Links\\RenderBase'],
            ['JeffersonSimaoGoncalves\\Utils\\Links\\RenderForm'],
            ['JeffersonSimaoGoncalves\\Utils\\Links\\RenderLink'],
            ['JeffersonSimaoGoncalves\\Utils\\Model\\Transformer\\LinkBaseTransformer'],
            ['JeffersonSimaoGoncalves\\Utils\\TableUtility'],
            ['JeffersonSimaoGoncalves\\Utils\\TypeLink'],
        ];
    }
}
