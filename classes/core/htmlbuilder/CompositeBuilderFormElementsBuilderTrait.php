<?php

declare(strict_types=1);

namespace App\core\htmlbuilder;

/**
 * Trait CompositeBuilderFormElementsBuilderTrait
 *
 * Provides wrapper methods for composite components that rely on FormElementsBuilder.
 * This includes helpers for sections, buttons, pulldowns, and input elements used
 * throughout HTML forms and layout structures.
 *
 * Delegates rendering tasks to the injected FormElementsBuilder instance.
 *
 * @package App\core\htmlbuilder
 */
trait CompositeBuilderFormElementsBuilderTrait
{
    /**
     * Generates a horizontal line table cell with padding using FormElementsBuilder.
     *
     * @param int $colspan  [optional] The number of columns to span the line. Default is 1.
     * @param int $pad      [optional] Indentation level for formatted output. Default is 0.
     *
     * @return string The HTML for the horizontal line.
     */
    public function buildLineTableCell(
        int $colspan = 1,
        int $pad = 0
    ): string {
        return $this->elemBuilder->buildLineTableCell(
            $colspan,
            $pad
        );
    }

    /**
     * Builds a horizontal line break section for the form.
     *
     * This method returns a formatted HTML line element, which serves as a visual
     * separator within the form.
     *
     * @return string The HTML for the section break, formatted as a horizontal line.
     */
    public function buildPageSectionBreak(
        int $pad = 0
    ): string {
        return $this->elemBuilder->buildPageSectionBreak($pad);
    }

    /**
     * Builds a generic page section with standard centering and optional line break.
     *
     * This method provides a standardized wrapper for page sections, ensuring
     * that the content is centered and optionally includes a line break.
     *
     * @param string $contentHtml   The HTML content of the section (typically a table element).
     * @param string $sectionTag    Section tag to be used in the header, used for debugging or commenting.
     * @param bool   $includeBreak  Whether to include a line break after the section. Defaults to false.
     * @param int    $pad           Optional padding level for formatted output. Defaults to 0.
     *
     * @return string The formatted HTML for the section.
     */
    public function buildPageSection(
        string $contentHtml,
        string $sectionTag,
        bool $includeBreak = false,
        int $pad = 0
    ): string {
        // Wrap the table in additional markup for centering and styling
        return $this->elemBuilder->buildPageSection(
            $contentHtml,
            $sectionTag,
            $includeBreak,
            $pad
        );
    }

    /**
     * Generates a section containing form buttons.
     *
     * This method creates a table to render form buttons (e.g., submit, reset) in a
     * single centered row. It supports customizable attributes for the table and row,
     * as well as padding for formatting. It uses the generic section builder to simplify wrapping.
     *
     * @param array $buttons      An array of HTML strings representing the buttons.
     *                            Each button should be generated using a helper method,
     *                            such as `getSubmitButton` or `getResetButton`.
     * @param array $rowAttr      Optional attributes for the table row.
     * @param array $tableAttr    Optional attributes for the table element.
     *                            Defaults to ['border' => '0', 'cellspacing' => '0', 'cellpadding' => '6'].
     * @param bool  $includeBreak Whether to include a line break after the section. Defaults to false.
     * @param int   $pad          Optional padding level for formatted output. Defaults to 0.
     *
     * @return string The HTML for the buttons section.
     */
    public function buildButtonsFormSection(
        array $buttons,
        array $rowAttr = [],
        array $tableAttr = ['border' => '0', 'cellspacing' => '0', 'cellpadding' => '6'],
        bool $includeBreak = false,
        int $pad = 0
    ): string {
        return $this->elemBuilder->buildButtonsFormSection(
            $buttons,
            $rowAttr,
            $tableAttr,
            $includeBreak,
            $pad
        );
    }

    /**
     * Builds a preamble section for a form or page.
     *
     * This method generates a section containing the provided preamble HTML, wrapped in
     * a table for layout. Attributes for the table and row can be customized, along
     * with padding for formatting.
     *
     * @param string $preambleHtml HTML content for the preamble (e.g., instructions or guidance text).
     * @param array  $rowAttr      Optional attributes for the table row.
     * @param array  $tableAttr    Optional attributes for the table element.
     *                             Defaults to ['border' => '0', 'cellspacing' => '0', 'cellpadding' => '6'].
     * @param bool   $includeBreak Whether to include a line break after the section. Defaults to false.
     * @param int    $pad          Optional padding level for formatted output. Defaults to 0.
     *
     * @return string The complete HTML for the preamble section.
     */
    public function buildPreambleFormSection(
        string $preambleHtml,
        array $rowAttr = [],
        array $tableAttr = ['border' => '0', 'cellspacing' => '0', 'cellpadding' => '6'],
        bool $includeBreak = false,
        int $pad = 0
    ): string {
        return $this->elemBuilder->buildPreambleFormSection(
            $preambleHtml,
            $rowAttr,
            $tableAttr,
            $includeBreak,
            $pad
        );
    }

    /**
     * Builds the input fields section for a form.
     *
     * This method generates a table containing input fields with their corresponding labels.
     * The fields are dynamically created based on the provided configuration array.
     *
     * Each row configuration should include:
     * - 'label' (string): The label text for the input field.
     * - 'name' (string): The name attribute for the input field.
     * - 'value' (mixed): The default value for the input field (optional, defaults to an empty string).
     * - 'type' (string): The input type (e.g., 'text', 'password') (optional, defaults to 'text').
     * - 'attr' (array): Additional attributes for the input field (optional).
     *
     * @param array $fieldConfigs An array of field configurations for the rows.
     * @param array $rowAttr      Optional attributes for the table row.
     * @param array $tableAttr    Optional attributes for the table element.
     *                            Defaults to ['border' => '0', 'cellspacing' => '0', 'cellpadding' => '6'].
     * @param bool  $includeBreak Whether to include a line break after the section. Defaults to false.
     * @param int   $pad          Optional padding level for formatted output. Defaults to 0.
     *
     * @return string The HTML for the input fields section.
     */
    public function buildInputFieldsFormSection(
        array $fieldConfigs,
        array $rowAttr = [],
        array $tableAttr = ['border' => '0', 'cellspacing' => '0', 'cellpadding' => '6'],
        bool $includeBreak = false,
        int $pad = 0
    ): string {
        return $this->elemBuilder->buildInputFieldsFormSection(
            $fieldConfigs,
            $rowAttr,
            $tableAttr,
            $includeBreak,
            $pad
        );
    }

    /**
     * Generates a set of semester chooser action buttons using FormElementsBuilder.
     *
     * @param string $button  The action label for the submit button.
     * @param int    $pad     [optional] Indentation level for formatted output. Default is 0.
     *
     * @return string The HTML for the reset and submit buttons.
     */
    public function buildSemesterChooserActionButtons(
        string $button,
        int $pad = 0
    ): string {
        return $this->elemBuilder->buildSemesterChooserActionButtons(
            $button,
            $pad
        );
    }

    /**
     * Generates a form trigger for proposal actions, with a hidden input and submit button.
     *
     * @param string $button   The action identifier for the button.
     * @param string $label    The button label text.
     * @param string $id       The proposal ID for the hidden input field.
     * @param string $color    The color styling for the submit button.
     * @param int    $pad      [optional] Indentation level for formatted output. Default is 0.
     *
     * @return string The HTML for the proposal action trigger.
     */
    public function buildProposalActionTrigger(
        string $button,
        string $label,
        string $id,
        string $color,
        int $pad = 0
    ): string {
        return $this->elemBuilder->buildProposalActionTrigger(
            $button,
            $label,
            $id,
            $color,
            $pad
        );
    }

    /**
     * Generates a form containing a button for proposal selection or editing using FormElementsBuilder.
     *
     * @param string $action  The form's action URL.
     * @param string $id      The proposal ID for the hidden input field.
     * @param string $color   The color for the submit button.
     * @param int    $pad     [optional] Indentation level for formatted output. Default is 0.
     *
     * @return string The HTML for the button form in the proposal list.
     */
    public function buildSemesterProposalListButtonForm(
        string $action,
        string $id,
        string $color,
        int $pad = 0
    ): string {
        return $this->elemBuilder->buildSemesterProposalListButtonForm(
            $action,
            $id,
            $color,
            $pad
        );
    }

    /**
     * Generates a single row for the proposal list table with the given details using FormElementsBuilder.
     *
     * @param string $action    The form's action URL.
     * @param array  $proposal  Array of proposal data (id, code, program number, investigator).
     * @param string $bgColor   Background color for the row.
     * @param int    $pad       [optional] Indentation level for formatted output. Default is 0.
     *
     * @return string The HTML for the proposal lister row.
     */
    public function buildSemesterProposalListFormRow(
        string $action,
        array $proposal,
        string $bgColor,
        int $pad = 0
    ): string {
        return $this->elemBuilder->buildSemesterProposalListFormRow(
            $action,
            $proposal,
            $bgColor,
            $pad
        );
    }

    /**
     * Generates a set of labeled pulldowns for date selection (year, month, day).
     *
     * Each pulldown menu allows the user to select a specific part of a date.
     * The year pulldown range can be customized using `$startYear` and `$endYear`.
     * Defaults to the current date if no pre-selected values are provided.
     *
     * @param array $names       Associative array with mandatory keys 'year', 'month', and 'day', representing
     *                            the pulldown names.
     * @param array $options     [optional] Pre-selected options with keys 'year', 'month', and 'day'.
     *                            Defaults to the current date.
     * @param int   $startYear   [optional] Start year for the year pulldown. Defaults to current year - 5.
     * @param int   $endYear     [optional] End year for the year pulldown. Defaults to current year + 3.
     * @param int   $pad         [optional] Indentation level for formatted output. Default is 0.
     *
     * @return string The HTML for the date pulldown set.
     */
    public function buildDatePulldowns(
        array $names,
        array $options = [],
        int $startYear = null,
        int $endYear = null,
        int $pad = 0
    ): string {
        return $this->elemBuilder->buildDatePulldowns(
            $names,
            $options,
            $startYear,
            $endYear,
            $pad
        );
    }

    /**
     * Generates three pulldown menus for selecting single-digit numbers (0-9).
     *
     * Each pulldown corresponds to a number selection, with names and default values
     * specified in `$names` and `$options`. Defaults to 0 if no pre-selected values are provided.
     *
     * @param array $names      Indexed array specifying custom names for the pulldowns
     *                           (e.g., `$names[1]`, `$names[2]`, `$names[3]`).
     * @param array $options    [optional] Indexed array specifying pre-selected values for each pulldown
     *                           (e.g., `$options[1]`, `$options[2]`, `$options[3]`).
     *                          Defaults to 0 for all pulldowns.
     * @param int   $pad        [optional] Indentation level for formatted output. Default is 0.
     *
     * @return string The HTML for the three-number pulldown set.
     */
    public function buildThreeNumberPulldowns(
        array $names,
        array $options = [],
        int $pad = 0
    ): string {
        return $this->elemBuilder->buildThreeNumberPulldowns(
            $names,
            $options,
            $pad
        );
    }
}
