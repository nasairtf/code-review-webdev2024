<?php

namespace App\core\common;

use Exception;

use App\exceptions\DatabaseException as Database;
use App\exceptions\EmailException as Email;
use App\exceptions\ValidationException as Validation;

use App\core\common\Config;

/**
 * /home/webdev2024/classes/core/common/Debug.php
 *
 * Handles debug output and error logging.
 *
 * Created:
 *  2024/10/15 - Miranda Hawarden-Ogata
 *
 * Modified:
 *  2024/10/20 - Miranda Hawarden-Ogata
 *      - Refactored class from static methods/properties to instance methods/properties.
 *      - Renamed class from DebugUtility to Debug, given it is not a utility class.
 *  2024/10/21 - Miranda Hawarden-Ogata
 *      - Set up composer and autoloading.
 *  2024/12/02 - Miranda Hawarden-Ogata
 *      - Add Debug colour config.
 *
 * @category Utilities
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.3
 */

class Debug
{
    private $debugMode;
    private $debugLevel;
    private $defaultColor;

    /**
     * Constructor to set the default debug mode, level, and colour.
     *
     * @param bool   $debugMode  Enable or disable debug mode.
     * @param int    $debugLevel Set the debugging level (in future).
     * @param string $color      The default color for debug messages.
     */
    //public function __construct(
    //    bool $debugMode = false,
    //    int $debugLevel = 0,
    //    string $color = 'green'
    /**
     * Constructor to initialize debug settings, optionally using Config for colors.
     *
     * @param string $context    The context or service type for debugging (e.g., 'schedule', 'database').
     * @param bool   $debugMode  Enable or disable debug mode.
     * @param int    $debugLevel Set the debugging level (in future).
     */
    public function __construct(
        string $context = '',
        bool $debugMode = false,
        int $debugLevel = 0
    ) {
        // Fetch color from Config or use a default
        $colorConfig = Config::get('debug_config', 'colors');
        $this->defaultColor = $colorConfig[$context] ?? 'green';
        //$this->defaultColor = $color;
        $this->debugMode = $debugMode;
        $this->debugLevel = $debugLevel;
    }

    /**
     * Get the current debug mode status.
     *
     * @return bool The current debug mode status.
     */
    public function isDebugMode(): bool
    {
        return $this->debugMode;
    }

    /**
     * Set the debug level.
     *
     * @return int The current debug level.
     */
    public function getDebugLevel(): int
    {
        return $this->debugLevel;
    }

    /**
     * Set the default color for all debug messages.
     *
     * @return string The current debug message colour.
     */
    public function getDefaultColor(): string
    {
        return $this->defaultColor;
    }

    /**
     * Handles the logging and debugging output for fail methods.
     *
     * @param string      $message   The message to log.
     * @param string      $throwMsg  The exception message to throw.
     * @param string|null $color     Optional color for the message, defaults to class color.
     *
     * @return string The message to use in the exception.
     */
    private function handleFail(string $message, string $throwMsg = '', ?string $color = null): string
    {
        $throw = $throwMsg !== '' ? $throwMsg : $message;
        $color = $color ?? $this->defaultColor;
        $this->log($message, $color);
        return $throw;
    }

    /**
     * Logs the message and throws an exception.
     *
     * @param string      $message   The message to log.
     * @param string      $throwMsg  Optional exception message to throw. Defaults to $message if not provided.
     * @param string|null $color     Optional custom color for this message. Defaults to the class default.
     *
     * @throws Exception
     */
    public function fail(string $message, string $throwMsg = '', ?string $color = null): void
    {
        $throw = $this->handleFail($message, $throwMsg, $color);
        throw new \Exception($throw);
    }

    public function failValidation(string $message, string $throwMsg = '', ?string $color = null): void
    {
        $throw = $this->handleFail($message, $throwMsg, $color);
        throw new Validation($throw);
    }

    public function failDatabase(string $message, string $throwMsg = '', ?string $color = null): void
    {
        $throw = $this->handleFail($message, $throwMsg, $color);
        throw new Database($throw);
    }

    public function failEmail(string $message, string $throwMsg = '', ?string $color = null): void
    {
        $throw = $this->handleFail($message, $throwMsg, $color);
        throw new Email($throw);
    }

    /**
     * Logs errors to the PHP error log and outputs error messages for debugging if enabled.
     *
     * @param string      $message The error message to log.
     * @param string|null $color   Optional custom color for this message. Defaults to the class default.
     */
    public function log(string $message, ?string $color = null): void
    {
        if ($this->debugLevel > 0) {
            error_log($message);
        }
        $color = $color ?? $this->defaultColor;
        $this->debug($message, $color);
    }

    /**
     * Outputs debug information if debug mode is enabled.
     * Uses the default color unless a custom color is provided.
     *
     * @param string      $message The message to be output for debugging.
     * @param string|null $color   Optional custom color for this message. Defaults to the class default.
     */
    public function debug(string $message, ?string $color = null): void
    {
        if ($this->debugMode) {
            $color = $color ?? $this->defaultColor;
            echo "<p style='color: {$color};'>DEBUG: {$message}</p>\n";
        }
    }

    /**
     * Generate a debug heading in the format "ClassLabel Label: methodName()".
     *
     * @param string $classLabel The type of class (e.g., View, Controller, Validator).
     * @param string $methodName The name of the method (e.g., __construct).
     * @return string The formatted debug heading.
     */
    public function debugHeading(string $classLabel, string $methodName): string
    {
        $callingClass = '';
        if ($this->debugMode) {
            $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
            $callingClass = isset($backtrace[1]['class'])
                ? (new \ReflectionClass($backtrace[1]['class']))->getShortName()
                : 'UnknownClass';
        }
        return "$callingClass $classLabel: $methodName()";
    }

    /**
     * Outputs debug information for complex variables like arrays or objects.
     * Uses the default color unless a custom color is provided.
     *
     * @param mixed       $variable The variable to output for debugging.
     * @param string      $label    An optional label to describe the variable.
     * @param string|null $color    Optional custom color for this message. Defaults to the class default.
     */
    public function debugVariable($variable, string $label = 'Debug Variable', ?string $color = null): void
    {
        if ($this->debugMode) {
            $color = $color ?? $this->defaultColor;
            echo "<pre style='color: {$color};'>DEBUG ({$label}): " . print_r($variable, true) . "</pre>\n";
        }
    }
}
