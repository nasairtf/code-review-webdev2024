<?php

declare(strict_types=1);

namespace Tests\classes\core\common;

use PHPUnit\Framework\TestCase;
use App\core\common\Config;

/**
 * Tests for Config getter methods that rely on constants defined in the application's bootstrap.php.
 *
 * This class validates that `getEnvironment`, `getBasePath`, and `getBaseUrl` return the expected
 * values as defined in the 'development' branch of the `bootstrap.php`.
 *
 * These tests depend on the actual runtime environment and are separated to avoid interference
 * with unit tests that override or mock constants.
 */

class ConfigGettersTest extends TestCase
{
    /**
     * The following three test methods were written to test Config methods using
     * the values set in the bootstrap.php file under the 'development' branch.
     * They are moved out of ConfigTest to ConfigGettersTest class as the constants
     * they tested were adjusted to allow unit testing overriding.
     */
    public function testGetEnvironmentDefaultsToDevelopment(): void
    {
        // APP_ENV should be defined as 'development' in the bootstrap.php for dev environment
        // from bootstrap.php: define('APP_ENV', 'development');
        $this->assertEquals('development', Config::getEnvironment());
    }

    public function testGetBasePathDefaultsToHomeWebdev2024(): void
    {
        // BASE_PATH should be '/home/webdev2024/' in the dev environment
        // from bootstrap.php: define('BASE_PATH', '/home/webdev2024/');
        $this->assertEquals('/home/webdev2024/', Config::getBasePath());
    }

    public function testGetBaseUrlDefaultsToWebdev2024Url(): void
    {
        // BASE_URL should be '/~webdev2024' in the dev environment
        // from bootstrap.php: define('BASE_URL', '/~webdev2024');
        $this->assertEquals('/~webdev2024', Config::getBaseUrl());
    }
}
