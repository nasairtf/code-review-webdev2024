<?php

declare(strict_types=1);

namespace Tests\classes\exceptions;

use PHPUnit\Framework\TestCase;
use App\exceptions\ValidationException;

/**
 * Unit tests for the ValidationException class.
 *
 * @covers \App\exceptions\ValidationException
 */
class ValidationExceptionTest extends TestCase
{
    /**
     * Tests the default message and empty errors array in ValidationException.
     *
     * @covers \App\exceptions\ValidationException::__construct
     * @covers \App\exceptions\ValidationException::getMessage
     * @covers \App\exceptions\ValidationException::getMessages
     */
    public function testDefaultMessage(): void
    {
        $exception = new ValidationException();
        $this->assertEquals('Validation failed.', $exception->getMessage());
        $this->assertEquals([], $exception->getMessages());
    }

    /**
     * Tests a custom message and error array in ValidationException.
     *
     * @covers \App\exceptions\ValidationException::__construct
     * @covers \App\exceptions\ValidationException::getMessage
     * @covers \App\exceptions\ValidationException::getMessages
     */
    public function testCustomMessageAndErrors(): void
    {
        $errors = ['field1' => 'Field 1 is required.'];
        $exception = new ValidationException('Custom validation message.', $errors);

        $this->assertEquals('Custom validation message.', $exception->getMessage());
        $this->assertEquals($errors, $exception->getMessages());
    }
}
