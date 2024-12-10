<?php

declare(strict_types=1);

namespace Tests\classes\controllers\feedback;

use PHPUnit\Framework\TestCase;
use Mockery;
use App\controllers\feedback\FeedbackController;
use App\models\feedback\FeedbackModel;
use App\views\forms\feedback\FeedbackView;
use App\validators\forms\feedback\FeedbackValidator;
use App\services\email\feedback\FeedbackService;
use App\core\common\Debug;

class FeedbackControllerTest extends TestCase
{
    private $debugMock;
    private $modelMock;
    private $viewMock;
    private $validatorMock;
    private $emailMock;
    private $controller;

    protected function setUp(): void
    {
        $this->debugMock = Mockery::mock(Debug::class);
        $this->modelMock = Mockery::mock(FeedbackModel::class);
        $this->viewMock = Mockery::mock(FeedbackView::class);
        $this->validatorMock = Mockery::mock(FeedbackValidator::class);
        $this->emailMock = Mockery::mock(FeedbackService::class);

        $this->controller = new FeedbackController(
            false,
            $this->debugMock,
            $this->modelMock,
            $this->viewMock,
            $this->validatorMock,
            $this->emailMock
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function testHandleRequestDisplaysFormPage(): void
    {
        $this->modelMock->shouldReceive('initializeDefaultFormData')
            ->once()
            ->andReturn(['program' => '2023A']);

        $this->modelMock->shouldReceive('fetchFormLists')
            ->once()
            ->with('2023A')
            ->andReturn([]);

        $this->viewMock->shouldReceive('renderFormPage')
            ->once()
            ->andReturn('Rendered Form');

        ob_start();
        $this->controller->handleRequest();
        $output = ob_get_clean();

        $this->assertStringContainsString('Rendered Form', $output);
    }

    public function testHandleFormSubmitProcessesValidForm(): void
    {
        $_POST['submit'] = true;

        $formData = ['respondent' => 'John Doe'];
        $mergedData = array_merge($formData, ['program' => '2023A']);
        $dbData = ['program' => ['a' => '2023A']];
        $validData = ['db' => [], 'email' => []];

        $this->modelMock->shouldReceive('initializeDefaultFormData')
            ->once()
            ->andReturn($mergedData);

        $this->modelMock->shouldReceive('fetchFormLists')
            ->once()
            ->with('2023A')
            ->andReturn($dbData);

        $this->validatorMock->shouldReceive('validateFormData')
            ->once()
            ->with($mergedData, $dbData)
            ->andReturn($validData);

        $this->modelMock->shouldReceive('saveFeedback')
            ->once()
            ->with($validData['db'])
            ->andReturn(true);

        $this->emailMock->shouldReceive('prepareFeedbackEmail')
            ->once()
            ->with($validData['email'])
            ->andReturnSelf();

        $this->emailMock->shouldReceive('send')
            ->once()
            ->andReturn(true);

        ob_start();
        $this->controller->handleRequest();
        $output = ob_get_clean();

        $this->assertStringContainsString('Feedback submitted successfully', $output);
    }

    public function testHandleFormSubmitRendersFormWithErrors(): void
    {
        $_POST['submit'] = true;

        $formData = ['respondent' => ''];
        $mergedData = array_merge($formData, ['program' => '2023A']);
        $dbData = ['program' => ['a' => '2023A']];

        $this->modelMock->shouldReceive('initializeDefaultFormData')
            ->once()
            ->andReturn($mergedData);

        $this->modelMock->shouldReceive('fetchFormLists')
            ->once()
            ->with('2023A')
            ->andReturn($dbData);

        $this->validatorMock->shouldReceive('validateFormData')
            ->once()
            ->with($mergedData, $dbData)
            ->andThrow(new \App\exceptions\ValidationException("Validation Error", ['respondent' => 'Required']));

        $this->viewMock->shouldReceive('renderFormWithErrors')
            ->once()
            ->andReturn('Rendered Form with Errors');

        ob_start();
        $this->controller->handleRequest();
        $output = ob_get_clean();

        $this->assertStringContainsString('Rendered Form with Errors', $output);
    }
}
