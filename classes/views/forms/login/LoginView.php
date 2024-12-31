<?php

declare(strict_types=1);

namespace App\views\forms\login;

use App\exceptions\HtmlBuilderException;
use App\core\common\CustomDebug           as Debug;
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
     * @param Debug|null       $debug       Debug instance for logging and debugging. Defaults to a new Debug instance.
     * @param HtmlBuilder|null $htmlBuilder Instance for constructing HTML elements. Defaults to a new HtmlBuilder.
     * @param CompBuilder|null $compBuilder Instance for composite HTML elements. Defaults to a new CompBuilder.
     * @param IrtfBuilder|null $irtfBuilder Legacy layout builder for site meta. Defaults to a new IrtfBuilder.
     */
    public function __construct(
        ?bool $formatHtml = null,
        ?Debug $debug = null,
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
        $break = $this->compBuilder->buildFormSectionBreak(0);
        $htmlParts = [
            $this->buildPreamble($formData),
            $break,
            $this->buildInputFields($formData),
            $break,
            $this->buildButtons(),
            $break,
        ];

        return $this->htmlBuilder->formatParts($htmlParts, $this->formatHtml);
    }

    // Public methods: buildDefaultInstructions()

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

    // Private methods: buildPreamble(), buildInputFields(), buildButtons()

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
}
