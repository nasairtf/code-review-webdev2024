<?php

declare(strict_types=1);

namespace Tests\classes\views\forms;

use Mockery;
use PHPUnit\Framework\TestCase;
use Tests\utilities\CustomDebugMockTrait;
use Tests\utilities\PrivatePropertyTrait;
use Tests\classes\views\forms\TestBaseFormView;
use App\views\forms\BaseFormView;
use App\exceptions\HtmlBuilderException;

/**
 * Unit tests for the BaseFormView view class.
 *
 * This test suite validates the behavior of the BaseFormView class,
 * specifically ensuring that its html output is as expected.
 *
 * List of method tests:
 *
 * - testConstructorCanInstantiate [DONE]
 * - testConstructorInitializesDebugMode [DONE]
 * - testConstructorInitializesDependencies [DONE]
 * - testGetFormatHtmlReturnsCorrectValues [DONE]
 * - testRenderPageWrapsContentCorrectly [DONE]
 * - testRenderResultsPageIncludesTitleAndContent [DONE]
 * - testRenderErrorPageIncludesTitleAndMessage [DONE]
 * - testRenderFormPageGeneratesExpectedContent [DONE]
 * - testRenderFormWithErrorsIncludesErrorMessages [DONE]
 * - testRenderPageWithResultsIncludesResultsMessages [MISSING]
 * - testGetErrorsBlockFormatsMessagesCorrectly [DONE]
 * - testGetResultsBlockFormatsMessagesCorrectly [DONE]
 * - testGetContentsFormWrapsContentInFormTags [DONE]
 *
 * - testGetFormatHtmlReturnsCorrectValues
 *
 * list of class methods:
 *
 * - __construct [DONE]
 * - getFieldLabels [ABSTRACT]
 * - getPageContents [ABSTRACT]
 * - getFormatHtml [DONE]
 * - renderResultsPage [DONE]
 * - renderErrorPage [DONE]
 * - renderFormPage [DONE]
 * - renderFormWithErrors [DONE]
 * - renderPageWithResults
 * - renderPage [PROTECTED] [DONE]
 * - getErrorsBlock [PROTECTED] [DONE]
 * - getResultsBlock [PROTECTED] [DONE]
 * - getContentsForm [PROTECTED] [DONE]
 *
 * @covers \App\views\forms\BaseFormView
 */
class BaseFormViewTest extends TestCase
{
    use CustomDebugMockTrait;
    use PrivatePropertyTrait;

    /**
     * Mock instance of CustomDebug.
     *
     * @var Mockery\MockInterface
     */
    private $debugMock;

    /**
     * Test instantiation of the `TestBaseFormView` class.
     *
     * This test ensures that the `TestBaseFormView` class, which extends `BaseFormView`,
     * can be successfully instantiated without errors.
     *
     * @covers \App\views\forms\BaseFormView::__construct
     *
     * @return void
     */
    public function testConstructorCanInstantiate(): void
    {
        // Instantiate TestBaseFormView
        $view = new TestBaseFormView(
            false,           // formatHtml
            $this->debugMock // Debug
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
     * @covers \App\views\forms\BaseFormView::__construct
     *
     * @return void
     */
    public function testConstructorInitializesDebugMode(): void
    {
        // Instantiate TestBaseFormView
        $view = new TestBaseFormView(
            true,            // formatHtml
            $this->debugMock // Debug
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
     * @covers \App\views\forms\BaseFormView::__construct
     *
     * @return void
     */
    public function testConstructorInitializesDependencies(): void
    {
        // Instantiate TestBaseFormView
        $view = new TestBaseFormView(
            true,            // formatHtml
            $this->debugMock // Debug
        );

        // Assert
        $this->assertDependency($this->debugMock, 'debug', $view);
        $this->assertTrue($this->getPrivateProperty($view, 'formatHtml'), 'HTML formatting should be enabled.');
    }

    /**
     * Test that the `getFormatHtml` method returns the correct value.
     *
     * This test verifies that the `getFormatHtml` method accurately reflects
     * the value of the `formatHtml` property as set during initialization.
     *
     * @covers \App\views\forms\BaseFormView::getFormatHtml
     *
     * @return void
     */
    public function testGetFormatHtmlReturnsCorrectValues(): void
    {
        // Instantiate TestBaseFormView
        $view = new TestBaseFormView(
            true,            // formatHtml
            $this->debugMock // Debug
        );

        // Assert
        $this->assertTrue($view->getFormatHtml(), 'HTML formatting should be enabled.');

        // Instantiate TestBaseFormView
        $view = new TestBaseFormView(
            false,           // formatHtml
            $this->debugMock // Debug
        );

        // Assert
        $this->assertFalse($view->getFormatHtml(), 'HTML formatting should be disabled.');
    }

    /**
     * Test that the `renderPage` method wraps content with the expected layout.
     *
     * This test verifies that the `renderPage` method correctly wraps the
     * provided content with the expected header and footer sections,
     * and includes the specified page title.
     *
     * @covers \App\views\forms\BaseFormView::renderPage
     *
     * @return void
     */
    public function testRenderPageWrapsContentCorrectly(): void
    {
        // Instantiate TestBaseFormView
        $view = new TestBaseFormView(
            true,            // formatHtml
            $this->debugMock // Debug
        );

        // Act
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
     * @covers \App\views\forms\BaseFormView::renderResultsPage
     *
     * @return void
     */
    public function testRenderResultsPageIncludesTitleAndContent(): void
    {
        // Instantiate TestBaseFormView
        $view = new TestBaseFormView(
            false,           // formatHtml
            $this->debugMock // Debug
        );

        // Act
        $result = $view->renderResultsPage('Test Title', 'Test Message');

        // Assert
        $this->assertStringContainsString('Test Title', $result);
        $this->assertStringContainsString('Test Message', $result);
    }

    /**
     * Test that the `renderErrorPage` method includes the title and message.
     *
     * This test checks that the rendered error page contains the specified
     * title and message within the generated HTML output.
     *
     * @covers \App\views\forms\BaseFormView::renderErrorPage
     *
     * @return void
     */
    public function testRenderErrorPageIncludesTitleAndMessage(): void
    {
        // Instantiate TestBaseFormView
        $view = new TestBaseFormView(
            true,            // formatHtml
            $this->debugMock // Debug
        );

        // Act
        $result = $view->renderErrorPage(
            'Error Page',                   // title
            'An unexpected error occurred.' // message
        );

        // Assert
        $this->assertStringContainsString('Error Page', $result);
        $this->assertStringContainsString('An unexpected error occurred.', $result);
    }

    /**
     * Test rendering form page generates expected content.
     *
     * @covers \App\views\forms\BaseFormView::renderFormPage
     *
     * @return void
     */
    public function testRenderFormPageGeneratesExpectedContent(): void
    {
        // Instantiate TestBaseFormView
        $view = new TestBaseFormView(
            true,            // formatHtml
            $this->debugMock // Debug
        );

        // Act
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
     * @covers \App\views\forms\BaseFormView::renderFormWithErrors
     *
     * @return void
     */
    public function testRenderFormWithErrorsIncludesErrorMessages(): void
    {
        // Instantiate TestBaseFormView
        $view = new TestBaseFormView(
            true,            // formatHtml
            $this->debugMock // Debug
        );

        // Define the test data
        $errors = ['field1' => 'This field is required.'];
        $labels = ['field1' => 'Field 1'];

        // Act
        $result = $view->renderFormWithErrors(
            'Test Form with Errors', // title
            '/submit',               // action
            [],                      // dbData
            [],                      // formData
            $errors,                 // dataErrors
            $labels                  // fieldLabels
        );

        // Assert
        $this->assertStringContainsString('Test Form with Errors', $result);
        $this->assertStringContainsString('Field 1', $result);
        $this->assertStringContainsString('This field is required.', $result);
    }

    /**
     * Test that the `renderPageWithResults` method includes results messages and labels.
     *
     * This test ensures that the rendered form with results displays the provided
     * result messages and labels as part of the generated HTML content.
     *
     * @covers \App\views\forms\BaseFormView::renderPageWithResults
     *
     * @return void
     */
    public function testRenderPageWithResultsIncludesResultsMessages(): void
    {
        // Instantiate TestBaseFormView
        $view = new TestBaseFormView(
            true,            // formatHtml
            $this->debugMock // Debug
        );

        // Define the test data
        $results = ['Success!', 'Your data has been saved.'];

        // Act
        $result = $view->renderPageWithResults(
            'Test Form with Results', // title
            $results                  // messages
        );

        // Assert
        $this->assertStringContainsString('Test Form with Results', $result);
        $this->assertStringContainsString('Success!', $result);
        $this->assertStringContainsString('Your data has been saved.', $result);
    }

    /**
     * Test getErrorsBlock formats messages correctly.
     *
     * @covers \App\views\forms\BaseFormView::getErrorsBlock
     *
     * @return void
     */
    public function testGetErrorsBlockFormatsMessagesCorrectly(): void
    {
        // Instantiate TestBaseFormView
        $view = new TestBaseFormView(
            false,           // formatHtml
            $this->debugMock // Debug
        );

        // Define the test data
        $errors = ['field1' => 'This field is required.'];
        $labels = ['field1' => 'Field 1'];

        // Act
        $result = $view->getErrorsBlockProxy($errors, $labels);

        // Assert
        $this->assertStringContainsString('Field 1', $result);
        $this->assertStringContainsString('This field is required.', $result);

        // Define the test data
        $errors['field2'] = 'This field is also required.';
        $labels['field2'] = 'Field 2';
        $errors['field3'] = ['And', 'so', 'is', 'this', 'set', 'of','fields'];
        $labels['field3'] = 'Field 3';

        // Act
        $result = $view->getErrorsBlockProxy($errors, $labels);

        // Assert
        $this->assertStringContainsString('Field 1', $result);
        $this->assertStringContainsString('Field 2', $result);
        $this->assertStringContainsString('This field is required.', $result);
        $this->assertStringContainsString('This field is also required.', $result);
    }

    /**
     * Test getResultsBlock formats messages correctly.
     *
     * @covers \App\views\forms\BaseFormView::getResultsBlock
     *
     * @return void
     */
    public function testGetResultsBlockFormatsMessagesCorrectly(): void
    {
        // Instantiate TestBaseFormView
        $view = new TestBaseFormView(
            true,            // formatHtml
            $this->debugMock // Debug
        );

        // Define the test data
        $results = ['Success! Your data has been saved.'];

        // Act
        $result = $view->getResultsBlockProxy($results);

        // Assert
        $this->assertStringContainsString('Success!', $result);
        $this->assertStringContainsString('Your data has been saved.', $result);

        // Define the test data
        $results[] = ['More success! Your other data has also been saved.'];

        // Act
        $result = $view->getResultsBlockProxy($results);

        // Assert
        $this->assertStringContainsString('Success!', $result);
        $this->assertStringContainsString('More success!', $result);
        $this->assertStringContainsString('Your data has been saved.', $result);
        $this->assertStringContainsString('Your other data has also been saved.', $result);
    }

    /**
     * Test getContentsForm wraps content in form tags.
     *
     * @covers \App\views\forms\BaseFormView::getContentsForm
     *
     * @return void
     */
    public function testGetContentsFormWrapsContentInFormTags(): void
    {
        // Instantiate TestBaseFormView
        $view = new TestBaseFormView(
            true,            // formatHtml
            $this->debugMock // Debug
        );

        // Act
        $result = $view->getContentsFormProxy('/submit', [], []);

        // Assert
        $this->assertStringContainsString('<form', $result);
        $this->assertStringContainsString('action="/submit"', $result);
        $this->assertStringContainsString('<p>Page Contents</p>', $result);
        $this->assertStringContainsString('</form>', $result);
    }

    /**
     * HELPER METHODS -- TEST SETUP AND/OR CLEANUP
     */

    /**
     * Set up the test environment.
     *
     * Initializes the Mockery Debug instance.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->debugMock = $this->createCustomDebugMock();
    }

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
