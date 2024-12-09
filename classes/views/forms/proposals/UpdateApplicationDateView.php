<?php

declare(strict_types=1);

namespace App\views\forms\proposals;

use App\core\common\Debug;
use App\views\forms\BaseFormView as BaseView;

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
     * @param bool  $formatHtml Whether to format the HTML output.
     * @param Debug $debug      Optional. Debugging utility instance.
     */
    public function __construct(
        bool $formatHtml = false,
        ?Debug $debug = null
    ) {
        // Use parent class' constructor
        parent::__construct($formatHtml ?? false, $debug);
        $debugHeading = $this->debug->debugHeading("View", "__construct");
        $this->debug->debug($debugHeading);
        $this->debug->log("{$debugHeading} -- Parent class is successfully constructed.");

        // Class initialisation complete
        $this->debug->log("{$debugHeading} -- View initialisation complete.");
    }

    public function getFieldLabels(): array
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("View", "getFieldLabels");
        $this->debug->debug($debugHeading);

        // Map internal field names to user-friendly labels
        return [];
    }

    public function renderForm1Page(
        string $title = '',
        string $action = ''
    ): string {
        // Debug output
        $this->debug->debug("UpdateApplicationDate View: renderForm1Page()");

        $instructions = 'Select the semester to edit proposals for.';
        $content = $this->compBuilder->buildSemesterChooserForm($action, $instructions, [], 0);
        return $this->renderPage($title, $content);
    }

    public function renderForm2Page(
        string $title = '',
        string $action = '',
        array $proposal = []
    ): string {
        // Debug output
        $this->debug->debug("UpdateApplicationDate View: renderForm2Page()");

        $instructions = 'Select the proposal for which to update the submission date. '
            . 'The unix timestamp value will be needed on the next screen.';
        $content = $this->compBuilder->buildSemesterProposalListForm($action, $instructions, $proposal, [], 0);
        return $this->renderPage($title, $content);
    }

    public function renderForm3Page(
        string $title = '',
        string $action = '',
        array $proposal = []
    ): string {
        // Debug output
        $this->debug->debug("UpdateApplicationDate View: renderForm3Page()");

        $instructions = 'Enter the new submission time for this proposal. Remember to use the unix timestamp. '
            . 'If you need to convert the timestamp, check out <a href="https://www.epochconverter.com">'
            . 'https://www.epochconverter.com</a>.';
        $timestampInput = $this->htmlBuilder->getUnixTimestampInput('t', $proposal['creationDate'], [], 0, false);
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
