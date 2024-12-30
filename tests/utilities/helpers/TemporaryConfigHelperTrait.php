<?php

declare(strict_types=1);

namespace Tests\utilities\helpers;

use Mockery;

/**
 * Trait for standardising the temporary config cleanup in unit tests.
 *
 * NOTE: This trait is intended exclusively for use in test classes and
 * should never be used in production code.
 */
trait TemporaryConfigHelperTrait
{
    /**
     * Directory for temporary configuration files.
     *
     * @var string|null
     */
    protected $configDir = null;

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
}
