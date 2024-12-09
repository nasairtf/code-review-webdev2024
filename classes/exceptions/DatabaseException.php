<?php

declare(strict_types=1);

namespace App\exceptions;

use Exception as Base;

/**
 * Custom exception for database errors.
 *
 * Allows passing and retrieving detailed error messages
 * when database operations fail.
 */

class DatabaseException extends Base
{
    /**
     * Validation error messages.
     *
     * @var array
     */
    private $errors;

    /**
     * Constructor for DatabaseException.
     *
     * @param string $message Error message.
     * @param array  $errors  [optional] Detailed error messages. Default is an empty array.
     */
    public function __construct(string $message = "Database failed.", array $errors = [])
    {
        parent::__construct($message);
        $this->errors = $errors;
    }

    /**
     * Retrieve the detailed error messages.
     *
     * @return array List of error messages.
     */
    public function getMessages(): array
    {
        return $this->errors;
    }
}
