<?php

declare(strict_types=1);

namespace Tests\utilities;

use Mockery;

/**
 * Trait for standardising the tearDown() method in unit tests.
 *
 * Usage:
 * use Tests\utilities\UnitTestTeardownTrait;
 * use UnitTestTeardownTrait;
 *
 * NOTE: This trait is intended exclusively for use in test classes and
 * should never be used in production code.
 */
trait UnitTestTeardownTrait
{
    /**
     * Cleans up the test environment after each unit test (method).
     *
     * - Verifies Mockery's expectations are met if Mockery was used.
     * - Clears resources and prevents leaks between tests.
     * - Ensures necessary parent (PHPUnit) teardown logic runs as well.
     *
     * @return void
     */
    protected function tearDown(): void
    {
        // Check if Mockery has any expectations or mocks
        if (Mockery::getContainer() !== null) {
            Mockery::close();
        }

        // Check if the parent class has any tearDown logic to run
        if (method_exists(parent::class, 'tearDown')) {
            parent::tearDown();
        }
    }
}
