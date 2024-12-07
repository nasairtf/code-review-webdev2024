<?php

namespace Tests\classes\exceptions;

use PHPUnit\Framework\TestCase;
use App\exceptions\DatabaseException;

class DatabaseExceptionTest extends TestCase
{
    public function testDefaultMessage(): void
    {
        $exception = new DatabaseException();
        $this->assertEquals('Database failed.', $exception->getMessage());
        $this->assertEquals([], $exception->getMessages());
    }

    public function testCustomMessageAndErrors(): void
    {
        $errors = ['query' => 'Syntax error in SQL query.'];
        $exception = new DatabaseException('Custom database message.', $errors);

        $this->assertEquals('Custom database message.', $exception->getMessage());
        $this->assertEquals($errors, $exception->getMessages());
    }
}
