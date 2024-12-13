<?php

declare(strict_types=1);

namespace Tests\utilities;

use Mockery;

/**
 * Trait for mocking the Config class in unit tests.
 *
 * NOTE: This trait is intended exclusively for use in test classes and
 * should never be used in production code.
 */
trait ConfigMockTrait
{
    /**
     * Creates a mock of the Config class with predefined return values.
     *
     * @param array $configData Nested key-value pairs to mock.
     * @return Mockery\MockInterface The mocked Config instance.
     */
    protected function createConfigMock(array $configData = []): Mockery\MockInterface
    {
        $configMock = Mockery::mock('alias:' . \App\core\common\Config::class);

        $configMock->shouldReceive('load')
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

        $configMock->shouldReceive('get')
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

        return $configMock;
    }

    /**
     * Loads a configuration from the mock data.
     *
     * @param array  $configData Mocked configuration data.
     * @param string $name       The name of the configuration file.
     * @return array The configuration array.
     *
     * @throws \Exception If the configuration file is not found or invalid.
     */
    private function loadMockConfig(array $configData, string $name): array
    {
        if (!isset($configData[$name])) {
            throw new \Exception("Configuration file '{$name}' not found.");
        }
        $fileData = $configData[$name];
        if (!is_array($fileData)) {
            throw new \Exception("Configuration file '{$name}' must return an array.");
        }
        return $fileData;
    }
}
