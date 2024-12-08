<?php

declare(strict_types=1);

namespace Tests\classes\exceptions;

use PHPUnit\Framework\TestCase;
use App\exceptions\EmailException;

class EmailExceptionTest extends TestCase
{
    public function testDefaultMessage(): void
    {
        $exception = new EmailException();
        $this->assertEquals('Email failed.', $exception->getMessage());
        $this->assertEquals([], $exception->getMessages());
    }

    public function testCustomMessageAndErrors(): void
    {
        $errors = ['email' => 'Invalid email address.'];
        $exception = new EmailException('Custom email message.', $errors);

        $this->assertEquals('Custom email message.', $exception->getMessage());
        $this->assertEquals($errors, $exception->getMessages());
    }
}
