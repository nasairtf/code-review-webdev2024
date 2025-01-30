<?php

declare(strict_types=1);

namespace App\exceptions;

use Exception as Base;

/**
 * Custom exception for execution errors.
 *
 * Allows passing and retrieving detailed error messages
 * when execution checks fail.
 */

class ExecutionException extends Base
{
    /**
     * Execution error messages.
     *
     * @var array
     */
    private $errors;

    /**
     * Constructor for ExecutionException.
     *
     * @param string $message Error message.
     * @param array  $errors  [optional] Detailed execution error messages. Default is an empty array.
     */
    public function __construct(string $message = "Execution failed.", array $errors = [])
    {
        parent::__construct($message);
        $this->errors = $errors;
    }

    /**
     * Retrieve the detailed execution error messages.
     *
     * @return array List of execution error messages.
     */
    public function getMessages(): array
    {
        return $this->errors;
    }
}
