<?php

declare(strict_types=1);

namespace App\exceptions;

use InvalidArgumentException as Base;

/**
 * Custom exception for HTML Builder validation errors.
 *
 * Allows passing and retrieving detailed error messages
 * when validation fails in HTML Builder classes.
 */

class HtmlBuilderException extends Base
{
    /**
     * Validation error messages.
     *
     * @var array
     */
    private $errors;

    /**
     * Constructor for HtmlBuilderException.
     *
     * @param string $message Error message.
     * @param array  $errors  [optional] Detailed validation error messages. Default is an empty array.
     */
    public function __construct(string $message = 'HTML Builder validation failed.', array $errors = [])
    {
        parent::__construct($message);
        $this->errors = $errors;
    }

    /**
     * Retrieve the detailed validation error messages.
     *
     * @return array List of validation error messages.
     */
    public function getMessages(): array
    {
        return $this->errors;
    }
}
