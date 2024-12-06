<?php

use PHPUnit\Framework\TestCase;

/**
 * Simple test to verify PHPUnit installation and configuration.
 */
class BaseTest extends TestCase
{
    public function testPhpUnitSetup(): void
    {
        $this->assertTrue(true, 'PHPUnit is installed and configured properly.');
    }
}
