<?php

declare(strict_types=1);

namespace Tests\classes\exceptions;

use PHPUnit\Framework\TestCase;
use App\exceptions\DatabaseException;

/**
 * Unit tests for the DatabaseException class.
 *
 * @covers \App\exceptions\DatabaseException
 */
class DatabaseExceptionTest extends TestCase
{
    /**
     * Tests the default message and empty errors array in DatabaseException.
     *
     * @covers \App\exceptions\DatabaseException::__construct
     * @covers \App\exceptions\DatabaseException::getMessage
     * @covers \App\exceptions\DatabaseException::getMessages
     */
    public function testDefaultMessage(): void
    {
        $exception = new DatabaseException();
        $this->assertEquals('Database failed.', $exception->getMessage());
        $this->assertEquals([], $exception->getMessages());
    }

    /**
     * Tests a custom message and error array in DatabaseException.
     *
     * @covers \App\exceptions\DatabaseException::__construct
     * @covers \App\exceptions\DatabaseException::getMessage
     * @covers \App\exceptions\DatabaseException::getMessages
     */
    public function testCustomMessageAndErrors(): void
    {
        $errors = ['query' => 'Syntax error in SQL query.'];
        $exception = new DatabaseException('Custom database message.', $errors);

        $this->assertEquals('Custom database message.', $exception->getMessage());
        $this->assertEquals($errors, $exception->getMessages());
    }
}
