<?php

namespace App\core\htmlbuilder;

/**
 * /home/webdev2024/classes/core/htmlbuilder/LayoutBuilder.php
 *
 * A utility class responsible for constructing HTML layouts with configurable formatting options.
 * This class provides methods to generate HTML layouts, including forms for results, errors,
 * semester selections, and proposal listings.
 *
 * @category Utilities
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class LayoutBuilder
{
    /**
     * Here are a list of element pad variables to use in each layout function to
     * ensure flexibility and consistency of padding values for the given form.
     * The variables are listed in alphabetical order at the very top of the methods.
     *
     * $buttonPad = $pad + 0;
     * $formPad = $pad + 0;
     * $inputPad = $pad + 0;
     * $linePad = $pad + 0;
     * $pulldownPad = $pad + 0;
     * $tableCellPad = $pad + 0;
     * $tablePad = $pad + 0;
     * $tableRowPad = $pad + 0;
     */

    /**
     * Whether to format the HTML output (indent and add line breaks).
     *
     * @var bool
     */
    private $formatOutput;

    /**
     * Various builder objects for generating HTML components.
     *
     * @var HtmlBuilder
     * @var FormElementsBuilder
     * @var TableLayoutBuilder
     */
    private $htmlBuilder;
    private $elemBuilder;
    private $tableBuilder;

    /**
     * Constructor to initialize the layout builder with HTML, form element, and table builders.
     *
     * @param bool                 $formatOutput     If true, output will be formatted with indentation.
     * @param HtmlBuilder          $htmlBuilder      [optional] Instance of HtmlBuilder. Defaults to a new instance.
     * @param FormElementsBuilder  $elemBuilder  [optional] Instance of FormElementsBuilder. Defaults to a new instance.
     * @param TableLayoutBuilder   $tableBuilder     [optional] Instance of TableLayoutBuilder. Defaults to a new instance.
     */
    public function __construct(
        bool $formatOutput = false,
        ?HtmlBuilder $htmlBuilder = null,
        ?FormElementsBuilder $elemBuilder = null,
        ?TableLayoutBuilder $tableBuilder = null
    ) {
        $this->formatOutput = $formatOutput;
        $this->htmlBuilder = $htmlBuilder ?? new HtmlBuilder($formatOutput);
        $this->elemBuilder = $elemBuilder ?? new FormElementsBuilder($formatOutput, $this->htmlBuilder);
        $this->tableBuilder = $tableBuilder ?? new TableLayoutBuilder($formatOutput, $this->htmlBuilder, $this->elemBuilder);
    }

    /**
     * Generates a results page with a message, using a styled HTML table.
     *
     * @param string $resultsMessage The message displayed on the results page.
     * @param array  $attributes     [optional] Additional attributes for the <table> element. Default is an empty array.
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     *
     * @return string HTML for the results page.
     */
    public function buildResultsPage(
        string $resultsMessage,
        array $attributes = [],
        int $pad = 0
    ): string {
        return $this->tableBuilder->buildMessagePageTable($resultsMessage, true, $attributes, $pad);
    }

    public function buildResultsBlockPage(
        string $resultsMessage,
        array $attributes = [],
        int $pad = 0
    ): string {
        return $this->tableBuilder->buildMessagesPageTable($resultsMessage, true, $attributes, $pad);
    }

    /**
     * Generates an error page with a message, using a styled HTML table.
     *
     * @param string $errorMessage   The message displayed on the error page.
     * @param array  $attributes     [optional] Additional attributes for the <table> element. Default is an empty array.
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     *
     * @return string HTML for the error page.
     */
    public function buildErrorPage(
        string $errorMessage,
        array $attributes = [],
        int $pad = 0
    ): string {
        return $this->tableBuilder->buildMessagePageTable($errorMessage, false, $attributes, $pad);
    }

    /**
     * Constructs a semester chooser form with instructions and pulldown fields for year and semester selection.
     * The form also includes reset and submit buttons, wrapped in a styled HTML table.
     *
     * @param string $action        The form's action URL.
     * @param string $instructions  Instructions displayed at the top of the form.
     * @param array  $attributes    [optional] Additional attributes for the <table> element. Default is an empty array.
     * @param int    $pad           [optional] Indentation level for formatted output. Default is 0.
     *
     * @return string HTML for the semester chooser form.
     */
    public function buildSemesterChooserForm(
        string $action,
        string $instructions,
        array $attributes = [],
        int $pad = 0
    ): string {
        $html = $this->tableBuilder->buildSemesterChooserTable($instructions, $attributes, $pad);
        return $this->htmlBuilder->getForm(
            $action,
            'get',
            $html,
            ['enctype' => 'multipart/form-data', 'target' => '_blank'],
            $pad,
            true
        );
    }

    /**
     * Constructs a form that lists semester programs based on provided proposal data.
     * The form includes instructions and a styled HTML table for displaying proposals.
     *
     * @param string $action        The form's action URL.
     * @param string $instructions  Instructions displayed at the top of the form.
     * @param array  $proposals     Array of proposal data to display in the table.
     * @param array  $attributes    [optional] Additional attributes for the <table> element. Default is an empty array.
     * @param int    $pad           [optional] Indentation level for formatted output. Default is 0.
     *
     * @return string HTML for the semester program list form.
     */
    public function buildSemesterProposalListForm(
        string $action,
        string $instructions,
        array $proposals,
        array $attributes = [],
        int $pad = 0
    ): string {
        return $this->tableBuilder->buildSemesterProposalListTable($action, $instructions, $proposals, $attributes, $pad);
    }

    /**
     * Constructs a proposal confirmation form with a customizable input field for proposal details.
     *
     * @param string $action        The form's action URL.
     * @param string $instructions  Instructions displayed at the top of the form.
     * @param array  $proposal      Proposal data (id, code, program number, investigator).
     * @param string $inputField    Customizable input field HTML for form data.
     * @param array  $attributes    [optional] Additional attributes for the <table> element. Default is an empty array.
     * @param int    $pad           [optional] Indentation level for formatted output. Default is 0.
     *
     * @return string HTML for the proposal confirmation form.
     */
    public function buildProposalUpdateConfirmationForm(
        string $action,
        string $instructions,
        array $proposal,
        string $inputField,
        array $attributes = [],
        int $pad = 0
    ): string {
        $html = $this->tableBuilder->buildProposalUpdateConfirmationTable($instructions, $proposal, $inputField, $attributes, $pad);
        return $this->htmlBuilder->getForm($action, 'post', $html, ['enctype' => 'multipart/form-data', 'style' => 'margin: 0px; padding: 0px;'], $pad, true);
    }
}
