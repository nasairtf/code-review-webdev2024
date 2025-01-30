<?php

declare(strict_types=1);

namespace App\core\common;

use App\core\common\Debug              as Base;
use App\exceptions\DatabaseException   as Database;
use App\exceptions\EmailException      as Email;
use App\exceptions\ValidationException as Validation;
use App\exceptions\ExecutionException  as Execution;

/**
 * CustomDebug class.
 *
 * Extends Debug to provide domain-specific debugging features, such as
 * throwing custom exceptions (e.g., ValidationException, DatabaseException).
 * Use this class for any additional failXXX methods or project-specific logic.
 *
 * @category Core Utilities
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.3
 * @since    2024-12-10
 */
class CustomDebug extends Base
{
    /**
     * Logs a debug message and throws a validation exception.
     *
     * This method logs the provided message and throws a `ValidationException`
     * with the specified message. It uses the specified color for logging,
     * or defaults to the class-defined color.
     *
     * @param string      $message   The debug message to log.
     * @param string      $throwMsg  [optional] The exception message to throw. Defaults to $message.
     * @param string|null $color     [optional] The color for the log message. Defaults to the class default.
     *
     * @throws \App\exceptions\ValidationException Always throws a validation exception.
     */
    public function failValidation(string $message, string $throwMsg = '', ?string $color = null): void
    {
        $throw = $this->handleFail($message, $throwMsg, $color);
        throw new Validation($throw);
    }

    /**
     * Logs a debug message and throws a database exception.
     *
     * This method logs the provided message and throws a `DatabaseException`
     * with the specified message. It uses the specified color for logging,
     * or defaults to the class-defined color.
     *
     * @param string      $message   The debug message to log.
     * @param string      $throwMsg  [optional] The exception message to throw. Defaults to $message.
     * @param string|null $color     [optional] The color for the log message. Defaults to the class default.
     *
     * @throws \App\exceptions\DatabaseException Always throws a database exception.
     */
    public function failDatabase(string $message, string $throwMsg = '', ?string $color = null): void
    {
        $throw = $this->handleFail($message, $throwMsg, $color);
        throw new Database($throw);
    }

    /**
     * Logs a debug message and throws an email exception.
     *
     * This method logs the provided message and throws a `EmailException`
     * with the specified message. It uses the specified color for logging,
     * or defaults to the class-defined color.
     *
     * @param string      $message   The debug message to log.
     * @param string      $throwMsg  [optional] The exception message to throw. Defaults to $message.
     * @param string|null $color     [optional] The color for the log message. Defaults to the class default.
     *
     * @throws \App\exceptions\EmailException Always throws a email exception.
     */
    public function failEmail(string $message, string $throwMsg = '', ?string $color = null): void
    {
        $throw = $this->handleFail($message, $throwMsg, $color);
        throw new Email($throw);
    }

    /**
     * Logs a debug message and throws a execution exception.
     *
     * This method logs the provided message and throws a `ExecutionException`
     * with the specified message. It uses the specified color for logging,
     * or defaults to the class-defined color.
     *
     * @param string      $message   The debug message to log.
     * @param string      $throwMsg  [optional] The exception message to throw. Defaults to $message.
     * @param string|null $color     [optional] The color for the log message. Defaults to the class default.
     *
     * @throws \App\exceptions\ExecutionException Always throws a execution exception.
     */
    public function failExecution(string $message, string $throwMsg = '', ?string $color = null): void
    {
        $throw = $this->handleFail($message, $throwMsg, $color);
        throw new Execution($throw);
    }
}
