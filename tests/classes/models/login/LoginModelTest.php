<?php

declare(strict_types=1);

namespace Tests\classes\models\login;

use App\models\login\LoginModel;
use App\core\common\Debug;
use App\services\database\troublelog\read\GuestAcctsService;
use PHPUnit\Framework\TestCase;

class LoginModelTest extends TestCase
{
    private $debugMock;
    private $dbReadMock;
    private $model;

    protected function setUp(): void
    {
        $this->debugMock = $this->createMock(Debug::class);
        $this->dbReadMock = $this->createMock(GuestAcctsService::class);
        $this->model = new LoginModel($this->debugMock, $this->dbReadMock);
    }

    public function testCheckCredentialsValid(): void
    {
        $this->dbReadMock->method('fetchProgramValidation')
            ->with('2023A001', '1234567890')
            ->willReturn([['count' => 1]]);

        $result = $this->model->checkCredentials('2023A001', '1234567890');
        $this->assertTrue($result);
    }

    public function testCheckCredentialsInvalid(): void
    {
        $this->dbReadMock->method('fetchProgramValidation')
            ->with('2023A001', 'wrongCode')
            ->willReturn([['count' => 0]]);

        $result = $this->model->checkCredentials('2023A001', 'wrongCode');
        $this->assertFalse($result);
    }

    public function testInitializeDefaultFormData(): void
    {
        $expected = [
            'program' => '',
            'session' => '',
            'error' => '',
        ];
        $result = $this->model->initializeDefaultFormData();
        $this->assertSame($expected, $result);
    }
}
