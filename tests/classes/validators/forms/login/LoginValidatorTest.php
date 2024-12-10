<?php

declare(strict_types=1);

namespace Tests\classes\validators\forms\login;

use App\validators\forms\login\LoginValidator;
use App\core\common\Debug;
use PHPUnit\Framework\TestCase;

class LoginValidatorTest extends TestCase
{
    private $debugMock;
    private $validator;

    protected function setUp(): void
    {
        $this->debugMock = $this->createMock(Debug::class);
        $this->validator = new LoginValidator($this->debugMock);
    }

    public function testValidateProgramValidInput(): void
    {
        $validProgram = '2023A001';
        $result = $this->validator->validateProgram($validProgram);
        $this->assertSame($validProgram, $result);
    }

    public function testValidateProgramInvalidFormatThrowsException(): void
    {
        $this->expectException(\Exception::class);
        $this->validator->validateProgram('InvalidProgram');
    }

    public function testValidateSessionValidInput(): void
    {
        $validSession = '1234567890';
        $result = $this->validator->validateSession($validSession);
        $this->assertSame($validSession, $result);
    }

    public function testValidateSessionInvalidLengthThrowsException(): void
    {
        $this->expectException(\Exception::class);
        $this->validator->validateSession('short');
    }

    public function testValidateSessionEngineeringCode(): void
    {
        $engineeringCode = 'tisanpwd';
        $result = $this->validator->validateSession($engineeringCode);
        $this->assertSame($engineeringCode, $result);
    }
}
