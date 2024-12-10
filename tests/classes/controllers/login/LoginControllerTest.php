<?php

declare(strict_types=1);

namespace Tests\classes\controllers\login;

use App\controllers\login\LoginController;
use App\models\login\LoginModel;
use App\views\forms\login\LoginView;
use App\validators\forms\login\LoginValidator;
use App\core\common\Debug;
use PHPUnit\Framework\TestCase;

class LoginControllerTest extends TestCase
{
    private $debugMock;
    private $modelMock;
    private $viewMock;
    private $validatorMock;
    private $controller;

    protected function setUp(): void
    {
        $this->debugMock = $this->createMock(Debug::class);
        $this->modelMock = $this->createMock(LoginModel::class);
        $this->viewMock = $this->createMock(LoginView::class);
        $this->validatorMock = $this->createMock(LoginValidator::class);

        $this->controller = new LoginController(
            false,
            $this->debugMock
        );

        // Overriding model, view, and validator with mocks for isolated testing
        $reflection = new \ReflectionClass($this->controller);
        $this->setPrivateProperty($reflection, $this->controller, 'model', $this->modelMock);
        $this->setPrivateProperty($reflection, $this->controller, 'view', $this->viewMock);
        $this->setPrivateProperty($reflection, $this->controller, 'valid', $this->validatorMock);
    }

    private function setPrivateProperty(\ReflectionClass $reflection, $instance, string $property, $value): void
    {
        $property = $reflection->getProperty($property);
        $property->setAccessible(true);
        $property->setValue($instance, $value);
    }

    public function testHandleLoginSubmitValid(): void
    {
        $postData = ['program' => '2023A001', 'session' => '1234567890'];
        $this->validatorMock->method('validateProgram')->willReturn($postData['program']);
        $this->validatorMock->method('validateSession')->willReturn($postData['session']);
        $this->modelMock->method('checkCredentials')->willReturn(true);

        $this->expectOutputRegex('/window.open/');
        $this->controller->handleRequest();
    }

    public function testHandleLoginSubmitInvalidCredentials(): void
    {
        $postData = ['program' => '2023A001', 'session' => 'wrongSession'];
        $this->validatorMock->method('validateProgram')->willReturn($postData['program']);
        $this->validatorMock->method('validateSession')->willReturn($postData['session']);
        $this->modelMock->method('checkCredentials')->willReturn(false);

        $this->expectOutputRegex('/Error in login submission/');
        $this->controller->handleRequest();
    }

    public function testRenderLoginForm(): void
    {
        $this->viewMock->expects($this->once())
            ->method('renderLoginFormPage')
            ->with('IRTF Form Login', $this->anything(), $this->anything(), $this->anything())
            ->willReturn('<form>Login Form</form>');

        $this->expectOutputString('<form>Login Form</form>');
        $this->controller->handleRequest();
    }
}
