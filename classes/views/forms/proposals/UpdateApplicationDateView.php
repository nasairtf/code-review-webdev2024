<?php

declare(strict_types=1);

namespace App\views\forms\proposals;

use App\exceptions\HtmlBuilderException;
use App\core\common\CustomDebug           as Debug;
use App\views\forms\BaseFormView          as BaseView;
use App\core\htmlbuilder\HtmlBuilder      as HtmlBuilder;
use App\core\htmlbuilder\CompositeBuilder as CompBuilder;
use App\legacy\IRTFLayout                 as IrtfBuilder;

/**
 * View for rendering the Update Application Date form.
 *
 * @category Views
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class UpdateApplicationDateView extends BaseView
{
    /**
     * Initializes the UpdateApplicationDateView with core builders and configurations.
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
        return [];
    }

    /**
     * Generates the main page content for the update form.
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
        return '';
    }

    public function renderForm1Page(
        string $title = '',
        string $action = ''
    ): string {
        // Debug output
        $debugHeading = $this->debug->debugHeading("View", "renderForm1Page");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($title, "{$debugHeading} -- title");
        $this->debug->debugVariable($action, "{$debugHeading} -- action");

        // Generate the main page content for the form
        $instructions = 'Select the semester to edit proposals for.';
        $content = $this->compBuilder->buildSemesterChooserForm(
            $action,
            $instructions,
            [],
            0
        );
        return $this->renderPage($title, $content);
    }

    public function renderForm2Page(
        string $title = '',
        string $action = '',
        array $proposal = []
    ): string {
        // Debug output
        $debugHeading = $this->debug->debugHeading("View", "renderForm2Page");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($title, "{$debugHeading} -- title");
        $this->debug->debugVariable($action, "{$debugHeading} -- action");
        $this->debug->debugVariable($proposal, "{$debugHeading} -- proposal");

        // Generate the main page content for the form
        $instructions = 'Select the proposal for which to update the submission date. '
            . 'The unix timestamp value will be needed on the next screen.';
        $content = $this->compBuilder->buildSemesterProposalListForm(
            $action,
            $instructions,
            $proposal,
            [],
            0
        );
        return $this->renderPage($title, $content);
    }

    public function renderForm3Page(
        string $title = '',
        string $action = '',
        array $proposal = []
    ): string {
        // Debug output
        $debugHeading = $this->debug->debugHeading("View", "renderForm3Page");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($title, "{$debugHeading} -- title");
        $this->debug->debugVariable($action, "{$debugHeading} -- action");
        $this->debug->debugVariable($proposal, "{$debugHeading} -- proposal");

        // Generate the main page content for the form
        $instructions = 'Enter the new submission time for this proposal. Remember to use the unix timestamp. '
            . 'If you need to convert the timestamp, check out <a href="https://www.epochconverter.com">'
            . 'https://www.epochconverter.com</a>.';
        $timestampInput = $this->htmlBuilder->getUnixTimestampInput(
            't',
            (string) $proposal['creationDate'],
            [],
            0,
            false
        );
        $content = $this->compBuilder->buildProposalUpdateConfirmationForm(
            $action,
            $instructions,
            $proposal,
            $timestampInput,
            [],
            0
        );
        return $this->renderPage($title, $content);
    }
}
