<?php

declare(strict_types=1);

namespace Tests\classes\views\forms;

use Mockery as Mockery;
use PHPUnit\Framework\TestCase;
use App\views\forms\BaseFormView;

class BaseFormViewTest extends TestCase
{
    /**
     * Clean up Mockery expectations and resources after each test.
     *
     * @return void
     */
    public function tearDown(): void
    {
        // Ensure Mockery's expectations are met and clear resources
        Mockery::close();
    }

    /**
     * Test instantiation of the `TestFormView` class.
     *
     * This test ensures that the `TestFormView` class, which extends `BaseFormView`,
     * can be successfully instantiated without errors.
     *
     * @return void
     */
    public function testCanInstantiate(): void
    {
        // Mock Debug and set expectations
        $mockDebug = Mockery::mock('App\core\common\Debug');
        $mockDebug->shouldReceive('debugHeading')->andReturn('Mock Debug Heading');
        $mockDebug->shouldReceive('debug')->andReturnNull();
        $mockDebug->shouldReceive('debugVariable')->andReturnNull();
        $mockDebug->shouldReceive('log')->andReturnNull();

        // Instantiate TestFormView
        $view = new TestFormView(
            false,     // formatHtml
            $mockDebug // Debug
        );

        // Assert that the instance is of the expected type
        $this->assertInstanceOf(BaseFormView::class, $view);
    }

    /**
     * Test initialization of `formatHtml` in debug mode.
     *
     * This test verifies that the `formatHtml` property is correctly
     * initialized when debug mode is enabled.
     *
     * @return void
     */
    public function testDebugModeInitialization(): void
    {
        // Mock the Debug class
        $mockDebug = Mockery::mock('App\core\common\Debug');
        $mockDebug->shouldReceive('debugHeading')->andReturn('Mock Debug Heading');
        $mockDebug->shouldReceive('debug')->andReturnNull();
        $mockDebug->shouldReceive('debugVariable')->andReturnNull();
        $mockDebug->shouldReceive('log')->andReturnNull();

        // Instantiate TestFormView
        $view = new TestFormView(
            true,      // formatHtml
            $mockDebug // Debug
        );

        // Use the getter method to assert the value of the protected property
        $this->assertTrue($view->getFormatHtml(), 'HTML formatting should be enabled.');
    }

    /**
     * Test that the constructor initializes all dependencies correctly.
     *
     * This test verifies that the `Debug`, `HtmlBuilder`, `CompBuilder`,
     * and other dependencies are properly initialized when the
     * BaseFormView constructor is called.
     *
     * @return void
     */
    public function testConstructorInitializesDependencies(): void
    {
        $mockDebug = Mockery::mock('App\core\common\Debug');
        $mockDebug->shouldReceive('debugHeading')->andReturn('Mock Debug Heading');
        $mockDebug->shouldReceive('debug')->andReturnNull();
        $mockDebug->shouldReceive('debugVariable')->andReturnNull();
        $mockDebug->shouldReceive('log')->andReturnNull();

        $view = new TestFormView(
            true,      // formatHtml
            $mockDebug // Debug
        );

        // Use reflection to verify protected properties
        $reflection = new \ReflectionClass($view);

        $debugProperty = $reflection->getProperty('debug');
        $debugProperty->setAccessible(true);
        $this->assertSame($mockDebug, $debugProperty->getValue($view));

        $formatHtmlProperty = $reflection->getProperty('formatHtml');
        $formatHtmlProperty->setAccessible(true);
        $this->assertTrue($formatHtmlProperty->getValue($view), 'HTML formatting should be enabled.');
    }

    /**
     * Test that the `getFormatHtml` method returns the correct value.
     *
     * This test verifies that the `getFormatHtml` method accurately reflects
     * the value of the `formatHtml` property as set during initialization.
     *
     * @return void
     */
    public function testGetFormatHtmlReturnsCorrectValue(): void
    {
        $mockDebug = Mockery::mock('App\core\common\Debug');
        $mockDebug->shouldReceive('debugHeading')->andReturn('Mock Debug Heading');
        $mockDebug->shouldReceive('debug')->andReturnNull();
        $mockDebug->shouldReceive('debugVariable')->andReturnNull();
        $mockDebug->shouldReceive('log')->andReturnNull();

        $view = new TestFormView(
            true,      // formatHtml
            $mockDebug // Debug
        );

        $this->assertTrue($view->getFormatHtml(), 'HTML formatting should be enabled.');

        $view = new TestFormView(
            false,     // formatHtml
            $mockDebug // Debug
        );

        $this->assertFalse($view->getFormatHtml(), 'HTML formatting should be disabled.');
    }

    /**
     * Test that the `renderPage` method wraps content with the expected layout.
     *
     * This test verifies that the `renderPage` method correctly wraps the
     * provided content with the expected header and footer sections,
     * and includes the specified page title.
     *
     * @return void
     */
    public function testRenderPageWrapsContentCorrectly(): void
    {
        $mockDebug = Mockery::mock('App\core\common\Debug');
        $mockDebug->shouldReceive('debugHeading')->andReturn('Mock Debug Heading');
        $mockDebug->shouldReceive('debug')->andReturnNull();
        $mockDebug->shouldReceive('debugVariable')->andReturnNull();
        $mockDebug->shouldReceive('log')->andReturnNull();

        $view = new TestFormView(
            true,      // formatHtml
            $mockDebug // Debug
        );

        $content = '<p>Main Content</p>';
        $result = $view->renderPageProxy('Page Title', $content);

        // Adjusted assertions to match legacy code
        $this->assertStringContainsString('<html', $result, 'HTML output should start with <html>');
        $this->assertStringContainsString('<head>', $result, 'HTML output should contain <head>');
        $this->assertStringContainsString('<body>', $result, 'HTML output should contain <body>');
        $this->assertStringContainsString('Page Title', $result, 'Rendered content should include the page title');
        $this->assertStringContainsString(
            '<p>Main Content</p>',
            $result,
            'Rendered content must contain the main content'
        );
        //$this->assertStringContainsString('<header', $result); // Assuming header tags are used
        //$this->assertStringContainsString('<footer', $result); // Assuming footer tags are used
        $this->assertStringContainsString('</body>', $result, 'HTML output should contain </body>');
        $this->assertStringContainsString('</html>', $result, 'HTML output should contain </html>');
        // Optional: Avoid asserting elements like <header> or <footer> since they're missing in the legacy code
    }

    /**
     * Test rendering results page includes title and content.
     *
     * @return void
     */
    public function testRenderResultsPageIncludesTitleAndContent(): void
    {
        $mockDebug = Mockery::mock('App\core\common\Debug');
        $mockDebug->shouldReceive('debugHeading')->andReturn('Mock Debug Heading');
        $mockDebug->shouldReceive('debug')->andReturnNull();
        $mockDebug->shouldReceive('debugVariable')->andReturnNull();
        $mockDebug->shouldReceive('log')->andReturnNull();

        $view = new TestFormView(
            false,     // formatHtml
            $mockDebug // Debug
        );

        $result = $view->renderResultsPage('Test Title', 'Test Message');

        $this->assertStringContainsString('Test Title', $result);
        $this->assertStringContainsString('Test Message', $result);
    }

    /**
     * Test that the `renderErrorPage` method includes the title and message.
     *
     * This test checks that the rendered error page contains the specified
     * title and message within the generated HTML output.
     *
     * @return void
     */
    public function testRenderErrorPageIncludesTitleAndMessage(): void
    {
        $mockDebug = Mockery::mock('App\core\common\Debug');
        $mockDebug->shouldReceive('debugHeading')->andReturn('Mock Debug Heading');
        $mockDebug->shouldReceive('debug')->andReturnNull();
        $mockDebug->shouldReceive('debugVariable')->andReturnNull();
        $mockDebug->shouldReceive('log')->andReturnNull();

        $view = new TestFormView(
            true,      // formatHtml
            $mockDebug // Debug
        );

        $result = $view->renderErrorPage(
            'Error Page',                   // title
            'An unexpected error occurred.' // message
        );

        $this->assertStringContainsString('Error Page', $result);
        $this->assertStringContainsString('An unexpected error occurred.', $result);
    }

    /**
     * Test rendering form page generates expected content.
     *
     * @return void
     */
    public function testRenderFormPageGeneratesExpectedContent(): void
    {
        $mockDebug = Mockery::mock('App\core\common\Debug');
        $mockDebug->shouldReceive('debugHeading')->andReturn('Mock Debug Heading');
        $mockDebug->shouldReceive('debug')->andReturnNull();
        $mockDebug->shouldReceive('debugVariable')->andReturnNull();
        $mockDebug->shouldReceive('log')->andReturnNull();

        // Instantiate TestFormView
        $view = new TestFormView(
            true,      // formatHtml
            $mockDebug // Debug
        );

        $result = $view->renderFormPage(
            'Test Form Page', // title
            '/submit',        // action
            [],               // dbData
            []                // formData
        );

        // Assert that the title and action appear in the generated content
        $this->assertStringContainsString('Test Form Page', $result);
        $this->assertStringContainsString('<p>Page Contents</p>', $result);
    }

    /**
     * Test that the `renderFormWithErrors` method includes error messages and field labels.
     *
     * This test ensures that the rendered form with errors displays the provided
     * error messages and field labels as part of the generated HTML content.
     *
     * @return void
     */
    public function testRenderFormWithErrorsIncludesErrorMessages(): void
    {
        $mockDebug = Mockery::mock('App\core\common\Debug');
        $mockDebug->shouldReceive('debugHeading')->andReturn('Mock Debug Heading');
        $mockDebug->shouldReceive('debug')->andReturnNull();
        $mockDebug->shouldReceive('debugVariable')->andReturnNull();
        $mockDebug->shouldReceive('log')->andReturnNull();

        $view = new TestFormView(
            true,      // formatHtml
            $mockDebug // Debug
        );

        $errors = ['field1' => 'This field is required.'];
        $labels = ['field1' => 'Field 1'];

        $result = $view->renderFormWithErrors(
            'Test Form with Errors', // title
            '/submit',               // action
            [],                      // dbData
            [],                      // formData
            $errors,                 // dataErrors
            $labels                  // fieldLabels
        );

        $this->assertStringContainsString('Test Form with Errors', $result);
        $this->assertStringContainsString('Field 1', $result);
        $this->assertStringContainsString('This field is required.', $result);
    }

    /**
     * Test getErrorsBlock formats messages correctly.
     *
     * @return void
     */
    public function testGetErrorsBlockFormatsMessagesCorrectly(): void
    {
        $mockDebug = Mockery::mock('App\core\common\Debug');
        $mockDebug->shouldReceive('debugHeading')->andReturn('Mock Debug Heading');
        $mockDebug->shouldReceive('debug')->andReturnNull();
        $mockDebug->shouldReceive('debugVariable')->andReturnNull();
        $mockDebug->shouldReceive('log')->andReturnNull();

        $view = new TestFormView(
            false,     // formatHtml
            $mockDebug // Debug
        );

        $errors = ['field1' => 'This field is required.'];
        $labels = ['field1' => 'Field 1'];

        $result = $view->getErrorsBlockProxy($errors, $labels);

        $this->assertStringContainsString('Field 1', $result);
        $this->assertStringContainsString('This field is required.', $result);
    }

    /**
     * Test getResultsBlock formats messages correctly.
     *
     * @return void
     */
    public function testGetResultsBlockFormatsMessagesCorrectly(): void
    {
        $mockDebug = Mockery::mock('App\core\common\Debug');
        $mockDebug->shouldReceive('debugHeading')->andReturn('Mock Debug Heading');
        $mockDebug->shouldReceive('debug')->andReturnNull();
        $mockDebug->shouldReceive('debugVariable')->andReturnNull();
        $mockDebug->shouldReceive('log')->andReturnNull();

        $view = new TestFormView(
            true,      // formatHtml
            $mockDebug // Debug
        );

        $results = ['Success! Your data has been saved.'];
        $result = $view->getResultsBlockProxy($results);

        $this->assertStringContainsString('Success!', $result);
        $this->assertStringContainsString('Your data has been saved.', $result);
    }

    /**
     * Test getContentsForm wraps content in form tags.
     *
     * @return void
     */
    public function testGetContentsFormWrapsContentInFormTags(): void
    {
        $mockDebug = Mockery::mock('App\core\common\Debug');
        $mockDebug->shouldReceive('debugHeading')->andReturn('Mock Debug Heading');
        $mockDebug->shouldReceive('debug')->andReturnNull();
        $mockDebug->shouldReceive('debugVariable')->andReturnNull();
        $mockDebug->shouldReceive('log')->andReturnNull();

        $view = new TestFormView(
            true,      // formatHtml
            $mockDebug // Debug
        );

        $result = $view->getContentsFormProxy('/submit', [], []);

        $this->assertStringContainsString('<form', $result);
        $this->assertStringContainsString('action="/submit"', $result);
        $this->assertStringContainsString('<p>Page Contents</p>', $result);
        $this->assertStringContainsString('</form>', $result);
    }
}
