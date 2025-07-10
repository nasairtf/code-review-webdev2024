<?php

declare(strict_types=1);

namespace App\views\forms;

use App\exceptions\HtmlBuilderException;
use App\core\common\AbstractDebug         as Debug;
use App\views\BaseView                    as BaseView;
use App\core\htmlbuilder\HtmlBuilder      as HtmlBuilder;
use App\core\htmlbuilder\CompositeBuilder as CompBuilder;
use App\legacy\IRTFLayout                 as IrtfBuilder;

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

abstract class BaseFormView extends BaseView
{
    /**
     * Constructor for the BaseFormView class.
     *
     * Initializes debugging, formatting preferences, and the necessary builder instances. Defaults are provided
     * if no specific instances or configurations are passed.
     *
     * @param bool|null        $formatHtml  Enable formatted HTML output. Defaults to false if not provided.
     * @param Debug|null       $debug       Debug instance for logging and debugging. Defaults to a new Debug.
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
        $debugHeading = $this->debug->debugHeading("BaseFormView", "__construct");
        $this->debug->debug($debugHeading);
        $this->debug->debug("{$debugHeading} -- Parent class is successfully constructed.");

        // Constructor completed
        $this->debug->debug("{$debugHeading} -- Parent View initialisation complete.");
    }

    // Abstract methods: getFieldLabels(), getPageContents()

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
     * and padding is set to 0. The BaseFormView parent method getContentsForm() passes the
     * contents to renderFormPage(), etc., for rendering.
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

    // Public methods: renderFormPage(), renderFormWithErrors()

    /**
     * Renders the main form page.
     *
     * This method generates a form page with the provided title and form data.
     * It wraps the form in the site's standard layout.
     *
     * @param string $title       The title of the form page.
     * @param string $action      The form submission URL.
     * @param array  $dbData      Data arrays required to populate form options.
     * @param array  $formData    Default data for form fields.
     * @param bool   $methodPost  Whether the form uses 'method="post"' (default: true).
     * @param bool   $targetBlank Whether the form includes 'target="_blank"' (default: false).
     * @param int    $pad         Optional padding level for formatted output (default: 0).
     *
     * @return string The complete HTML of the form page.
     */
    public function renderFormPage(
        string $title = '',
        string $action = '',
        array $dbData = [],
        array $formData = [],
        bool $methodPost = true,
        bool $targetBlank = false,
        int $pad = 0
    ): string {
        // Debug output
        $debugHeading = $this->debug->debugHeading("BaseFormView", "renderFormPage");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($title, "{$debugHeading} -- title");
        $this->debug->debugVariable($action, "{$debugHeading} -- action");
        $this->debug->debugVariable($dbData, "{$debugHeading} -- dbData");
        $this->debug->debugVariable($formData, "{$debugHeading} -- formData");
        $this->debug->debugVariable($methodPost, "{$debugHeading} -- methodPost");
        $this->debug->debugVariable($targetBlank, "{$debugHeading} -- targetBlank");
        $this->debug->debugVariable($pad, "{$debugHeading} -- pad");

        // Wrap form tags around the body content
        return $this->renderPage(
            $title,
            $this->getContentsForm(
                $action,
                $dbData,
                $formData,
                $methodPost,
                $targetBlank,
                $pad
            )
        );
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
     * @param bool   $methodPost  Whether the form uses 'method="post"' (default: true).
     * @param bool   $targetBlank Whether the form includes 'target="_blank"' (default: false).
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
        bool $methodPost = true,
        bool $targetBlank = false,
        int $pad = 0
    ): string {
        // Debug output
        $debugHeading = $this->debug->debugHeading("BaseFormView", "renderFormWithErrors");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($title, "{$debugHeading} -- title");
        $this->debug->debugVariable($action, "{$debugHeading} -- action");
        $this->debug->debugVariable($dbData, "{$debugHeading} -- dbData");
        $this->debug->debugVariable($formData, "{$debugHeading} -- formData");
        $this->debug->debugVariable($dataErrors, "{$debugHeading} -- dataErrors");
        $this->debug->debugVariable($fieldLabels, "{$debugHeading} -- fieldLabels");
        $this->debug->debugVariable($methodPost, "{$debugHeading} -- methodPost");
        $this->debug->debugVariable($targetBlank, "{$debugHeading} -- targetBlank");
        $this->debug->debugVariable($pad, "{$debugHeading} -- pad");

        // Render the errors section and the form
        return $this->renderPage(
            $title,
            $this->htmlBuilder->formatParts(
                [
                    $this->getErrorsBlock(
                        $dataErrors,
                        $fieldLabels,
                        $pad
                    ),
                    $this->getContentsForm(
                        $action,
                        $dbData,
                        $formData,
                        $methodPost,
                        $targetBlank,
                        $pad
                    ),
                ],
                $this->formatHtml
            )
        );
    }

    // Helper methods: getErrorsBlock(), getContentsForm()

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
        $debugHeading = $this->debug->debugHeading("BaseFormView", "getErrorsBlock");
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

        // Wrap the table in additional markup for centering and styling
        return $this->compBuilder->buildPageSection(
            $tableHtml,
            'Errors',
            false,
            $pad
        );
    }

    /**
     * Generates the HTML for the main form contents.
     *
     * This method wraps the page content in form tags and applies necessary attributes,
     * formatting it according to the specified padding and layout rules.
     *
     * @param string $action      The form submission URL.
     * @param array  $dbData      Data arrays required to populate form options.
     * @param array  $formData    Default data for form fields.
     * @param bool   $methodPost  Whether the form uses 'method="post"' (default: true).
     * @param bool   $targetBlank Whether the form includes 'target="_blank"' (default: false).
     * @param int    $pad         Optional padding level for formatted output (default: 0).
     *
     * @return string The formatted HTML for the form contents.
     */
    protected function getContentsForm(
        string $action = '',
        array $dbData = [],
        array $formData = [],
        bool $methodPost = true,
        bool $targetBlank = false,
        int $pad = 0
    ): string {
        // Debug output
        $debugHeading = $this->debug->debugHeading("BaseFormView", "getContentsForm");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($action, "{$debugHeading} -- action");
        $this->debug->debugVariable($dbData, "{$debugHeading} -- dbData");
        $this->debug->debugVariable($formData, "{$debugHeading} -- formData");
        $this->debug->debugVariable($methodPost, "{$debugHeading} -- methodPost");
        $this->debug->debugVariable($targetBlank, "{$debugHeading} -- targetBlank");
        $this->debug->debugVariable($pad, "{$debugHeading} -- pad");

        // Wrap form tags around the body content
        $formAttr = array_merge(
            ['enctype' => 'multipart/form-data'],
            $targetBlank ? ['target' => '_blank'] : []
        );
        $method = $methodPost ? 'post' : 'get';
        $htmlParts = [
            $this->htmlBuilder->getForm(
                $action,
                $method,
                $this->getPageContents($dbData, $formData, $pad),
                $formAttr,
                $pad,
                true
            ),
        ];
        return $this->htmlBuilder->formatParts(
            $htmlParts,
            $this->formatHtml
        );
    }
}
