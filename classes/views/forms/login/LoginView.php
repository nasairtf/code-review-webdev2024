<?php

declare(strict_types=1);

namespace App\views\forms\login;

use App\core\common\CustomDebug;
use App\exceptions\HtmlBuilderException;
use App\views\forms\BaseFormView          as BaseView;
use App\core\htmlbuilder\HtmlBuilder      as HtmlBuilder;
use App\core\htmlbuilder\CompositeBuilder as CompBuilder;
use App\legacy\IRTFLayout                 as IrtfBuilder;

/**
 * View for rendering the Login form.
 *
 * This class is responsible for generating the HTML structure of the login form,
 * including any default instructions and formatting options. It utilizes the shared
 * functionality in the BaseFormView.
 *
 * @category Views
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class LoginView extends BaseView
{
    /**
     * Constructor for initializing the LoginView.
     *
     * Sets up the necessary builders and layout utilities, and determines
     * whether HTML output should be formatted.
     *
     * @param bool|null        $formatHtml  Enable formatted HTML output. Defaults to false if not provided.
     * @param CustomDebug|null $debug       Debug instance for logging and debugging. Defaults to a new Debug instance.
     * @param HtmlBuilder|null $htmlBuilder Instance for constructing HTML elements. Defaults to a new HtmlBuilder.
     * @param CompBuilder|null $compBuilder Instance for composite HTML elements. Defaults to a new CompBuilder.
     * @param IrtfBuilder|null $irtfBuilder Legacy layout builder for site meta. Defaults to a new IrtfBuilder.
     */
    public function __construct(
        ?bool $formatHtml = null,
        ?CustomDebug $debug = null,
        ?HtmlBuilder $htmlBuilder = null, // Dependency injection to simplify unit testing
        ?CompBuilder $compBuilder = null, // Dependency injection to simplify unit testing
        ?IrtfBuilder $irtfBuilder = null  // Dependency injection to simplify unit testing
    ) {
        // Use parent class' constructor
        parent::__construct($formatHtml, $debug, $htmlBuilder, $compBuilder, $irtfBuilder);
        $debugHeading = $this->debug->debugHeading("View", "__construct");
        $this->debug->debug($debugHeading);
        $this->debug->debug("{$debugHeading} -- Parent class is successfully constructed.");

        // Class initialisation complete
        $this->debug->debug("{$debugHeading} -- View initialisation complete.");
    }

    // Abstract methods: getFieldLabels(), getPageContents()

    /**
     * Provides field labels for the Login form.
     *
     * Maps internal field names to user-friendly labels.
     *
     * @return array An associative array mapping field names to labels.
     */
    public function getFieldLabels(): array
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("View", "getFieldLabels");
        $this->debug->debug($debugHeading);

        // Map internal field names to user-friendly labels
        return [
            'program' => 'Program Number',
            'session' => 'Session Code',
        ];
    }

    /**
     * Generates the main page content for the login form.
     *
     * This method defines the specific HTML structure for the login form,
     * using the provided database and form data. The BaseFormView parent method
     * getContentsForm() passes the contents to renderFormPage(), etc., for rendering.
     *
     * @param array $dbData   Data arrays required to populate form options. Defaults to an empty array.
     * @param array $formData Default data for form fields. Defaults to an empty array.
     * @param int   $pad      Optional padding level for formatted output. Defaults to 0.
     *
     * @return string The HTML content for the form page.
     */
    protected function getPageContents(
        array $dbData = [],
        array $formData = [],
        int $pad = 0
    ): string {
        // Debug output
        $debugHeading = $this->debug->debugHeading("View", "getPageContents");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($dbData, "{$debugHeading} -- dbData");
        $this->debug->debugVariable($formData, "{$debugHeading} -- formData");
        $this->debug->debugVariable($pad, "{$debugHeading} -- pad");

        // Build the page contents
        $htmlParts = [
            $this->buildPreamble($formData),
            $this->buildBreak(),
            $this->buildInputFields($formData),
            $this->buildBreak(),
            $this->buildButtons(),
            $this->buildBreak(),
        ];

        return $this->htmlBuilder->formatParts($htmlParts, $this->formatHtml);
    }

    // Public methods

    /**
     * Generates the default instructions for the login form.
     *
     * @return string HTML-formatted paragraph containing default instructions.
     */
    public function buildDefaultInstructions(): string
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("View", "buildDefaultInstructions");
        $this->debug->debug($debugHeading);

        // Prep the section contents
        $instructions = 'Please log in using your program number and session code.';
        return $this->htmlBuilder->getParagraph($instructions, ['align' => 'justify'], 0);
    }

    // Private methods

    /**
     * Builds a horizontal line break section for the form.
     *
     * This method returns a formatted HTML line element, which serves as a visual
     * separator within the form.
     *
     * @return string The HTML for the section break, formatted as a horizontal line.
     */
    private function buildBreak(): string
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("View", "buildBreak");
        $this->debug->debug($debugHeading);

        // Build the section contents
        return $this->compBuilder->buildFormSectionBreak(0);
    }

    /**
     * Builds the preamble section for the login form.
     *
     * @param array $formData The default form data for login fields.
     *
     * @return string The HTML for the preamble.
     */
    private function buildPreamble(array $formData): string
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("View", "buildPreamble");
        $this->debug->debug($debugHeading);

        // Prep the section contents
        $instructions = $formData['instructions'] ?? $this->buildDefaultInstructions();
        $rowAttr = [];
        $tableAttr = ['border' => '0', 'cellspacing' => '0', 'cellpadding' => '6'];

        // Build the section contents
        return $this->compBuilder->buildPreambleFormSection(
            $instructions,
            $rowAttr,
            $tableAttr,
            0
        );
    }

    /**
     * Builds the input fields section for the login form.
     *
     * @param array $formData The default form data for login fields.
     *
     * @return string The HTML for the input fields.
     */
    private function buildInputFields(array $formData): string
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("View", "buildInputFields");
        $this->debug->debug($debugHeading);

        // Prep the section contents
        $fields = $this->getFieldLabels();
        $inputAttr = [];
        //$cellAttr = ['style' => 'width: 150px;', 'align' => 'left'];
        $rowAttr = [];
        $tableAttr = ['border' => '0', 'cellspacing' => '0', 'cellpadding' => '6'];
        $inputFields = [];

        foreach ($fields as $name => $label) {
            $inputFields[] = [
                'label' => "{$label}:",
                'name' => $name,
                'value' => $formData[$name] ?? '',
                'type' => 'text',
                'attr' => $inputAttr
            ];
        }

        // Build the section contents
        return $this->compBuilder->buildInputFieldsFormSection(
            $inputFields,
            $rowAttr,
            $tableAttr,
            0
        );
    }

    /**
     * Builds the button section for the login form.
     *
     * @return string The HTML for the form buttons.
     */
    private function buildButtons(): string
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("View", "buildButtons");
        $this->debug->debug($debugHeading);

        // Prep the section contents
        $buttonAttr = ['style' => 'width: 135px;'];
        $rowAttr = [];
        $tableAttr = ['border' => '0', 'cellspacing' => '4'];

        $buttons = [
            $this->htmlBuilder->getResetButton('Clear Form', $buttonAttr),
            $this->htmlBuilder->getSubmitButton('login', 'Log in', $buttonAttr),
        ];

        // Build the section contents
        return $this->compBuilder->buildButtonsFormSection(
            $buttons,
            $rowAttr,
            $tableAttr,
            0
        );
    }
























    /**
     * Builds and returns the HTML for an embeddable login form.
     *
     * This method generates the login form with specified action, data, and instructions.
     * Intended for cases where the login form needs to be embedded in other forms or views.
     * Calls an internal method to handle the main form construction, allowing future customization.
     *
     * @param string $action       The form submission URL.
     * @param array  $data         Default form data for the login fields.
     * @param string $instructions Instructions or guidance text to display above the form.
     *
     * @return string              The complete HTML for the embeddable login form.
     *//*
    public function buildEmbeddableLoginForm(
        string $action,
        array $data,
        string $instructions
    ): string {
        // Debug output
        $this->debug->debug("Login View: buildEmbeddableLoginForm()");

        return $this->buildLoginForm($action, $data, $instructions);
    }*/

    /**
     * Renders the complete login form page with layout.
     *
     * This method builds the login form content with the provided title,
     * form action URL, default form data, and instructions, and then wraps
     * it within the page's standard header and footer.
     *
     * @param string $title        The title of the login form page.
     * @param string $formAction   The form submission URL.
     * @param array  $formData     The default form data for the login fields.
     * @param string $instructions Instructions or guidance text displayed above the form.
     *
     * @return string              The complete HTML for the login form page.
     *//*
    public function renderLoginFormPage(
        $title,
        $formAction,
        $formData,
        $instructions
    ): string {
        // Debug output
        $this->debug->debug("Login View: renderLoginFormPage()");

        $content = $this->buildLoginForm($formAction, $formData, $instructions);
        return $this->renderPage($title, $content);
    }*/

    // Private helper methods

    /**
     * Renders a complete HTML page with a specified title and content.
     *
     * This method uses the IRTF layout to generate a header and footer around the
     * given content, formatting the full HTML structure based on the `$formatHtml` property.
     *
     * @param string $title   [optional] The title of the page, displayed in the header.
     * @param string $content [optional] The main content of the page.
     *
     * @return string The fully constructed and formatted HTML page.
     *//*
    private function renderPage(
        string $title = '',
        string $content = ''
    ): string {
        // Debug output
        $this->debug->debug("Login View: renderPage()");

        $htmlParts = [
            $this->irtfBuilder->myHeader(false, $title, false),
            $content,
            $this->irtfBuilder->myFooter(__FILE__, false),
        ];
        return $this->htmlBuilder->formatParts($htmlParts, $this->formatHtml);
    }*/

    /**
     * Constructs the login form with the specified action, form data, and instructions.
     *
     * This method wraps the form's main content in form tags, setting the action URL,
     * method, and other attributes.
     *
     * @param string $action       The form submission URL.
     * @param array  $formData     The default form data values for login fields.
     * @param string $instructions Instructions or guidance text for display.
     *
     * @return string The complete HTML for the login form.
     *//*
    private function buildLoginForm(
        string $action,
        array $formData,
        string $instructions
    ): string {
        // Debug output
        $this->debug->debug("Login View: buildLoginForm()");
        $this->debug->debugVariable($formData, "formData");

        // build the form contents
        $content = $this->buildLoginFormContents($formData, $instructions);
        $method = 'post';
        $formAttr = ['enctype' => 'multipart/form-data'];
        // wrap the contents in form tags
        return $this->htmlBuilder->formatParts(
            [$this->htmlBuilder->getForm($action, $method, $content, $formAttr, 0, true)],
            $this->formatHtml
        );
    }*/

    /**
     * Builds the complete login form content with the input fields and instructions.
     *
     * This method assembles sections for the form instructions, login input fields, and buttons,
     * combining them into the full form content.
     *
     * @param array  $formData     The default form data for login fields.
     * @param string $instructions Instructions or guidance text to be displayed.
     *
     * @return string The HTML content of the login form.
     *//*
    private function buildLoginFormContents(
        array $formData,
        string $instructions
    ): string {
        // Debug output
        $debugHeading = $this->debug->debugHeading("View", "buildLoginFormContents");
        $this->debug->debug($debugHeading);

        // Debug output
        $this->debug->debug("Login View: buildLoginFormContents()");
        $this->debug->debugVariable($formData, "formData");

        $htmlParts = [
            $this->buildPreambleSection($instructions),
            $this->buildSectionBreak(),
            $this->buildErrorMessage($formData),
            $this->buildInputFields($formData),
            $this->buildSectionBreak(),
            $this->buildButtons(),
            $this->buildSectionBreak(),
        ];
        return $this->htmlBuilder->formatParts($htmlParts, $this->formatHtml);
    }*/

    /**
     * Builds a horizontal line break section for the form.
     *
     * This method returns a formatted HTML line element, which serves as a visual
     * separator within the form.
     *
     * @return string The HTML for the section break, formatted as a horizontal line.
     *//*
    private function buildSectionBreak(): string
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("View", "buildSectionBreak");
        $this->debug->debug($debugHeading);

        // Debug output
        $this->debug->debug("Login View: buildSectionBreak()");

        $htmlParts = [
            '',
            $this->htmlBuilder->getLine([], 0),
            '',
        ];
        return $this->htmlBuilder->formatParts($htmlParts, $this->formatHtml);
    }*/

    /**
     * Builds the preamble section for the login form, displaying the provided instructions.
     *
     * @param string $instructions Instructions or guidance text for display.
     *
     * @return string The HTML for the preamble section.
     *//*
    private function buildPreambleSection(string $instructions): string
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("View", "buildPreambleSection");
        $this->debug->debug($debugHeading);

        // Debug output
        $this->debug->debug("Login View: buildPreambleSection()");

        $tablePad = 0;
        $tableRowPad = $tablePad + 2;
        $rowAttr = [];
        $tableAttr = ['border' => '0', 'cellspacing' => '0', 'cellpadding' => '6'];
        $tableHtml = $this->htmlBuilder->getTableFromRows(
            [
                $this->htmlBuilder->getTableRowFromArray(
                    [$instructions],
                    false,
                    [false],
                    $rowAttr,
                    $tableRowPad,
                    true
                )
            ],
            $tableAttr,
            $tablePad
        );

        $htmlParts = [
            '',
            '<!--  Preamble  -->',
            '',
            '<center>',
            $tableHtml,
            '</center>',
            '',
        ];
        return $this->htmlBuilder->formatParts($htmlParts, $this->formatHtml);
    }*/

    /**
     * Builds the error message section for the login form if an error exists.
     *
     * This method checks for an error message within the `$formData` array.
     * If an error message is found, it creates a formatted HTML section displaying
     * the message in a centered table with specific styling. Otherwise, it returns
     * an empty string.
     *
     * @param array $formData The form data array, which may contain an 'error' key
     *                        with a message to display.
     *
     * @return string         The HTML for the error message section, or an empty string
     *                        if no error exists.
     *//*
    private function buildErrorMessage(array $formData): string
    {
        // Debug output
        $this->debug->debug("Login View: buildErrorMessage()");
        $this->debug->debugVariable($formData, "formData");

        // No error to display
        if (empty($formData['error'])) {
            return '';
        }
        // Style attributes and wrapper for error message
        $tablePad = 0;
        $tableRowPad = $tablePad + 2;
        $errorAttr = ['class' => 'error-message', 'style' => 'color: red; font-weight: bold;'];
        $rowAttr = [];
        $tableAttr = ['border' => '0', 'cellspacing' => '0', 'cellpadding' => '6'];
        $tableHtml = $this->htmlBuilder->getTableFromRows(
            [
                $this->htmlBuilder->getTableRowFromArray(
                    [$this->htmlBuilder->getParagraph($formData['error'], $errorAttr, 0)],
                    false,
                    [false],
                    $rowAttr,
                    $tableRowPad,
                    true
                )
            ],
            $tableAttr,
            $tablePad
        );

        $htmlParts = [
            '',
            '<!--  Error Message  -->',
            '',
            '<center>',
            $tableHtml,
            '</center>',
            '',
            $this->htmlBuilder->getLine([], 0),
            '',
        ];

        return $this->htmlBuilder->formatParts($htmlParts, $this->formatHtml);
    }*/

    /**
     * Builds the input fields section for the login form.
     *
     * This section includes fields for program login information, as well as
     * necessary labels and structure.
     *
     * @param array $formData The form data to prefill into the fields.
     *
     * @return string The HTML for the input fields section.
     *//*
    private function buildInputFields(array $formData): string
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("View", "buildInputFields");
        $this->debug->debug($debugHeading);

        // Debug output
        $this->debug->debug("Login View: buildInputFields()");
        $this->debug->debugVariable($formData, "formData");

        $tablePad = 0;
        $tableRowPad = $tablePad + 2;
        $inputAttr = [];
        $cellAttr = ['style' => 'width: 150px;', 'align' => 'left'];
        $rowAttr = [];
        $tableAttr = ['border' => '0', 'cellspacing' => '0', 'cellpadding' => '6'];
        $programLabel = 'Program Number:';
        $sessionLabel = 'Session Code:';
        $programIntput = $this->htmlBuilder->getTextInput('program', $formData['program'], 10, $inputAttr, 0, false);
        $sessionIntput = $this->htmlBuilder->getTextInput('session', $formData['session'], 10, $inputAttr, 0, false);
        $programCells = ['&nbsp;', $programLabel, '&nbsp;', $programIntput, '&nbsp;'];
        $sessionCells = ['&nbsp;', $sessionLabel, '&nbsp;', $sessionIntput, '&nbsp;'];
        $tableHtml = $this->htmlBuilder->getTableFromRows(
            [
                $this->htmlBuilder->getTableRowFromArray(
                    $programCells,
                    false,
                    [true, true, true, true, true],
                    $rowAttr,
                    $tableRowPad,
                    true
                ),
                $this->htmlBuilder->getTableRowFromArray(
                    $sessionCells,
                    false,
                    [true, true, true, true, true],
                    $rowAttr,
                    $tableRowPad,
                    true
                ),
            ],
            $tableAttr,
            $tablePad
        );
        $htmlParts = [
            '',
            '<!--  Program Login Info  -->',
            '',
            '<center>',
            $tableHtml,
            '</center>',
            '',
        ];
        return $this->htmlBuilder->formatParts($htmlParts, $this->formatHtml);
    }*/

    /**
     * Builds the button section for the login form.
     *
     * This section includes reset and submit buttons, and centers them on the page.
     *
     * @return string The HTML for the buttons section.
     *//*
    private function buildButtons(): string
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("View", "buildButtons");
        $this->debug->debug($debugHeading);

        // Debug output
        $this->debug->debug("Login View: buildButtons()");

        $tablePad = 0;
        $tableRowPad = $tablePad + 2;
        $buttonAttr = ['style' => 'width: 135px;'];
        $rowAttr = [];
        $tableAttr = ['border' => '0', 'cellspacing' => '4'];
        $buttons = [
            $this->htmlBuilder->getResetButton('Clear Form', $buttonAttr),
            $this->htmlBuilder->getSubmitButton('login', 'Log in', $buttonAttr),
        ];
        $tableHtml = $this->htmlBuilder->getTableFromRows(
            [
                $this->htmlBuilder->getTableRowFromArray(
                    $buttons,
                    false,
                    [true, true],
                    $rowAttr,
                    $tableRowPad,
                    true
                )
            ],
            $tableAttr,
            $tablePad
        );
        $htmlParts = [
            '',
            '<!--  Buttons  -->',
            '',
            '<center>',
            $tableHtml,
            '</center>',
            '',
        ];
        return $this->htmlBuilder->formatParts($htmlParts, $this->formatHtml);
    }*/
}
