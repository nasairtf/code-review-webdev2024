<?php

declare(strict_types=1);

namespace Tests\utilities\helpers;

use Mockery;
use Tests\utilities\helpers\TemporaryConfigHelperTrait;
use Tests\utilities\helpers\TemporaryFileHelperTrait;

/**
 * Trait for standardising the tearDown() method in unit tests.
 *
 * Usage:
 * use Tests\utilities\helpers\UnitTestTeardownTrait;
 * use UnitTestTeardownTrait;
 *
 * NOTE: This trait is intended exclusively for use in test classes and
 * should never be used in production code.
 */
trait UnitTestTeardownTrait
{
    use TemporaryConfigHelperTrait;
    use TemporaryFileHelperTrait;

    /**
     * Cleans up the test environment after each unit test (method).
     *
     * - Verifies Mockery's expectations are met if Mockery was used.
     * - Removes temporary files and directories.
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

        // Remove temporary configuration files and directories
        $this->cleanTemporaryConfigs();

        // Remove general temporary files and directories
        $this->cleanTemporaryFiles();

        // Check if the parent class has any tearDown logic to run
        if (method_exists(parent::class, 'tearDown')) {
            parent::tearDown();
        }
    }
}
