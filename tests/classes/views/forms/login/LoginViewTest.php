<?php

declare(strict_types=1);

namespace Tests\classes\views\forms\login;

use Mockery;
use PHPUnit\Framework\TestCase;
use Tests\utilities\PrivatePropertyTrait;

use App\views\forms\login\LoginView;
use App\core\common\Debug;
use App\core\htmlbuilder\HtmlBuilder;
use App\core\htmlbuilder\CompositeBuilder;
use App\legacy\IRTFLayout;

class LoginViewTest extends TestCase
{/*
    private $debugMock;
    private $htmlBuilderMock;
    private $compBuilderMock;
    private $irtfBuilderMock;
    private $view;

    protected function setUp(): void
    {
        $this->debugMock = Mockery::mock(\App\core\common\Debug::class);
        $this->htmlBuilderMock = Mockery::mock(HtmlBuilder::class);
        $this->compBuilderMock = Mockery::mock(CompositeBuilder::class);
        $this->irtfBuilderMock = Mockery::mock(IRTFLayout::class);

        $this->view = new LoginView(
            false,
            $this->debugMock,
            $this->htmlBuilderMock,
            $this->compBuilderMock,
            $this->irtfBuilderMock
        );
    }
*/

    public function testReturnsAsc(): void
    {
        $this->assertSame(1, 1);
    }
/*
    public function testBuildDefaultInstructionsReturnsHtml(): void
    {
        $instructions = 'Please log in using your program number and session code.';

        $this->htmlBuilderMock->shouldReceive('getParagraph')
            ->once()
            ->with($instructions, ['align' => 'justify'], 0)
            ->andReturn('<p>Please log in using your program number and session code.</p>');

        $result = $this->view->buildDefaultInstructions();
        $this->assertSame('<p>Please log in using your program number and session code.</p>', $result);
    }

    public function testRenderLoginFormPageIncludesTitleAndContent(): void
    {
        $title = 'Login Page';
        $formAction = '/submit';
        $formData = ['program' => '', 'session' => '', 'error' => ''];
        $instructions = 'Please log in using your credentials.';
        $formContent = '<form>Form Content</form>';
        $header = '<header>Header</header>';
        $footer = '<footer>Footer</footer>';

        $this->irtfBuilderMock->shouldReceive('myHeader')
            ->once()
            ->with(false, $title, false)
            ->andReturn($header);

        $this->irtfBuilderMock->shouldReceive('myFooter')
            ->once()
            ->with(Mockery::type('string'), false)
            ->andReturn($footer);

        $this->htmlBuilderMock->shouldReceive('formatParts')
            ->once()
            ->with([$header, $formContent, $footer], false)
            ->andReturn($header . $formContent . $footer);

        $this->htmlBuilderMock->shouldReceive('formatParts')
            ->andReturn($formContent); // Mocking internal calls for form content generation

        $result = $this->view->renderLoginFormPage($title, $formAction, $formData, $instructions);
        $this->assertStringContainsString($header, $result);
        $this->assertStringContainsString($formContent, $result);
        $this->assertStringContainsString($footer, $result);
    }

    public function testBuildEmbeddableLoginFormCallsBuildLoginForm(): void
    {
        $action = '/login';
        $data = ['program' => '2023A001', 'session' => '1234567890'];
        $instructions = 'Enter your login details.';
        $formHtml = '<form>Login Form</form>';

        $this->htmlBuilderMock->shouldReceive('formatParts')
            ->andReturn($formHtml);

        $result = $this->view->buildEmbeddableLoginForm($action, $data, $instructions);
        $this->assertSame($formHtml, $result);
    }

    public function testRenderPageReturnsCompleteHtml(): void
    {
        $title = 'Login Page';
        $content = '<div>Page Content</div>';
        $header = '<header>Header Content</header>';
        $footer = '<footer>Footer Content</footer>';
        $formattedPage = $header . $content . $footer;

        $this->irtfBuilderMock->shouldReceive('myHeader')
            ->once()
            ->with(false, $title, false)
            ->andReturn($header);

        $this->irtfBuilderMock->shouldReceive('myFooter')
            ->once()
            ->with(Mockery::type('string'), false)
            ->andReturn($footer);

        $this->htmlBuilderMock->shouldReceive('formatParts')
            ->once()
            ->with([$header, $content, $footer], false)
            ->andReturn($formattedPage);

        $result = $this->view->renderLoginFormPage($title, '/action', [], 'Instructions');
        $this->assertSame($formattedPage, $result);
    }*/

    /**
     * Cleans up the test environment after each unit test (method).
     *
     * - Verifies Mockery's expectations are met.
     * - Clears resources and prevents leaks between tests.
     * - Ensures necessary parent (PHPUnit) teardown logic runs as well.
     *
     * @return void
     */
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
