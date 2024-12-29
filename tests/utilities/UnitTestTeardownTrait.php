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
     * Directory for temporary files.
     *
     * @var string|null
     */
    protected $tempDir = null;

    /**
     * File path for temporary test files.
     *
     * @var string|null
     */
    protected $testFilePath = null;

    /**
     * Directory for temporary configuration files.
     *
     * @var string|null
     */
    protected $configDir = null;

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

    /**
     * Removes temporary configuration files and directories.
     *
     * - Removes temporary configuration files and directories.
     * - Asserts that the temporary config directory is deleted.
     *
     * @return void
     */
    private function cleanTemporaryConfigs(): void
    {
        if ($this->configDir && is_dir($this->configDir)) {
            array_map('unlink', glob($this->configDir . '/*.php'));
            rmdir($this->configDir);

            // Optionally, assert that the directory no longer exists
            if (method_exists($this, 'assertFalse')) {
                $this->assertFalse(
                    is_dir($this->configDir),
                    'The configuration directory should not exist.'
                );
            }
        }
    }

    /**
     * Removes general temporary files and directories.
     *
     * - Removes temporary files and directories created during testing.
     * - Asserts that the temporary directory and file are deleted.
     *
     * @return void
     */
    private function cleanTemporaryFiles(): void
    {
        // Remove a specific test file if it exists
        if ($this->testFilePath && file_exists($this->testFilePath)) {
            unlink($this->testFilePath);

            // Optionally, assert that the file no longer exists
            if (method_exists($this, 'assertFalse')) {
                $this->assertFalse(
                    file_exists($this->testFilePath),
                    'The test file should not exist.'
                );
            }
        }

        // Remove all files in the temporary directory
        if ($this->tempDir && is_dir($this->tempDir)) {
            array_map('unlink', glob($this->tempDir . '/*'));
            rmdir($this->tempDir);

            // Optionally, assert that the directory no longer exists
            if (method_exists($this, 'assertFalse')) {
                $this->assertFalse(
                    is_dir($this->tempDir),
                    'The temporary directory should not exist.'
                );
            }
        }
    }
}
