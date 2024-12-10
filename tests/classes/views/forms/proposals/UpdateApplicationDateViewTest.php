<?php

declare(strict_types=1);

namespace Tests\classes\views\forms\proposals;

use App\views\forms\proposals\UpdateApplicationDateView;
use App\core\common\Debug;
use Mockery;
use PHPUnit\Framework\TestCase;

class UpdateApplicationDateViewTest extends TestCase
{
    private $debugMock;
    private $view;

    protected function setUp(): void
    {
        $this->debugMock = Mockery::mock(Debug::class);
        $this->view = new UpdateApplicationDateView(false, $this->debugMock);
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function testRenderForm1PageReturnsValidHtml(): void
    {
        $title = 'IRTF Proposal Date Update Semester Chooser';
        $action = '/form-action';

        $this->debugMock->shouldReceive('debugHeading')
            ->once()
            ->with('View', 'renderForm1Page');

        $this->debugMock->shouldReceive('debugVariable')
            ->with($title, Mockery::type('string'));

        $this->debugMock->shouldReceive('debugVariable')
            ->with($action, Mockery::type('string'));

        $result = $this->view->renderForm1Page($title, $action);
        $this->assertStringContainsString('<form', $result);
        $this->assertStringContainsString($title, $result);
    }

    public function testRenderForm2PageReturnsValidHtml(): void
    {
        $title = 'IRTF Proposal Date Update Semester Listing';
        $action = '/form-action';
        $proposals = [
            ['proposal_id' => 1, 'title' => 'Proposal A'],
            ['proposal_id' => 2, 'title' => 'Proposal B'],
        ];

        $this->debugMock->shouldReceive('debugHeading')
            ->once()
            ->with('View', 'renderForm2Page');

        $this->debugMock->shouldReceive('debugVariable')
            ->with($title, Mockery::type('string'));

        $this->debugMock->shouldReceive('debugVariable')
            ->with($action, Mockery::type('string'));

        $this->debugMock->shouldReceive('debugVariable')
            ->with($proposals, Mockery::type('array'));

        $result = $this->view->renderForm2Page($title, $action, $proposals);
        $this->assertStringContainsString('<form', $result);
        $this->assertStringContainsString('Proposal A', $result);
        $this->assertStringContainsString('Proposal B', $result);
    }

    public function testRenderForm3PageReturnsValidHtml(): void
    {
        $title = 'IRTF Proposal Creation Date Entry';
        $action = '/form-action';
        $proposal = ['creationDate' => 1672531200];

        $this->debugMock->shouldReceive('debugHeading')
            ->once()
            ->with('View', 'renderForm3Page');

        $this->debugMock->shouldReceive('debugVariable')
            ->with($title, Mockery::type('string'));

        $this->debugMock->shouldReceive('debugVariable')
            ->with($action, Mockery::type('string'));

        $this->debugMock->shouldReceive('debugVariable')
            ->with($proposal, Mockery::type('array'));

        $result = $this->view->renderForm3Page($title, $action, $proposal);
        $this->assertStringContainsString('<form', $result);
        $this->assertStringContainsString('1672531200', $result);
    }

    public function testGetFieldLabelsReturnsEmptyArray(): void
    {
        $this->debugMock->shouldReceive('debugHeading')
            ->once()
            ->with('View', 'getFieldLabels');

        $result = $this->view->getFieldLabels();
        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }
}

/*
<?php

declare(strict_types=1);

namespace Tests\classes\views\forms\proposals;

use PHPUnit\Framework\TestCase;
use App\views\forms\proposals\UpdateApplicationDateView;
use App\core\common\Debug;

class UpdateApplicationDateViewTest extends TestCase
{
    private $view;

    protected function setUp(): void
    {
        $mockDebug = $this->createMock(Debug::class);
        $this->view = new UpdateApplicationDateView(false, $mockDebug);
    }

    public function testRenderForm1Page(): void
    {
        $result = $this->view->renderForm1Page('Title', '/submit');
        $this->assertStringContainsString('<form', $result);
        $this->assertStringContainsString('Title', $result);
    }

    public function testRenderForm2Page(): void
    {
        $proposals = [
            ['ObsApp_id' => 1, 'ProgramNumber' => 100],
            ['ObsApp_id' => 2, 'ProgramNumber' => 101],
        ];

        $result = $this->view->renderForm2Page('Title', '/submit', $proposals);
        $this->assertStringContainsString('<form', $result);
        $this->assertStringContainsString('Title', $result);
        $this->assertStringContainsString('ProgramNumber: 100', $result);
    }

    public function testRenderForm3Page(): void
    {
        $proposal = ['creationDate' => '1672444800'];

        $result = $this->view->renderForm3Page('Title', '/submit', $proposal);
        $this->assertStringContainsString('<form', $result);
        $this->assertStringContainsString('Title', $result);
        $this->assertStringContainsString('1672444800', $result);
    }

    public function testRenderErrorPage(): void
    {
        $result = $this->view->renderErrorPage('Error Title', 'An error occurred.');
        $this->assertStringContainsString('Error Title', $result);
        $this->assertStringContainsString('An error occurred.', $result);
    }
}
*/
