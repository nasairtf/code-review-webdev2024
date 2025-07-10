<?php

declare(strict_types=1);

namespace App\core\common;

/**
 * DebugFactory
 *
 * Centralised *factory* responsible for handing out the correct **debugger**
 * implementation (`CustomDebug` for web, `CustomDebugCLI` for CLI) at runtime.
 * Call-sites only depend on the shared contract (`AbstractDebug`) and never
 * need to branch on “web vs CLI”.
 *
 * Typical usage
 * ```php
 * // Generic (context-aware) debugger
 * $debug = DebugFactory::create('database', true);
 *
 * // High-traffic helper for the schedule/TAC domain
 * $debug = DebugFactory::schedule();
 * ```
 *
 * Detection rules
 * * `APP_MODE` constant (set in bootstrap) is preferred.
 * * Falls back to `PHP_SAPI === 'cli'` if the constant is missing.
 *
 * @category Core Utilities
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 * @since    2025-04-24
 */
final class DebugFactory
{
    /**
     * Build a debugger appropriate for the current runtime (CLI or web).
     *
     * @param string|null $context   Logical channel that selects a colour scheme
     *                               (e.g. 'database', 'schedule'). `null`
     *                               defaults to `'default'`.
     * @param bool|null   $debugMode Enable (`true`) or suppress (`false`)
     *                               debug output. `null` lets the concrete
     *                               class pick its own default (usually false).
     * @param int|null    $debugLevel Reserved verbosity dial; `null` maps to 0.
     *
     * @return AbstractDebug Concrete debugger (`CustomDebug` or
     *                       `CustomDebugCLI`) chosen according to the runtime.
     */
    public static function create(
        ?string $context = null,
        ?bool $debugMode = null,
        ?int $debugLevel = null
    ): AbstractDebug {
        // Use bootstrap information ­– fallback to PHP_SAPI for safety.
        $isCli = defined('APP_MODE')
            ? (APP_MODE === 'cli')
            : (PHP_SAPI === 'cli');

        // A single `new` based on that decision
        return $isCli
            ? new CustomDebugCLI($context, $debugMode, $debugLevel)
            : new CustomDebug   ($context, $debugMode, $debugLevel);
    }

    /**
     * Convenience helper for the high-traffic **schedule/TAC** domain.
     *
     * Same as calling `create('schedule', $debugMode, $debugLevel)`.
     *
     * @param bool $debugMode Optional override for debug flag (default false).
     * @param int  $debugLevel Optional verbosity level (default 0).
     *
     * @return AbstractDebug CLI or web debugger pre-configured for the
     *                       'schedule' context.
     */
    public static function schedule(
        bool $debugMode = false,
        int $debugLevel = 0
    ): AbstractDebug {
        return self::create('schedule', $debugMode, $debugLevel);
    }
}
