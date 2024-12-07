<?php

namespace Tests\classes\exceptions;

use PHPUnit\Framework\TestCase;
use App\exceptions\ValidationException;

class ValidationExceptionTest extends TestCase
{
    public function testDefaultMessage(): void
    {
        $exception = new ValidationException();
        $this->assertEquals('Validation failed.', $exception->getMessage());
        $this->assertEquals([], $exception->getMessages());
    }

    public function testCustomMessageAndErrors(): void
    {
        $errors = ['field1' => 'Field 1 is required.'];
        $exception = new ValidationException('Custom validation message.', $errors);

        $this->assertEquals('Custom validation message.', $exception->getMessage());
        $this->assertEquals($errors, $exception->getMessages());
    }
}
