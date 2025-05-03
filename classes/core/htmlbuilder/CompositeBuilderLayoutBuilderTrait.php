<?php

declare(strict_types=1);

namespace App\core\htmlbuilder;

/**
 * Trait CompositeBuilderLayoutBuilderTrait
 *
 * Provides wrapper methods for page-level form generation using LayoutBuilder.
 * Covers complete page renderings for results, errors, proposals, and semester data.
 *
 * Delegates layout rendering to the injected LayoutBuilder instance.
 *
 * @package App\core\htmlbuilder
 */
trait CompositeBuilderLayoutBuilderTrait
{
    /**
     * Generates the full HTML form for displaying a results page.
     *
     * This form displays a success message styled within a table, with optional additional attributes.
     *
     * @param string $resultsMessage The message to display in the results page.
     * @param array  $attributes     [optional] Additional attributes for the <table> element.
     *                                Default is an empty array.
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     *
     * @return string The generated HTML for the results page.
     */
    public function buildResultsPage(
        string $resultsMessage,
        array $attributes = [],
        int $pad = 0
    ): string {
        return $this->layoutBuilder->buildResultsPage(
            $resultsMessage,
            $attributes,
            $pad
        );
    }

    public function buildResultsBlockPage(
        string $resultsMessage,
        array $attributes = [],
        int $pad = 0
    ): string {
        return $this->layoutBuilder->buildResultsBlockPage(
            $resultsMessage,
            $attributes,
            $pad
        );
    }

    /**
     * Generates the full HTML form for displaying an error page.
     *
     * This form displays an error message styled within a table, with optional additional attributes.
     *
     * @param string $errorMessage The message to display in the error page.
     * @param array  $attributes   [optional] Additional attributes for the <table> element.
     *                              Default is an empty array.
     * @param int    $pad          [optional] Indentation level for formatted output. Default is 0.
     *
     * @return string The generated HTML for the error page.
     */
    public function buildErrorPage(
        string $errorMessage,
        array $attributes = [],
        int $pad = 0
    ): string {
        return $this->layoutBuilder->buildErrorPage(
            $errorMessage,
            $attributes,
            $pad
        );
    }

    /**
     * Generates the full HTML form for selecting a semester.
     *
     * This form includes instructions, year and semester pulldowns, and reset/submit buttons,
     * wrapped inside a styled HTML table structure with horizontal lines.
     *
     * @param string $action        The form's action URL.
     * @param string $instructions  Instructions to display at the top of the form.
     * @param array  $attributes    [optional] Additional attributes for the <table> element.
     *                               Default is an empty array.
     * @param int    $pad           [optional] Indentation level for formatted output. Default is 0.
     *
     * @return string The generated HTML for the semester chooser form.
     */
    public function buildSemesterChooserForm(
        string $action,
        string $instructions,
        array $attributes = [],
        int $pad = 0
    ): string {
        return $this->layoutBuilder->buildSemesterChooserForm(
            $action,
            $instructions,
            $attributes,
            $pad
        );
    }

    /**
     * Generates the full HTML form for listing a semester's programs.
     *
     * This form includes instructions and rows listing proposals for the semester.
     *
     * @param string $action        The form's action URL.
     * @param string $instructions  Instructions to display at the top of the form.
     * @param array  $proposals     Array of proposal data to be displayed in the table.
     * @param array  $attributes    [optional] Additional attributes for the <table> element. Default is an empty array.
     * @param int    $pad           [optional] Indentation level for formatted output. Default is 0.
     *
     * @return string The HTML for the semester lister form.
     */
    public function buildSemesterProposalListForm(
        string $action,
        string $instructions,
        array $proposals,
        array $attributes = [],
        int $pad = 0
    ): string {
        return $this->layoutBuilder->buildSemesterProposalListForm(
            $action,
            $instructions,
            $proposals,
            $attributes,
            $pad
        );
    }

    /**
     * Generates the full HTML page for listing a semester's programs.
     *
     * This pages includes instructions and rows listing proposals for the semester.
     *
     * @param string $instructions  Instructions to display at the top of the form.
     * @param array  $proposals     Array of proposal data to be displayed in the table.
     * @param array  $attributes    [optional] Additional attributes for the <table> element. Default is an empty array.
     * @param int    $pad           [optional] Indentation level for formatted output. Default is 0.
     *
     * @return string The HTML for the semester lister form.
     */
    public function buildSemesterProposalListPage(
        string $instructions,
        array $proposals,
        array $attributes = [],
        int $pad = 0
    ): string {
        return $this->layoutBuilder->buildSemesterProposalListPage(
            $instructions,
            $proposals,
            $attributes,
            $pad
        );
    }

    /**
     * Generates a proposal confirmation form with a customizable input field.
     *
     * @param string $action        The form's action URL.
     * @param string $instructions  Instructions to display at the top of the form.
     * @param array  $proposal      Proposal data (id, code, program number, investigator).
     * @param string $inputField    The input field HTML, customizable per form.
     * @param array  $attributes    [optional] Additional attributes for the <table> element. Default is an empty array.
     * @param int    $pad           [optional] Padding level for formatted output. Default is 0.
     *
     * @return string The HTML for the proposal confirmation form.
     */
    public function buildProposalUpdateConfirmationForm(
        string $action,
        string $instructions,
        array $proposal,
        string $inputField,
        array $attributes = [],
        int $pad = 0
    ): string {
        return $this->layoutBuilder->buildProposalUpdateConfirmationForm(
            $action,
            $instructions,
            $proposal,
            $inputField,
            $attributes,
            $pad
        );
    }
}
