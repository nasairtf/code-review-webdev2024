<?php

namespace App\views\forms\proposals;

use App\core\common\Debug;

use App\core\htmlbuilder\HtmlBuilder as HtmlBuilder;
use App\core\htmlbuilder\CompositeBuilder as CompBuilder;
use App\legacy\IRTFLayout as IrtfBuilder;

/**
 * View for rendering the Update Application Date form.
 *
 * @category Views
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class UpdateApplicationDateView
{
    private $formatHtml;
    private $debug;
    private $htmlBuilder;
    private $compBuilder;
    private $irtfBuilder;

    public function __construct(
        bool $formatHtml = false,
        ?Debug $debug = null,
        ?HtmlBuilder $htmlBuilder = null,
        ?CompBuilder $compBuilder = null,
        ?IrtfBuilder $irtfBuilder = null
    ) {
        $this->formatHtml = $formatHtml; // set the global html formatting
        $this->debug = $debug ?? new Debug('default', false, 0);
        $this->htmlBuilder = $htmlBuilder ?? new HtmlBuilder($this->formatHtml);
        $this->compBuilder = $compBuilder ?? new CompBuilder($this->formatHtml, $this->htmlBuilder);
        $this->irtfBuilder = $irtfBuilder ?? new IrtfBuilder();
    }

    public function renderForm1Page(
        string $title = '',
        string $action = ''
    ): string {
        // Debug output
        $this->debug->debug("UpdateApplicationDate View: renderForm1Page()");

        $instructions = "Select the semester to edit proposals for.";
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

        $instructions = "Select the proposal for which to update the submission date. The unix timestamp value will be needed on the next screen.";
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

        $instructions = "Enter the new submission time for this proposal. Remember to use the unix timestamp. If you need to convert the timestamp, check out <a href='https://www.epochconverter.com'>https://www.epochconverter.com</a>.";
        $timestampInput = $this->htmlBuilder->getUnixTimestampInput('t', $proposal['creationDate'], [], 0, false);
        $content = $this->compBuilder->buildProposalUpdateConfirmationForm($action, $instructions, $proposal, $timestampInput, [], 0);
        return $this->renderPage($title, $content);
    }

    public function renderResultsPage(
        string $title = '',
        string $message = ''
    ): string {
        // Debug output
        $this->debug->debug("UpdateApplicationDate View: renderResultsPage()");

        $content = $this->compBuilder->buildResultsPage($message, [], 0);
        return $this->renderPage($title, $content);
    }

    public function renderErrorPage(
        string $title = '',
        string $message = ''
    ): string {
        // Debug output
        $this->debug->debug("UpdateApplicationDate View: renderErrorPage()");

        $content = $this->compBuilder->buildErrorPage($message, [], 0);
        return $this->renderPage($title, $content);
    }

    /**
     * Form helper method that builds the common parts for the form
     */

    private function renderPage(
        string $title = '',
        string $content = ''
    ): string {
        // Debug output
        $this->debug->debug("UpdateApplicationDate View: renderPage()");

        $htmlParts = [];
        $htmlParts[] = $this->irtfBuilder->myHeader(false, $title, false);
        $htmlParts[] = $content;
        $htmlParts[] = $this->irtfBuilder->myFooter(__FILE__, false);
        return $this->htmlBuilder->formatParts($htmlParts, $this->formatHtml);
    }
}
