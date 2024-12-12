<?php

declare(strict_types=1);

namespace Tests\classes\exceptions;

use PHPUnit\Framework\TestCase;
use App\exceptions\HtmlBuilderException;

/**
 * Unit tests for the HtmlBuilderException class.
 *
 * @covers \App\exceptions\HtmlBuilderException
 */
class HtmlBuilderExceptionTest extends TestCase
{
    /**
     * Tests the default message and empty errors array in HtmlBuilderException.
     *
     * @covers \App\exceptions\HtmlBuilderException::__construct
     * @covers \App\exceptions\HtmlBuilderException::getMessage
     * @covers \App\exceptions\HtmlBuilderException::getMessages
     */
    public function testDefaultMessage(): void
    {
        $exception = new HtmlBuilderException();
        $this->assertEquals('HTML Builder validation failed.', $exception->getMessage());
        $this->assertEquals([], $exception->getMessages());
    }

    /**
     * Tests a custom message and error array in HtmlBuilderException.
     *
     * @covers \App\exceptions\HtmlBuilderException::__construct
     * @covers \App\exceptions\HtmlBuilderException::getMessage
     * @covers \App\exceptions\HtmlBuilderException::getMessages
     */
    public function testCustomMessageAndErrors(): void
    {
        $errors = ['field1' => 'Field 1 is required.'];
        $exception = new HtmlBuilderException('Custom validation message.', $errors);

        $this->assertEquals('Custom validation message.', $exception->getMessage());
        $this->assertEquals($errors, $exception->getMessages());
    }
}
