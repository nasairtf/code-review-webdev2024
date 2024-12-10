<?php

declare(strict_types=1);

namespace Tests\classes\controllers\proposals;

use App\controllers\proposals\UpdateApplicationDateController;
use App\models\proposals\UpdateApplicationDateModel;
use App\views\forms\proposals\UpdateApplicationDateView;
use App\validators\forms\proposals\UpdateApplicationDateValidator;
use App\core\common\Debug;
use Mockery;
use PHPUnit\Framework\TestCase;

class UpdateApplicationDateControllerTest extends TestCase
{
    private $debugMock;
    private $modelMock;
    private $viewMock;
    private $validatorMock;
    private $controller;

    protected function setUp(): void
    {
        $this->debugMock = Mockery::mock(Debug::class);
        $this->modelMock = Mockery::mock(UpdateApplicationDateModel::class);
        $this->viewMock = Mockery::mock(UpdateApplicationDateView::class);
        $this->validatorMock = Mockery::mock(UpdateApplicationDateValidator::class);

        $this->controller = new UpdateApplicationDateController(false, $this->debugMock);
        $this->controller = new UpdateApplicationDateController(false, $this->debugMock);
        $this->controller->setDependencies($this->modelMock, $this->viewMock, $this->validatorMock);
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function testHandleRequestRendersForm1Page(): void
    {
        $this->viewMock->shouldReceive('renderForm1Page')
            ->once()
            ->withArgs(function ($title, $action) {
                return $title === 'IRTF Proposal Date Update Semester Chooser' && !empty($action);
            })
            ->andReturn('Form1Page');

        ob_start();
        $this->controller->handleRequest();
        $output = ob_get_clean();

        $this->assertStringContainsString('Form1Page', $output);
    }

    public function testHandleForm1SubmitProcessesValidData(): void
    {
        $_GET = ['y' => '2023', 's' => 'A'];

        $this->validatorMock->shouldReceive('validateYear')
            ->once()
            ->with('2023')
            ->andReturn(2023);

        $this->validatorMock->shouldReceive('validateSemester')
            ->once()
            ->with('A')
            ->andReturn('A');

        $this->modelMock->shouldReceive('fetchSemesterData')
            ->once()
            ->with(2023, 'A')
            ->andReturn([['proposal_id' => 1, 'title' => 'Sample Proposal']]);

        $this->viewMock->shouldReceive('renderForm2Page')
            ->once()
            ->andReturn('Form2Page');

        ob_start();
        $this->controller->handleRequest();
        $output = ob_get_clean();

        $this->assertStringContainsString('Form2Page', $output);
    }

    public function testHandleForm2SubmitRendersForm3Page(): void
    {
        $_POST = ['select' => true, 'i' => '123'];

        $this->validatorMock->shouldReceive('validateObsAppID')
            ->once()
            ->with('123')
            ->andReturn(123);

        $this->modelMock->shouldReceive('fetchProposalData')
            ->once()
            ->with(123)
            ->andReturn([['creationDate' => 1672531200]]);

        $this->viewMock->shouldReceive('renderForm3Page')
            ->once()
            ->andReturn('Form3Page');

        ob_start();
        $this->controller->handleRequest();
        $output = ob_get_clean();

        $this->assertStringContainsString('Form3Page', $output);
    }

    public function testHandleForm3SubmitProcessesUpdate(): void
    {
        $_POST = ['confirm' => true, 'i' => '123', 't' => '1672531200'];

        $this->validatorMock->shouldReceive('validateObsAppID')
            ->once()
            ->with('123')
            ->andReturn(123);

        $this->validatorMock->shouldReceive('validateTimestamp')
            ->once()
            ->with('1672531200')
            ->andReturn(1672531200);

        $this->modelMock->shouldReceive('updateProposal')
            ->once()
            ->with(123, 1672531200)
            ->andReturn('Successfully updated timestamp.');

        $this->viewMock->shouldReceive('renderResultsPage')
            ->once()
            ->andReturn('ResultsPage');

        ob_start();
        $this->controller->handleRequest();
        $output = ob_get_clean();

        $this->assertStringContainsString('ResultsPage', $output);
    }
}

/*
<?php

declare(strict_types=1);

namespace Tests\classes\controllers\proposals;

use PHPUnit\Framework\TestCase;
use Exception;
use App\controllers\proposals\UpdateApplicationDateController;
use App\core\common\Debug;
use App\models\proposals\UpdateApplicationDateModel;
use App\views\forms\proposals\UpdateApplicationDateView;
use App\validators\forms\proposals\UpdateApplicationDateValidator;

class UpdateApplicationDateControllerTest extends TestCase
{
    private $controller;
    private $mockDebug;
    private $mockModel;
    private $mockView;
    private $mockValidator;

    protected function setUp(): void
    {
        $this->mockDebug = $this->createMock(Debug::class);
        $this->mockModel = $this->createMock(UpdateApplicationDateModel::class);
        $this->mockView = $this->createMock(UpdateApplicationDateView::class);
        $this->mockValidator = $this->createMock(UpdateApplicationDateValidator::class);

        $this->controller = new UpdateApplicationDateController(false, $this->mockDebug);
        $this->controller->model = $this->mockModel;
        $this->controller->view = $this->mockView;
        $this->controller->valid = $this->mockValidator;
    }

    public function testHandleRequestRendersForm1WhenNoSubmission(): void
    {
        $this->mockDebug
            ->expects($this->once())
            ->method('debug')
            ->with('UpdateApplicationDate Controller: handleRequest()');

        $this->mockView
            ->expects($this->once())
            ->method('renderForm1Page')
            ->willReturn('<form>Form 1</form>');

        $this->expectOutputString('<form>Form 1</form>');
        $this->controller->handleRequest();
    }

    public function testHandleForm1SubmitProcessesValidData(): void
    {
        $_GET['submit'] = true;
        $_GET['y'] = '2024';
        $_GET['s'] = 'A';

        $this->mockValidator
            ->expects($this->once())
            ->method('validateYear')
            ->with('2024')
            ->willReturn(2024);

        $this->mockValidator
            ->expects($this->once())
            ->method('validateSemester')
            ->with('A')
            ->willReturn('A');

        $this->mockModel
            ->expects($this->once())
            ->method('fetchSemesterData')
            ->with(2024, 'A')
            ->willReturn([['proposal' => 'data']]);

        $this->mockView
            ->expects($this->once())
            ->method('renderForm2Page')
            ->willReturn('<form>Form 2</form>');

        $this->expectOutputString('<form>Form 2</form>');
        $this->controller->handleRequest();
    }

    public function testHandleForm1SubmitHandlesInvalidData(): void
    {
        $_GET['submit'] = true;
        $_GET['y'] = null;
        $_GET['s'] = 'Invalid';

        $this->mockValidator
            ->method('validateYear')
            ->willThrowException(new Exception('Invalid year'));

        $this->mockView
            ->expects($this->once())
            ->method('renderErrorPage')
            ->willReturn('<div>Error Page</div>');

        $this->expectOutputString('<div>Error Page</div>');
        $this->controller->handleRequest();
    }

    public function testHandleForm2SubmitProcessesValidData(): void
    {
        $_POST['select'] = true;
        $_POST['i'] = '123';

        $this->mockValidator
            ->expects($this->once())
            ->method('validateObsAppID')
            ->with('123')
            ->willReturn(123);

        $this->mockModel
            ->expects($this->once())
            ->method('fetchProposalData')
            ->with(123)
            ->willReturn([['proposal' => 'data']]);

        $this->mockView
            ->expects($this->once())
            ->method('renderForm3Page')
            ->with('IRTF Proposal Creation Date Entry', $_SERVER['PHP_SELF'], ['proposal' => 'data'])
            ->willReturn('<form>Form 3</form>');

        $this->expectOutputString('<form>Form 3</form>');
        $this->controller->handleRequest();
    }

    public function testHandleForm2SubmitHandlesInvalidData(): void
    {
        $_POST['select'] = true;
        $_POST['i'] = null;

        $this->mockValidator
            ->method('validateObsAppID')
            ->willThrowException(new Exception('Invalid proposal ID'));

        $this->mockView
            ->expects($this->once())
            ->method('renderErrorPage')
            ->with('The proposal ID is not valid', 'Invalid proposal ID')
            ->willReturn('<div>Error Page</div>');

        $this->expectOutputString('<div>Error Page</div>');
        $this->controller->handleRequest();
    }

    public function testHandleForm3SubmitProcessesValidData(): void
    {
        $_POST['confirm'] = true;
        $_POST['i'] = '123';
        $_POST['t'] = '1672444800';

        $this->mockValidator
            ->expects($this->exactly(2))
            ->methodConsecutive(
                ['validateObsAppID', ['123']],
                ['validateTimestamp', ['1672444800']]
            )
            ->willReturnOnConsecutiveCalls(123, 1672444800);

        $this->mockModel
            ->expects($this->once())
            ->method('updateProposal')
            ->with(123, 1672444800)
            ->willReturn('Successfully updated timestamp.');

        $this->mockView
            ->expects($this->once())
            ->method('renderForm4Page')
            ->with('Successfully updated timestamp.')
            ->willReturn('<div>Success Page</div>');

        $this->expectOutputString('<div>Success Page</div>');
        $this->controller->handleRequest();
    }

    public function testHandleForm3SubmitHandlesInvalidData(): void
    {
        $_POST['confirm'] = true;
        $_POST['i'] = null;
        $_POST['t'] = 'invalid';

        $this->mockValidator
            ->method('validateObsAppID')
            ->willThrowException(new Exception('Invalid proposal ID'));

        $this->mockView
            ->expects($this->once())
            ->method('renderErrorPage')
            ->with('The proposal ID and/or timestamp are not valid.', 'Invalid proposal ID')
            ->willReturn('<div>Error Page</div>');

        $this->expectOutputString('<div>Error Page</div>');
        $this->controller->handleRequest();
    }
}
*/
