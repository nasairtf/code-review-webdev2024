<?php

declare(strict_types=1);

namespace Tests\classes\validators\forms\feedback;

use PHPUnit\Framework\TestCase;
use Mockery;
use App\validators\forms\feedback\FeedbackValidator;
use App\core\common\Debug;
use App\exceptions\ValidationException;

class FeedbackValidatorTest extends TestCase
{
    private $debugMock;
    private $validator;

    protected function setUp(): void
    {
        $this->debugMock = Mockery::mock(Debug::class);
        $this->validator = new FeedbackValidator($this->debugMock);
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function testValidateFormDataReturnsValidData(): void
    {
        $form = [
            'respondent' => 'John Doe',
            'email' => 'john.doe@example.com',
            'startyear' => 2023,
            'startmonth' => 10,
            'startday' => 1,
            'endyear' => 2023,
            'endmonth' => 10,
            'endday' => 5,
        ];

        $db = [
            'program' => ['p' => 1, 'a' => 'Program A', 's' => '2023A'],
            'support' => ['staff1', 'staff2'],
            'operator' => ['op1', 'op2'],
        ];

        $result = $this->validator->validateFormData($form, $db);
        $this->assertIsArray($result);
        $this->assertArrayHasKey('db', $result);
        $this->assertArrayHasKey('email', $result);
    }

    public function testValidateFormDataThrowsExceptionOnInvalidData(): void
    {
        $this->expectException(ValidationException::class);

        $form = [
            'respondent' => '',
            'email' => 'invalid-email',
        ];

        $db = ['program' => ['p' => 1]];

        $this->validator->validateFormData($form, $db);
    }
}
