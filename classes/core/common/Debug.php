<?php

declare(strict_types=1);

namespace App\core\common;

use Exception;
use App\core\common\Config;

/**
 * Debug class.
 *
 * This class provides configurable debug modes, error logging, and structured debug output,
 * including message coloring and variable output for improved debugging workflows.
 *
 * The Debug class is immutable and locked to ensure system stability.
 *
 * NOTE: For additional features or domain-specific behavior, extend this class (e.g., CustomDebug).
 *
 * Example usage:
 * ```php
 * $debug = new Debug('database', true, 1);
 * $debug->log('Database connection failed.');
 * ```
 *
 * @category Core Utilities
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.3
 * @since    2024-12-10
 */
class Debug
{
    /**
     * Whether debugging is enabled.
     *
     * This flag determines if debug output is allowed. If set to `true`,
     * messages will be displayed as specified in the `debug()` method.
     *
     * @var bool
     */
    protected $debugMode;

    /**
     * The verbosity level for logging.
     *
     * Reserved for future use to control the level of detail in log output.
     * Currently operates as a toggle to turn log output on (1) or off (0).
     *
     * @var int
     */
    protected $debugLevel;

    /**
     * The default color for debug messages.
     *
     * This is determined by the context passed during initialization or defaults to "green".
     *
     * @var string
     */
    protected $defaultColor;

    /**
     * Initializes the debug settings and assigns a default message color.
     *
     * The constructor configures debugging behavior based on the provided parameters.
     * If a context is specified, it determines the default color for debug messages
     * by fetching configuration settings from `Config::get('debug_config', 'colors')`.
     * If no color is found for the context, it defaults to "green".
     *
     * @param string|null $context    [optional] The context or service type for debugging (e.g., 'schedule',
     *                                'database'). Defaults to null.
     * @param bool|null   $debugMode  [optional] Enable or disable debug mode. Defaults to false if not provided.
     * @param int|null    $debugLevel [optional] Debug verbosity level (reserved for future use).
     *                                Defaults to 0 if not provided.
     */
    public function __construct(
        ?string $context = null,
        ?bool $debugMode = null,
        ?int $debugLevel = null
    ) {
        // Fetch color from Config or use a default
        $colorConfig = Config::get('debug_config', 'colors');
        $this->defaultColor = $colorConfig[$context] ?? 'green';
        $this->debugMode = $debugMode ?? false;
        $this->debugLevel = $debugLevel ?? 0;
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
     * Retrieves the current debug level.
     *
     * The debug level determines the verbosity of debug messages. Currently, this is
     * reserved for future enhancements.
     *
     * @return int The current debug level.
     */
    public function getDebugLevel(): int
    {
        return $this->debugLevel;
    }

    /**
     * Retrieves the default color for debug messages.
     *
     * @return string The default color for debug messages.
     */
    public function getDefaultColor(): string
    {
        return $this->defaultColor;
    }

    /**
     * Logs a debug message and throws a general exception.
     *
     * This method logs the provided message and throws an `Exception` with the
     * specified message. It uses the specified color for logging, or defaults
     * to the class-defined color.
     *
     * @param string      $message   The debug message to log.
     * @param string      $throwMsg  [optional] The exception message to throw. Defaults to $message.
     * @param string|null $color     [optional] The color for the log message. Defaults to the class default.
     *
     * @throws \Exception Always throws an exception with the specified message.
     */
    public function fail(
        string $message,
        string $throwMsg = '',
        ?string $color = null
    ): void {
        $throw = $this->handleFail($message, $throwMsg, $color);
        throw new \Exception($throw);
    }

    /**
     * Logs errors to the PHP error log and outputs error messages for debugging if enabled.
     *
     * @param string      $message The error message to log.
     * @param string|null $color   Optional custom color for this message. Defaults to the class default.
     */
    public function log(
        string $message,
        ?string $color = null
    ): void {
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
    public function debug(
        string $message,
        ?string $color = null
    ): void {
        if ($this->debugMode) {
            $color = $color ?? $this->defaultColor;
            echo "<p style='color: {$color};'>DEBUG: {$message}</p>\n";
        }
    }

    /**
     * Generates a debug heading with the calling class, label, and method name.
     *
     * This method uses the debug backtrace to identify the calling class,
     * and formats the output as "ClassLabel Label: methodName()".
     *
     * @param string $classLabel The label for the class (e.g., View, Controller, Validator).
     * @param string $methodName The name of the method (e.g., __construct).
     *
     * @return string The formatted debug heading.
     */
    public function debugHeading(
        string $classLabel,
        string $methodName
    ): string {
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
    public function debugVariable(
        $variable,
        string $label = 'Debug Variable',
        ?string $color = null
    ): void {
        if ($this->debugMode) {
            $color = $color ?? $this->defaultColor;
            echo "<pre style='color: {$color};'>DEBUG ({$label}): " . print_r($variable, true) . "</pre>\n";
        }
    }

    /**
     * Logs a message and prepares the exception message for fail methods.
     *
     * This method logs the provided message and returns the exception message.
     * It uses the specified color for logging, or falls back to the default color.
     *
     * @param string      $message   The debug message to log.
     * @param string      $throwMsg  [optional] The exception message to throw. Defaults to $message.
     * @param string|null $color     [optional] The color for the log message. Defaults to the class default.
     *
     * @return string The exception message to throw.
     */
    protected function handleFail(
        string $message,
        string $throwMsg = '',
        ?string $color = null
    ): string {
        $throw = $throwMsg !== '' ? $throwMsg : $message;
        $color = $color ?? $this->defaultColor;
        $this->log($message, $color);
        return $throw;
    }
}
