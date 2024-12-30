<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Tests\utilities\helpers\UnitTestTeardownTrait;

/**
 * Simple test to verify PHPUnit installation and configuration.
 */
class BaseTest extends TestCase
{
    use UnitTestTeardownTrait;

    public function testPhpUnitSetup(): void
    {
        $this->assertTrue(true, 'PHPUnit is installed and configured properly.');
    }

    /**
     * Set up the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        // Ensure parent setup runs if necessary
        parent::setUp();
    }
}
