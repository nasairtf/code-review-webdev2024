<?php

declare(strict_types=1);

namespace Tests\utilities\mocks;

use Mockery;

/**
 * Trait for mocking the Config class in unit tests.
 *
 * This trait provides a mock implementation for the `Config` class, commonly used for
 * loading and accessing application configuration. It supports dynamic configuration
 * loading, retrieval, and validation with default error handling.
 *
 * Features:
 * - Mocks `load` and `get` methods for retrieving configuration data.
 * - Validates configuration file existence and structure.
 * - Supports dynamic mocking for flexible test scenarios.
 *
 * Example usage:
 * ```
 * $mockConfig = $this->createConfigMock([
 *     'db_config' => [
 *         'default' => ['host' => 'localhost', 'user' => 'root', 'password' => '']
 *     ]
 * ]);
 * ```
 *
 * NOTE: This trait is intended exclusively for use in test classes and
 * should never be used in production code.
 */
trait MockConfigTrait
{
    /**
     * Creates a mock of the Config class with predefined return values.
     *
     * This method supports mocking configuration loading and retrieval, including error handling.
     *
     * @param array $configData An array of nested key-value pairs representing configuration data.
     *
     * @return Mockery\MockInterface The mocked Config instance.
     */
    protected function createConfigMock(array $configData = []): Mockery\MockInterface
    {
        // Create the aliased mock
        $myMock = Mockery::mock('alias:' . \App\core\common\Config::class);

        // Mock load() to mimic real behavior
        $myMock->shouldReceive('load')
            ->andReturnUsing(function (string $name) use ($configData) {
                if (!isset($configData[$name])) {
                    throw new \Exception("Configuration file '{$name}' not found.");
                }
                $fileData = $configData[$name];
                if (!is_array($fileData)) {
                    throw new \Exception("Configuration file '{$name}' must return an array.");
                }
                return $fileData;
            });

        // Mock get() to mimic real behavior
        $myMock->shouldReceive('get')
            ->andReturnUsing(function (string $name, ?string $key = null) use ($configData) {
                $fileData = $this->loadMockConfig($configData, $name);
                if ($key === null) {
                    return $fileData;
                }
                if (!array_key_exists($key, $fileData)) {
                    throw new \Exception("Key '{$key}' not found in configuration '{$name}'.");
                }
                return $fileData[$key];
            });

        return $myMock;
    }

    /**
     * Loads a configuration from the mock data.
     *
     * @param array  $configData An array of mocked configuration data.
     * @param string $name       The name of the configuration file to load.
     *
     * @return array The loaded configuration data.
     *
     * @throws \Exception If the configuration file is missing or invalid.
     */
    private function loadMockConfig(array $configData, string $name): array
    {
        // Mock exception to mimic real behavior
        if (!isset($configData[$name])) {
            throw new \Exception("Configuration file '{$name}' not found.");
        }

        // Mock real behavior
        $fileData = $configData[$name];

        // Mock exception to mimic real behavior
        if (!is_array($fileData)) {
            throw new \Exception("Configuration file '{$name}' must return an array.");
        }

        return $fileData;
    }
}
