<?php

declare(strict_types=1);

namespace App\core\common;

use Exception;
use App\core\common\Config;
use App\core\common\AbstractDebug as Base;

/**
 * DebugCLI class.
 *
 * This concrete class extends AbstractDebug to provide plain-text debug output
 * suitable for command-line (CLI) environments. Messages and variable dumps are
 * rendered as standard text.
 *
 * Example usage:
 * ```php
 * // Instantiate for a CLI context with debug enabled and verbosity level 1
 * $debug = new DebugCLI('database', true, 1);
 * $debug->log('Database connection failed.');
 * ```
 *
 * @category Core Utilities
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.4
 * @since    2024-12-10
 */
class DebugCLI extends Base
{
    /**
     * Renders a debug message as plain text if debug mode is enabled.
     *
     * @param string $message The message to be output for debugging.
     * @param string $color   Placeholder for color handling (unused in CLI).
     */
    protected function renderDebugMessage(
        string $message,
        string $color
    ): void {
        echo "DEBUG: {$message}\n";
    }

    /**
     * Renders a debug variable as plain text if debug mode is enabled.
     *
     * @param mixed  $variable The variable to output for debugging.
     * @param string $label    Label describing the variable.
     * @param string $color    Placeholder for color handling (unused in CLI).
     */
    protected function renderDebugVariable(
        $variable,
        string $label,
        string $color
    ): void {
        echo "DEBUG ({$label}): " . print_r($variable, true) . "\n";
    }
}
