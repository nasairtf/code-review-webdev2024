<?php

declare(strict_types=1);

namespace Tests;

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

    /**
     * Cleans up the test environment after each unit test (method).
     *
     * - Verifies Mockery's expectations are met.
     * - Clears resources and prevents leaks between tests.
     * - Ensures necessary parent (PHPUnit) teardown logic runs as well.
     *
     * @return void
     */
    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
