<?php

declare(strict_types=1);

namespace Tests\utilities\helpers;

use Mockery;

/**
 * Trait for standardising the temporary file/directory cleanup in unit tests.
 *
 * NOTE: This trait is intended exclusively for use in test classes and
 * should never be used in production code.
 */
trait TemporaryFileHelperTrait
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
