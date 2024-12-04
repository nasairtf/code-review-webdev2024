<?php

namespace App\exceptions;

use Exception as Base;

class EmailException extends Base
{
    private $errors;

    public function __construct(string $message = "Email failed.", array $errors = [])
    {
        parent::__construct($message);
        $this->errors = $errors;
    }

    public function getMessages(): array
    {
        return $this->errors;
    }
}
