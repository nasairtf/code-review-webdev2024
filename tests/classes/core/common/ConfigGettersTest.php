<?php

declare(strict_types=1);

namespace Tests\classes\core\common;

use PHPUnit\Framework\TestCase;
use Tests\utilities\helpers\UnitTestTeardownTrait;
use App\core\common\Config;

/**
 * Tests for Config getter methods that rely on constants defined in the application's bootstrap.php.
 *
 * This class validates that `getEnvironment`, `getBasePath`, and `getBaseUrl` return the expected
 * values as defined in the 'development' branch of the `bootstrap.php`.
 *
 * These tests depend on the actual runtime environment and are separated to avoid interference
 * with unit tests that override or mock constants.
 *
 * @covers \App\core\common\Config
 */
class ConfigGettersTest extends TestCase
{
    use UnitTestTeardownTrait;

    /**
     * The following three test methods were written to test Config methods using
     * the values set in the bootstrap.php file under the 'development' branch.
     * They are moved out of ConfigTest to ConfigGettersTest class as the constants
     * they tested were adjusted to allow unit testing overriding.
     */

    /**
     * Validates that `Config::getEnvironment()` returns the expected environment value.
     *
     * This test checks the constant `APP_ENV`, which should be defined in `bootstrap.php`
     * for the 'development' branch as `'development'`.
     *
     * @covers \App\core\common\Config::getEnvironment
     *
     * @return void
     */
    public function testGetEnvironmentDefaultsToDevelopment(): void
    {
        // APP_ENV should be defined as 'development' in the bootstrap.php for dev environment
        // from bootstrap.php: define('APP_ENV', 'development');
        $this->assertEquals('development', Config::getEnvironment());
    }

    /**
     * Validates that `Config::getBasePath()` returns the expected base path value.
     *
     * This test checks the constant `BASE_PATH`, which should be defined in `bootstrap.php`
     * for the 'development' branch as `'/home/webdev2024/'`.
     *
     * @covers \App\core\common\Config::getBasePath
     *
     * @return void
     */
    public function testGetBasePathDefaultsToHomeWebdev2024(): void
    {
        // BASE_PATH should be '/home/webdev2024/' in the dev environment
        // from bootstrap.php: define('BASE_PATH', '/home/webdev2024/');
        $this->assertEquals('/home/webdev2024/', Config::getBasePath());
    }

    /**
     * Validates that `Config::getBaseUrl()` returns the expected base URL value.
     *
     * This test checks the constant `BASE_URL`, which should be defined in `bootstrap.php`
     * for the 'development' branch as `'/~webdev2024'`.
     *
     * @covers \App\core\common\Config::getBaseUrl
     *
     * @return void
     */
    public function testGetBaseUrlDefaultsToWebdev2024Url(): void
    {
        // BASE_URL should be '/~webdev2024' in the dev environment
        // from bootstrap.php: define('BASE_URL', '/~webdev2024');
        $this->assertEquals('/~webdev2024', Config::getBaseUrl());
    }
}
