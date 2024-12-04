<?php

namespace App\core\common;

use Exception;

/**
 * /home/webdev2024/classes/core/common/Config.php
 *
 * A lightweight configuration loader and manager class.
 * Handles reading and caching of configuration files with environment awareness.
 *
 * @category Utilities
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class Config
{
    /**
     * Cache for loaded configuration files.
     *
     * @var array
     */
    private static $cache = [];

    /**
     * Get the current application environment (e.g., development or production).
     *
     * @return string The current environment, as defined in bootstrap.php.
     */
    public static function getEnvironment(): string
    {
        return defined('APP_ENV') ? APP_ENV : 'unknown';
    }

    /**
     * Get the base path of the application.
     *
     * @return string The base path, as defined in bootstrap.php.
     */
    public static function getBasePath(): string
    {
        return defined('BASE_PATH') ? BASE_PATH : '/';
    }

    /**
     * Get the base URL of the application.
     *
     * @return string The base URL, as defined in bootstrap.php.
     */
    public static function getBaseUrl(): string
    {
        return defined('BASE_URL') ? BASE_URL : '/';
    }

    /**
     * Load a configuration file by name.
     *
     * @param string $name The name of the configuration file (without the `.php` extension).
     *
     * @return array The entire configuration data from the file.
     *
     * @throws \Exception If the configuration file is not found or invalid.
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
     * Retrieve a specific key or the entire configuration from a file.
     *
     * @param string      $name The name of the configuration file (without the `.php` extension).
     * @param string|null $key  The key to retrieve from the configuration. If null, returns the entire configuration.
     *
     * @return mixed The requested configuration value, or the entire file data if $key is null.
     *
     * @throws \Exception If the configuration file or key is not found.
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
