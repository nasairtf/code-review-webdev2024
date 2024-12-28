<?php

declare(strict_types=1);

namespace Tests\classes\core\common;

use PHPUnit\Framework\TestCase;
use App\core\common\Config;

/**
 * Unit tests for the Config class.
 *
 * This test suite validates the functionality of the Config class, including
 * environment and path handling, configuration loading, and exception handling.
 *
 * @covers \App\core\common\Config
 */
class ConfigTest extends TestCase
{
    /**
     * @var string Path to the temporary directory for test configurations.
     */
    private $tmpDir;

    /**
     * Tests that the default environment is 'test'.
     *
     * @covers \App\core\common\Config::getEnvironment
     *
     * @return void
     */
    public function testGetEnvironmentDefaultsToTest(): void
    {
        // TEST_APP_ENV is defined as 'test' in setUp()
        $this->assertEquals('test', Config::getEnvironment());
    }

    /**
     * Tests that the default base path is '/home/webdev2024/tmp/'.
     *
     * @covers \App\core\common\Config::getBasePath
     *
     * @return void
     */
    public function testGetBasePathDefaultsToTmp(): void
    {
        // TEST_BASE_PATH is defined as '/home/webdev2024/tmp/' in setUp()
        $this->assertEquals('/home/webdev2024/tmp/', Config::getBasePath());
    }

    /**
     * Tests that the default base URL is '/~webdev2024/tmp/'.
     *
     * @covers \App\core\common\Config::getBaseUrl
     *
     * @return void
     */
    public function testGetBaseUrlDefaultsToTmpUrl(): void
    {
        // TEST_BASE_URL is defined as '/~webdev2024/tmp/' in setUp()
        $this->assertEquals('/~webdev2024/tmp/', Config::getBaseUrl());
    }

    /**
     * Tests that configuration files are cached after the first load.
     *
     * @covers \App\core\common\Config::load
     *
     * @return void
     */
    public function testLoadUsesCache(): void
    {
        $config1 = Config::load('valid');
        $config2 = Config::load('valid');

        $this->assertSame($config1, $config2, 'Configuration should be loaded from the cache.');
    }

    /**
     * Tests that a valid configuration file is loaded correctly.
     *
     * @covers \App\core\common\Config::load
     *
     * @return void
     */
    public function testLoadValidConfigurationFile(): void
    {
        $config = Config::load('valid');
        $this->assertIsArray($config);
        $this->assertEquals('value1', $config['key1']);
    }

    /**
     * Tests that loading a missing configuration file throws an exception.
     *
     * @covers \App\core\common\Config::load
     *
     * @return void
     */
    public function testLoadThrowsExceptionForMissingFile(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Configuration file 'missing' not found");

        Config::load('missing');
    }

    /**
     * Tests that loading an invalid configuration file format throws an exception.
     *
     * @covers \App\core\common\Config::load
     *
     * @return void
     */
    public function testLoadThrowsExceptionForInvalidFileFormat(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Configuration file 'invalid' must return an array.");

        Config::load('invalid');
    }

    /**
     * Tests retrieving a configuration file without specifying a key.
     *
     * @covers \App\core\common\Config::get
     *
     * @return void
     */
    public function testGetConfigWithNoKey(): void
    {
        $config = Config::get('valid');
        $this->assertIsArray($config);
        $this->assertEquals('value1', $config['key1']);
        $this->assertEquals('value2', $config['key2']);
    }

    /**
     * Tests retrieving a specific key from a configuration file.
     *
     * @covers \App\core\common\Config::get
     *
     * @return void
     */
    public function testGetSpecificKey(): void
    {
        $value = Config::get('valid', 'key1');
        $this->assertEquals('value1', $value);
    }

    /**
     * Tests that retrieving a missing key throws an exception.
     *
     * @covers \App\core\common\Config::get
     *
     * @return void
     */
    public function testGetThrowsExceptionForMissingKey(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Key 'missing_key' not found in configuration 'valid'.");

        Config::get('valid', 'missing_key');
    }

    /**
     * Tests that temporary configuration files are cleaned up correctly.
     *
     * @covers \App\core\common\Config
     *
     * @return void
     */
    public function testCleanupRemovesTemporaryConfigs(): void
    {
        $configDir = $this->tmpDir . '/configs';

        // Cleanup first to ensure the directory is not present
        if (is_dir($configDir)) {
            $this->cleanTemporaryConfigs();
        }

        // Create temporary files to mimic test setup
        mkdir($configDir, 0777, true);
        file_put_contents($configDir . '/dummy.php', "<?php return [];");

        // Ensure the file and directory exist before cleanup
        $this->assertFileExists($configDir . '/dummy.php');
        $this->assertDirectoryExists($configDir);

        // Perform cleanup
        $this->cleanTemporaryConfigs();

        // Assert that the directory and files are removed
        $this->assertFalse(file_exists($configDir . '/dummy.php'), 'The file should not exist.');
        $this->assertFalse(is_dir($configDir), 'The directory should not exist.');
    }

    /**
     * Sets up the test environment before each test.
     *
     * - Initializes a temporary directory for test configuration files.
     * - Defines necessary constants for configuration handling.
     * - Prepares temporary configuration files for testing.
     *
     * @return void
     */
    protected function setUp(): void
    {
        // Fetch the temporary directory from environment variables
        $this->tmpDir = getenv('TEST_TMP_DIR') ?: '/tmp';

        // Ensure the directory is clean before each test
        $this->cleanTemporaryConfigs();

        // Ensure the constants are set correctly before each test
        $this->defineTestConstants();

        // Set the rest of the test configuration
        $this->prepareTemporaryConfigs();
    }

    /**
     * Cleans up the test environment after each unit test (method).
     *
     * - Removes temporary configuration files and directories.
     * - Asserts that the temporary directory is deleted.
     * - Verifies Mockery's expectations are met.
     * - Clears resources and prevents leaks between tests.
     * - Ensures necessary parent (PHPUnit) teardown logic runs as well.
     *
     * @return void
     */
    protected function tearDown(): void
    {
        $configDir = $this->tmpDir . '/configs';

        // Cleanup the temporary configs
        $this->cleanTemporaryConfigs();

        // Assert that the directory no longer exists
        $this->assertFalse(is_dir($configDir), 'The directory should not exist.');

        parent::tearDown();
    }

    /**
     * Defines constants required for testing.
     *
     * Ensures all necessary constants (e.g., BASE_PATH, TEST_APP_ENV) are defined
     * for the duration of the test suite.
     *
     * @return void
     */
    private function defineTestConstants(): void
    {
        // Verify the BASE_PATH and set if necessary
        if (!defined('BASE_PATH')) {
            define('BASE_PATH', $this->tmpDir . '/');
        }

        // Verify the TEST_APP_ENV and set if necessary
        if (!defined('TEST_APP_ENV')) {
            define('TEST_APP_ENV', 'test');
        }

        // Verify the TEST_BASE_PATH and set if necessary
        if (!defined('TEST_BASE_PATH')) {
            define('TEST_BASE_PATH', $this->tmpDir . '/');
        }

        // Verify the TEST_BASE_URL and set if necessary
        if (!defined('TEST_BASE_URL')) {
            define('TEST_BASE_URL', '/~webdev2024/tmp/');
        }
    }

    /**
     * Prepares temporary configuration files for testing.
     *
     * - Creates a temporary directory for configuration files.
     * - Writes valid and invalid configuration files for test cases.
     *
     * @return void
     */
    private function prepareTemporaryConfigs(): void
    {
        $configDir = $this->tmpDir . '/configs';
        if (!is_dir($configDir)) {
            mkdir($configDir, 0777, true);
        }

        file_put_contents($configDir . '/valid.php', "<?php return ['key1' => 'value1', 'key2' => 'value2'];");
        file_put_contents($configDir . '/invalid.php', "<?php return 'not an array';");
    }

    /**
     * Removes temporary configuration files and directories.
     *
     * @return void
     */
    private function cleanTemporaryConfigs(): void
    {
        $configDir = $this->tmpDir . '/configs';
        if (is_dir($configDir)) {
            array_map('unlink', glob($configDir . '/*.php'));
            rmdir($configDir);
        }
    }
}
