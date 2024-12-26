<?php

declare(strict_types=1);

namespace App\views\forms;

use App\core\common\CustomDebug;
use App\exceptions\HtmlBuilderException;
use App\core\htmlbuilder\HtmlBuilder as HtmlBuilder;
use App\core\htmlbuilder\CompositeBuilder as CompBuilder;
use App\legacy\IRTFLayout as IrtfBuilder;

/**
 * Base class for rendering form views.
 *
 * This abstract class provides standard functionality for generating HTML forms
 * and results/error pages, with integrated debugging and layout building tools.
 * Child classes are expected to implement specific methods to define the form
 * structure and field labels.
 *
 * Key Features:
 * - Support for formatted (readable) HTML output.
 * - Debugging utilities for tracking rendering and form processing logic.
 * - Integration with multiple HTML builders for modular page construction.
 *
 * @category Views
 * @package  IRTF
 * @version  1.0.0
 */

abstract class BaseFormView
{
    /**
     * Whether to produce formatted (readable) HTML output.
     *
     * Determines if the HTML builders should include line breaks and indentation
     * in the generated HTML for better readability during development.
     * Defaults to `false` if not explicitly set.
     *
     * @var bool
     */
    protected $formatHtml;

    /**
     * CustomDebug instance for logging and debugging output.
     *
     * Provides methods for structured debug messages and error tracking. If no
     * instance is provided, a default instance is initialized with debug mode
     * disabled.
     *
     * @var CustomDebug
     */
    protected $debug;

    /**
     * Instance for constructing individual HTML elements.
     *
     * This builder is used to create basic HTML components such as tables,
     * paragraphs, and forms. It supports formatting based on the `$formatHtml` setting.
     *
     * @var HtmlBuilder
     */
    protected $htmlBuilder;

    /**
     * Instance for building composite HTML components.
     *
     * Used to generate higher-level HTML constructs like forms with validation errors
     * or results tables, combining multiple basic elements. Inherits formatting preferences
     * from the `$htmlBuilder`.
     *
     * @var CompBuilder
     */
    protected $compBuilder;

    /**
     * Legacy layout builder for header/footer content.
     *
     * Handles specific layout components, such as the page header and footer, based on
     * legacy IRTF requirements. This is primarily used for wrapping form content in
     * site-wide templates.
     *
     * @var IrtfBuilder
     */
    protected $irtfBuilder;

    /**
     * Constructor for the BaseFormView class.
     *
     * Initializes debugging, formatting preferences, and the necessary builder instances. Defaults are provided
     * if no specific instances or configurations are passed.
     *
     * @param bool|null        $formatHtml  Enable formatted HTML output. Defaults to false if not provided.
     * @param Debug|null       $debug       Debug instance for logging and debugging. Defaults to a new Debug instance.
     * @param HtmlBuilder|null $htmlBuilder Instance for constructing HTML elements. Defaults to a new HtmlBuilder.
     * @param CompBuilder|null $compBuilder Instance for composite HTML elements. Defaults to a new CompBuilder.
     * @param IrtfBuilder|null $irtfBuilder Legacy layout builder for site meta. Defaults to a new IrtfBuilder.
     */
    public function __construct(
        ?bool $formatHtml = null,
        ?CustomDebug $debug = null,
        ?HtmlBuilder $htmlBuilder = null,
        ?CompBuilder $compBuilder = null,
        ?IrtfBuilder $irtfBuilder = null
    ) {
        // Initialize debugging
        $this->debug = $debug ?? new CustomDebug('default', false, 0);
        $debugHeading = $this->debug->debugHeading("View", "__construct");
        $this->debug->debug($debugHeading);

        // Set global HTML formatting preference
        $this->formatHtml = $formatHtml ?? false;
        $this->debug->debugVariable($this->formatHtml, "{$debugHeading} -- this->formatHtml");

        // Initialize builder instances
        $this->htmlBuilder = $htmlBuilder ?? new HtmlBuilder($this->formatHtml);
        $this->compBuilder = $compBuilder ?? new CompBuilder($this->formatHtml, $this->htmlBuilder);
        $this->irtfBuilder = $irtfBuilder ?? new IrtfBuilder();
        $this->debug->log("{$debugHeading} -- HtmlBuilder, CompBuilder, IrtfBuilder successfully initialised.");

        // Constructor completed
        $this->debug->log("{$debugHeading} -- Parent View initialisation complete.");
    }

    /**
     * Abstract method to provide field labels for the form.
     *
     * Child classes must implement this method to map internal field names
     * to user-friendly labels for display in forms and error messages. For
     * non-form views, this method can be implemented as a stub returning an
     * empty array or a default mapping.
     *
     * @return array An associative array mapping field names to labels.
     */
    abstract public function getFieldLabels(): array;

    /**
     * Abstract method to generate the main page content for a form.
     *
     * Each child class must implement this method to define the specific
     * content structure for its form. By default, data arrays are empty
     * and padding is set to 0.
     *
     * @param array $dbData   Data arrays required to populate form options. Defaults to an empty array.
     * @param array $formData Default data for form fields. Defaults to an empty array.
     * @param int   $pad      Optional padding level for formatted output. Defaults to 0.
     *
     * @return string The HTML content for the form page.
     */
    abstract protected function getPageContents(
        array $dbData = [],
        array $formData = [],
        int $pad = 0
    ): string;

    /**
     * Retrieves the HTML formatting preference.
     *
     * This method returns the value of the `$formatHtml` property,
     * which indicates whether the HTML output should be formatted
     * with indentation and line breaks for readability.
     *
     * @return bool True if HTML formatting is enabled; false otherwise.
     */
    public function getFormatHtml(): bool
    {
        return $this->formatHtml;
    }

    /**
     * Renders the results page after form submission.
     *
     * This method generates a standardized results page using a message
     * and wraps it with the site's standard layout.
     *
     * @param string $title   The title of the results page.
     * @param string $message The message to display on the results page.
     *
     * @return string The complete HTML of the results page.
     */
    public function renderResultsPage(
        string $title = '',
        string $message = ''
    ): string {
        // Debug output
        $debugHeading = $this->debug->debugHeading("View", "renderResultsPage");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($title, "{$debugHeading} -- title");
        $this->debug->debugVariable($message, "{$debugHeading} -- message");

        // Generate the results page contents
        $content = $this->compBuilder->buildResultsPage($message, [], 0);
        return $this->renderPage($title, $content);
    }

    /**
     * Renders an error page with a provided title and message.
     *
     * This method generates a standardized error page using the provided
     * message and wraps it with the site's standard layout.
     *
     * @param string $title   The title of the error page.
     * @param string $message The error message to display on the page.
     *
     * @return string The complete HTML of the error page.
     */
    public function renderErrorPage(
        string $title = '',
        string $message = ''
    ): string {
        // Debug output
        $debugHeading = $this->debug->debugHeading("View", "renderErrorPage");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($title, "{$debugHeading} -- title");
        $this->debug->debugVariable($message, "{$debugHeading} -- message");

        // Generate the error page contents
        $content = $this->compBuilder->buildErrorPage($message, [], 0);
        return $this->renderPage($title, $content);
    }

    /**
     * Renders the main form page.
     *
     * This method generates a form page with the provided title and form data.
     * It wraps the form in the site's standard layout.
     *
     * @param string $title    The title of the form page.
     * @param string $action   The form submission URL.
     * @param array  $dbData   Data arrays required to populate form options.
     * @param array  $formData Default data for form fields.
     * @param int    $pad      Optional padding level for formatted output (default: 0).
     *
     * @return string The complete HTML of the form page.
     */
    public function renderFormPage(
        string $title = '',
        string $action = '',
        array $dbData = [],
        array $formData = [],
        int $pad = 0
    ): string {
        // Debug output
        $debugHeading = $this->debug->debugHeading("View", "renderFormPage");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($title, "{$debugHeading} -- title");
        $this->debug->debugVariable($action, "{$debugHeading} -- action");
        $this->debug->debugVariable($dbData, "{$debugHeading} -- dbData");
        $this->debug->debugVariable($formData, "{$debugHeading} -- formData");
        $this->debug->debugVariable($pad, "{$debugHeading} -- pad");

        // Wrap form tags around the body content
        $content = $this->getContentsForm($action, $dbData, $formData, $pad);
        return $this->renderPage($title, $content);
    }

    /**
     * Renders the main form page with validation errors displayed.
     *
     * This method generates a form page that includes a block for displaying
     * validation error messages above the form. The page is wrapped in the site's
     * standard layout.
     *
     * @param string $title       The title of the form page.
     * @param string $action      The form submission URL.
     * @param array  $dbData      Data arrays required to populate form options.
     * @param array  $formData    Default data for form fields.
     * @param array  $dataErrors  Validation error messages for fields.
     * @param array  $fieldLabels Labels for form fields to display with errors.
     * @param int    $pad         Optional padding level for formatted output (default: 0).
     *
     * @return string The complete HTML of the form page with validation errors.
     */
    public function renderFormWithErrors(
        string $title = '',
        string $action = '',
        array $dbData = [],
        array $formData = [],
        array $dataErrors = [],
        array $fieldLabels = [],
        int $pad = 0
    ): string {
        // Debug output
        $debugHeading = $this->debug->debugHeading("View", "renderFormWithErrors");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($title, "{$debugHeading} -- title");
        $this->debug->debugVariable($action, "{$debugHeading} -- action");
        $this->debug->debugVariable($dbData, "{$debugHeading} -- dbData");
        $this->debug->debugVariable($formData, "{$debugHeading} -- formData");
        $this->debug->debugVariable($dataErrors, "{$debugHeading} -- dataErrors");
        $this->debug->debugVariable($fieldLabels, "{$debugHeading} -- fieldLabels");
        $this->debug->debugVariable($pad, "{$debugHeading} -- pad");

        // Render the errors section and the form
        return $this->renderPage(
            $title,
            $this->htmlBuilder->formatParts(
                [
                    $this->getErrorsBlock($dataErrors, $fieldLabels, $pad),
                    $this->getContentsForm($action, $dbData, $formData, $pad),
                ],
                $this->formatHtml
            )
        );
    }

    /**
     * Renders the results page after form submission.
     *
     * This method generates a standardized results page using a message
     * and wraps it with the site's standard layout.
     *
     * @param string $title   The title of the results page.
     * @param string $message The message to display on the results page.
     *
     * @return string The complete HTML of the results page.
     */
    public function renderPageWithResults(
        string $title = '',
        array $messages = []
    ): string {
        // Debug output
        $debugHeading = $this->debug->debugHeading("View", "renderPageWithResults");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($title, "{$debugHeading} -- title");
        $this->debug->debugVariable($messages, "{$debugHeading} -- messages");

        // Generate the results block
        $resultBlock = $this->getResultsBlock($messages, 0);

        // Generate the results page contents
        $content = $this->compBuilder->buildResultsBlockPage($resultBlock, [], 0);
        return $this->renderPage($title, $content);
    }

    /**
     * Renders a complete HTML page with a title and content.
     *
     * This method wraps the provided content in the site's standard header
     * and footer layout.
     *
     * @param string $title   The title of the page.
     * @param string $content The HTML content of the page.
     *
     * @return string The complete HTML of the page.
     */
    protected function renderPage(
        string $title = '',
        string $content = ''
    ): string {
        // Debug output
        $debugHeading = $this->debug->debugHeading("View", "renderPage");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($title, "{$debugHeading} -- title");
        //$this->debug->debugVariable($content, "{$debugHeading} -- content");

        // REMOVE ONCE IRTFLayout HAS BEEN REFACTORED!
        define('CONTACT', '');

        // wrap the page contents in the site meta
        $htmlParts = [
            $this->irtfBuilder->myHeader(false, $title, false),
            $content,
            $this->irtfBuilder->myFooter(__FILE__, false),
        ];
        return $this->htmlBuilder->formatParts($htmlParts, $this->formatHtml);
    }

    /**
     * Generates the HTML block for displaying validation error messages.
     *
     * This method creates a table of error messages formatted as paragraphs,
     * with each error prefixed by its associated field label.
     *
     * @param array $dataErrors  An array of error messages keyed by field name.
     * @param array $fieldLabels An array mapping field names to human-readable labels.
     * @param int   $pad         Optional padding level for formatted output (default: 0).
     *
     * @return string The HTML block containing formatted error messages.
     */
    protected function getErrorsBlock(
        array $dataErrors = [],
        array $fieldLabels = [],
        int $pad = 0
    ): string {
        // Debug output
        $debugHeading = $this->debug->debugHeading("View", "getErrorsBlock");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($dataErrors, "{$debugHeading} -- dataErrors");
        $this->debug->debugVariable($fieldLabels, "{$debugHeading} -- fieldLabels");
        $this->debug->debugVariable($pad, "{$debugHeading} -- pad");

        $tablePad = $pad;
        $tableRowPad = $tablePad + 2;
        $paragraphPad = $tableRowPad + 2;

        $pAttr = ['align' => 'justify', 'class' => 'error-messages', 'color' => 'red'];
        $rowAttr = [];
        $tableAttr = ['width' => '100%', 'border' => '0', 'cellspacing' => '0', 'cellpadding' => '6'];

        $rows = [];
        foreach ($dataErrors as $field => $message) {
            // retrieve 'readable' label for error
            $displayField = $fieldLabels[$field] ?? $field;
            $escapedField = $this->htmlBuilder->escape($displayField);
            $escapedMessage = $this->htmlBuilder->escape(
                is_array($message)
                    ? implode(', ', $message)
                    : $message
            );
            $paragraph = $this->htmlBuilder->getParagraph(
                sprintf('<strong>%s:</strong> %s', $escapedField, $escapedMessage),
                $pAttr,
                $paragraphPad,
                true
            );
            $rows[] = $this->htmlBuilder->getTableRowFromArray(
                [$paragraph],
                false,
                [false],
                $rowAttr,
                $tableRowPad,
                true
            );
        }
        $tableHtml = $this->htmlBuilder->getTableFromRows(
            $rows,
            $tableAttr,
            $tablePad
        );
        $htmlParts = [
            '',
            '<!--  Errors  -->',
            '',
            '<center>',
            $tableHtml,
            '</center>',
            '',
        ];
        return $this->htmlBuilder->formatParts($htmlParts, $this->formatHtml);
    }

    /**
     * Generates the HTML block for displaying results messages.
     *
     * This method creates a table of results messages formatted as paragraphs.
     *
     * @param array $dataResults An array of result messages.
     * @param int   $pad         Optional padding level for formatted output (default: 0).
     *
     * @return string The HTML block containing formatted results messages.
     */
    protected function getResultsBlock(
        array $dataResults = [],
        int $pad = 0
    ): string {
        // Debug output
        $debugHeading = $this->debug->debugHeading("View", "getResultsBlock");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($dataResults, "{$debugHeading} -- dataResults");
        $this->debug->debugVariable($pad, "{$debugHeading} -- pad");

        $tablePad = $pad;
        $tableRowPad = $tablePad + 2;
        $paragraphPad = $tableRowPad + 2;

        $pAttr = ['align' => 'justify', 'class' => 'result-messages', 'color' => 'green'];
        $rowAttr = [];
        $tableAttr = ['width' => '100%', 'border' => '0', 'cellspacing' => '0', 'cellpadding' => '6'];

        // Generate the results block
        $rows = [];
        foreach ($dataResults as $message) {
            $escapedMessage = $this->htmlBuilder->escape(
                is_array($message)
                    ? implode(', ', $message)
                    : $message
            );
            $paragraph = $this->htmlBuilder->getParagraph(
                $escapedMessage,
                $pAttr,
                $paragraphPad,
                true
            );
            $rows[] = $this->htmlBuilder->getTableRowFromArray(
                [$paragraph],
                false,
                [false],
                $rowAttr,
                $tableRowPad,
                true
            );
        }
        $tableHtml = $this->htmlBuilder->getTableFromRows(
            $rows,
            $tableAttr,
            $tablePad
        );
        $htmlParts = [
            '',
            '<!--  Results  -->',
            '',
            '<center>',
            $tableHtml,
            '</center>',
            '',
        ];
        return $this->htmlBuilder->formatParts($htmlParts, $this->formatHtml);
    }

    /**
     * Generates the HTML for the main form contents.
     *
     * This method wraps the page content in form tags and applies necessary attributes,
     * formatting it according to the specified padding and layout rules.
     *
     * @param string $action   The form submission URL.
     * @param array  $dbData   Data arrays required to populate form options.
     * @param array  $formData Default data for form fields.
     * @param int    $pad      Optional padding level for formatted output (default: 0).
     *
     * @return string The formatted HTML for the form contents.
     */
    protected function getContentsForm(
        string $action = '',
        array $dbData = [],
        array $formData = [],
        int $pad = 0
    ): string {
        // Debug output
        $debugHeading = $this->debug->debugHeading("View", "getContentsForm");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($action, "{$debugHeading} -- action");
        $this->debug->debugVariable($dbData, "{$debugHeading} -- dbData");
        $this->debug->debugVariable($formData, "{$debugHeading} -- formData");
        $this->debug->debugVariable($pad, "{$debugHeading} -- pad");

        // Wrap form tags around the body content
        $htmlParts = [
            $this->htmlBuilder->getForm(
                $action,
                'post',
                $this->getPageContents($dbData, $formData, $pad),
                ['enctype' => 'multipart/form-data'],
                $pad,
                true
            ),
        ];
        return $this->htmlBuilder->formatParts($htmlParts, $this->formatHtml);
    }
}
