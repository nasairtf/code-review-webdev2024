<?php

namespace App\core\common;

use Exception;

/**
 * /home/webdev2024/classes/core/common/Config.php
 *
 * Lightweight configuration loader and manager.
 *
 * This class provides methods to load and retrieve application configuration
 * files, with support for caching and environment awareness. Configuration
 * files are expected to return associative arrays when included.
 *
 * Environment-specific settings can be managed by referencing environment
 * constants defined in `bootstrap.php` (e.g., `APP_ENV`, `BASE_PATH`).
 *
 * @category Utilities
 * @package  IRTF
 * @version  1.0.0
 */

class Config
{
    /**
     * Cache for loaded configuration files.
     *
     * This cache stores configuration data as an associative array
     * with the configuration file name as the key and its data as the value.
     *
     * @var array<string, array>
     */
    private static $cache = [];

    /**
     * Retrieves the current application environment.
     *
     * This method returns the value of the `APP_ENV` constant, which should
     * be defined in the application's bootstrap process to indicate the
     * current environment (e.g., 'development', 'production').
     *
     * For unit testing purposes, the `TEST_APP_ENV` constant can override this
     * behavior to allow tests to isolate and validate behavior specific to
     * different environments.
     *
     * If neither constant is defined, it defaults to 'unknown'.
     *
     * **Internal Note**:
     * - During testing, the `TEST_APP_ENV` constant overrides the standard `APP_ENV`.
     * - This design ensures that the bootstrap constants don't interfere with test isolation.
     *
     * Example Usage:
     * ```php
     * // Normal runtime
     * echo Config::getEnvironment(); // 'development' or 'production'
     *
     * // During tests
     * define('TEST_APP_ENV', 'unit_test');
     * echo Config::getEnvironment(); // 'unit_test'
     * ```
     * @return string The current environment (e.g., 'development', 'production', 'test').
     */
    public static function getEnvironment(): string
    {
        // Defined for unit testing
        if (defined('TEST_APP_ENV')) {
            return TEST_APP_ENV;
        }
        // For development and production use
        return defined('APP_ENV') ? APP_ENV : 'unknown';
    }

    /**
     * Retrieves the base path of the application.
     *
     * This method returns the value of the `BASE_PATH` constant, which should
     * be defined in the application's bootstrap process to represent the
     * root directory of the application.
     *
     * For unit testing purposes, the `TEST_BASE_PATH` constant can override this
     * behavior to allow tests to isolate and validate specific paths without
     * relying on production or development constants.
     *
     * If neither constant is defined, it defaults to '/'.
     *
     * **Internal Note**:
     * - During testing, the `TEST_BASE_PATH` constant overrides the standard `BASE_PATH`.
     * - This design ensures that the bootstrap constants don't interfere with test isolation.
     *
     * Example Usage:
     * ```php
     * // Normal runtime
     * echo Config::getBasePath(); // '/home/webdev2024/' or '/aux1/irtf-web/'
     *
     * // During tests
     * define('TEST_BASE_PATH', '/home/webdev2024/tmp/');
     * echo Config::getBasePath(); // '/home/webdev2024/tmp/'
     * ```
     *
     * @return string The application's base path.
     */
    public static function getBasePath(): string
    {
        // Defined for unit testing
        if (defined('TEST_BASE_PATH')) {
            return TEST_BASE_PATH;
        }
        // For development and production use
        return defined('BASE_PATH') ? BASE_PATH : '/';
    }

    /**
     * Retrieves the base URL of the application.
     *
     * This method returns the value of the `BASE_URL` constant, which should
     * be defined in the application's bootstrap process to represent the
     * root URL of the application.
     *
     * For unit testing purposes, the `TEST_BASE_URL` constant can override this
     * behavior to allow tests to isolate and validate specific paths without
     * relying on production or development constants.
     *
     * If neither constant is defined, it defaults to '/'.
     *
     * **Internal Note**:
     * - During testing, the `TEST_BASE_URL` constant overrides the standard `BASE_URL`.
     * - This design ensures that the bootstrap constants don't interfere with test isolation.
     *
     * Example Usage:
     * ```php
     * // Normal runtime
     * echo Config::getBaseUrl(); // '/~webdev2024' or ''
     *
     * // During tests
     * define('TEST_BASE_URL', 'unit_test');
     * echo Config::getBaseUrl(); // 'unit_test'
     * ```
     *
     * @return string The application's base URL.
     */
    public static function getBaseUrl(): string
    {
        // Defined for unit testing
        if (defined('TEST_BASE_URL')) {
            return TEST_BASE_URL;
        }
        // Defined for development and production use
        return defined('BASE_URL') ? BASE_URL : '/';
    }

    /**
     * Loads a configuration file by name.
     *
     * This method reads a PHP file from the `configs` directory within the
     * application's base path. Configuration files must return an associative
     * array. The data is cached for subsequent calls to improve performance.
     *
     * @param string $name The name of the configuration file (without the `.php` extension).
     *
     * @return array<string, mixed> The configuration data as an associative array.
     *
     * @throws \Exception If the configuration file is not found or does not return an array.
     */
    public static function load(string $name): array
    {
        // Check if the configuration is already cached
        if (isset(self::$cache[$name])) {
            return self::$cache[$name];
        }

        // Construct the file path
        $filePath = self::getBasePath() . "configs/{$name}.php";

        // Ensure the configuration file exists
        if (!file_exists($filePath)) {
            throw new \Exception("Configuration file '{$name}' not found at '{$filePath}'.");
        }

        // Load the configuration file and cache it
        $config = include $filePath;

        if (!is_array($config)) {
            throw new \Exception("Configuration file '{$name}' must return an array.");
        }

        self::$cache[$name] = $config;

        return $config;
    }

    /**
     * Retrieves a specific key or the entire configuration from a file.
     *
     * This method first loads the specified configuration file and then
     * retrieves the requested key from the configuration array. If no key
     * is specified, the entire configuration array is returned.
     *
     * @param string      $name The name of the configuration file (without the `.php` extension).
     * @param string|null $key  [optional] The key to retrieve from the configuration. Defaults to null.
     *
     * @return mixed The configuration value if $key is provided, or the entire configuration array if $key is null.
     *
     * @throws \Exception If the configuration file is not found, or the key does not exist.
     */
    public static function get(string $name, ?string $key = null)
    {
        $config = self::load($name);

        // Return the entire configuration if no key is specified
        if ($key === null) {
            return $config;
        }

        // Check if the requested key exists
        if (!array_key_exists($key, $config)) {
            throw new \Exception("Key '{$key}' not found in configuration '{$name}'.");
        }

        return $config[$key];
    }
}
