<?php

declare(strict_types=1);

namespace Tests\classes\core\common;

use PHPUnit\Framework\TestCase;
use App\core\common\Config;

class ConfigTest extends TestCase
{
    private $tmpDir;

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

    protected function tearDown(): void
    {
        $configDir = $this->tmpDir . '/configs';

        // Cleanup the temporary configs
        $this->cleanTemporaryConfigs();

        // Assert that the directory no longer exists
        $this->assertFalse(is_dir($configDir), 'The directory should not exist.');
    }

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

    private function prepareTemporaryConfigs(): void
    {
        $configDir = $this->tmpDir . '/configs';
        if (!is_dir($configDir)) {
            mkdir($configDir, 0777, true);
        }

        file_put_contents($configDir . '/valid.php', "<?php return ['key1' => 'value1', 'key2' => 'value2'];");
        file_put_contents($configDir . '/invalid.php', "<?php return 'not an array';");
    }

    private function cleanTemporaryConfigs(): void
    {
        $configDir = $this->tmpDir . '/configs';
        if (is_dir($configDir)) {
            array_map('unlink', glob($configDir . '/*.php'));
            rmdir($configDir);
        }
    }

    public function testGetEnvironmentDefaultsToTest(): void
    {
        // TEST_APP_ENV is defined as 'test' in setUp()
        $this->assertEquals('test', Config::getEnvironment());
    }

    public function testGetBasePathDefaultsToTmp(): void
    {
        // TEST_BASE_PATH is defined as '/home/webdev2024/tmp/' in setUp()
        $this->assertEquals('/home/webdev2024/tmp/', Config::getBasePath());
    }

    public function testGetBaseUrlDefaultsToTmpUrl(): void
    {
        // TEST_BASE_URL is defined as '/~webdev2024/tmp/' in setUp()
        $this->assertEquals('/~webdev2024/tmp/', Config::getBaseUrl());
    }

    public function testLoadUsesCache(): void
    {
        $config1 = Config::load('valid');
        $config2 = Config::load('valid');

        $this->assertSame($config1, $config2, 'Configuration should be loaded from the cache.');
    }

    public function testLoadValidConfigurationFile(): void
    {
        $config = Config::load('valid');
        $this->assertIsArray($config);
        $this->assertEquals('value1', $config['key1']);
    }

    public function testLoadThrowsExceptionForMissingFile(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Configuration file 'missing' not found");

        Config::load('missing');
    }

    public function testLoadThrowsExceptionForInvalidFileFormat(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Configuration file 'invalid' must return an array.");

        Config::load('invalid');
    }

    public function testGetConfigWithNoKey(): void
    {
        $config = Config::get('valid');
        $this->assertIsArray($config);
        $this->assertEquals('value1', $config['key1']);
        $this->assertEquals('value2', $config['key2']);
    }

    public function testGetSpecificKey(): void
    {
        $value = Config::get('valid', 'key1');
        $this->assertEquals('value1', $value);
    }

    public function testGetThrowsExceptionForMissingKey(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Key 'missing_key' not found in configuration 'valid'.");

        Config::get('valid', 'missing_key');
    }

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
}
