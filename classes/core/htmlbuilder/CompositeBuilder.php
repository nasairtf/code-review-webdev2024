<?php

declare(strict_types=1);

namespace App\core\htmlbuilder;

use App\core\htmlbuilder\HtmlBuildUtility;
use App\core\htmlbuilder\FormElementsBuilder;
use App\core\htmlbuilder\HtmlBuilder;
use App\core\htmlbuilder\LayoutBuilder;
use App\core\htmlbuilder\TableLayoutBuilder;

/**
 * Provides an interface for generating composite HTML components and forms.
 *
 * This utility class builds reusable, high-level HTML components by combining
 * various elements such as pulldowns, tables, buttons, and form fields. It uses
 * dependency-injected builder classes for modular and consistent generation of
 * formatted or raw HTML.
 *
 * Key Features:
 * - Encapsulation of composite component logic (e.g., date selectors, rating forms).
 * - Extensible through injected builder instances.
 * - Optional support for formatted HTML output with indentation and line breaks.
 *
 * @category Utilities
 * @package  App\Core\HtmlBuilder
 * @version  1.0.0
 * @license  MIT License
 */

class CompositeBuilder
{
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
     * @var LayoutBuilder
     */
    private $htmlBuilder;
    private $elemBuilder;
    private $tableBuilder;
    private $layoutBuilder;

    /**
     * Initializes the CompositeBuilder with optional dependencies and formatting preferences.
     *
     * This constructor supports dependency injection for various builder classes used
     * to generate composite HTML components. If no builders are provided, new instances
     * are created internally, inheriting the specified formatting preference.
     *
     * @param bool|null                $formatOutput  Whether to format the output with indentation and line breaks.
     *                                                 Defaults to false.
     * @param HtmlBuilder|null         $htmlBuilder   [optional] Custom instance of HtmlBuilder.
     *                                                 Defaults to a new instance.
     * @param FormElementsBuilder|null $elemBuilder   [optional] Custom instance of FormElementsBuilder.
     *                                                 Defaults to a new instance.
     * @param TableLayoutBuilder|null  $tableBuilder  [optional] Custom instance of TableLayoutBuilder.
     *                                                 Defaults to a new instance.
     * @param LayoutBuilder|null       $layoutBuilder [optional] Custom instance of LayoutBuilder.
     *                                                 Defaults to a new instance.
     */
    public function __construct(
        ?bool $formatOutput = null,
        ?HtmlBuilder $htmlBuilder = null,
        ?FormElementsBuilder $elemBuilder = null,
        ?TableLayoutBuilder $tableBuilder = null,
        ?LayoutBuilder $layoutBuilder = null
    ) {
        $this->formatOutput = $formatOutput ?? false;
        $this->htmlBuilder = $htmlBuilder ?? new HtmlBuilder(
            $formatOutput
        );
        $this->elemBuilder = $elemBuilder ?? new FormElementsBuilder(
            $formatOutput,
            $this->htmlBuilder
        );
        $this->tableBuilder = $tableBuilder ?? new TableLayoutBuilder(
            $formatOutput,
            $this->htmlBuilder,
            $this->elemBuilder
        );
        $this->layoutBuilder = $layoutBuilder ?? new LayoutBuilder(
            $formatOutput,
            $this->htmlBuilder,
            $this->elemBuilder,
            $this->tableBuilder
        );
    }

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
    public function buildFormSectionBreak(
        int $pad = 0
    ): string {
        return $this->elemBuilder->buildFormSectionBreak($pad);
    }

    /**
     * Generates a section containing form buttons.
     *
     * This method creates a table to render form buttons (e.g., submit, reset) in a
     * single centered row. It supports customizable attributes for the table and row,
     * as well as padding for formatting.
     *
     * @param array $buttons   An array of HTML strings representing the buttons.
     *                         Each button should be generated using a helper method,
     *                         such as `getSubmitButton` or `getResetButton`.
     * @param array $rowAttr   Optional attributes for the table row.
     * @param array $tableAttr Optional attributes for the table element.
     *                         Defaults to ['border' => '0', 'cellspacing' => '0', 'cellpadding' => '6'].
     * @param int   $pad       Optional padding level for formatted output. Defaults to 0.
     *
     * @return string The HTML for the buttons section.
     */
    public function buildButtonsFormSection(
        array $buttons,
        array $rowAttr = [],
        array $tableAttr = ['border' => '0', 'cellspacing' => '4'],
        int $pad = 0
    ): string {
        return $this->elemBuilder->buildButtonsFormSection(
            $buttons,
            $rowAttr,
            $tableAttr,
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
     * @param int    $pad          Optional padding level for formatted output. Defaults to 0.
     *
     * @return string The complete HTML for the preamble section.
     */
    public function buildPreambleFormSection(
        string $preambleHtml,
        array $rowAttr = [],
        array $tableAttr = ['border' => '0', 'cellspacing' => '4'],
        int $pad = 0
    ): string {
        return $this->elemBuilder->buildPreambleFormSection(
            $preambleHtml,
            $rowAttr,
            $tableAttr,
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
     * @param int   $pad          Optional padding level for formatted output. Defaults to 0.
     *
     * @return string The HTML for the input fields section.
     */
    public function buildInputFieldsFormSection(
        array $fieldConfigs,
        array $rowAttr = [],
        array $tableAttr = ['border' => '0', 'cellspacing' => '0', 'cellpadding' => '6'],
        int $pad = 0
    ): string {
        return $this->elemBuilder->buildInputFieldsFormSection(
            $fieldConfigs,
            $rowAttr,
            $tableAttr,
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

    /**
     * Generates a styled HTML table layout with a centered message using TableLayoutBuilder.
     *
     * This method is used for creating both result and error pages by
     * displaying the provided message inside a styled HTML table.
     *
     * @param string $message     The message to display in the page.
     * @param bool   $isSuccess   Indicates if the page is a success page (true) or error page (false).
     * @param array  $attributes  [optional] Additional attributes for the <table> element. Default is an empty array.
     * @param int    $pad         [optional] Indentation level for formatted output. Default is 0.
     *
     * @return string The generated HTML for the message page.
     */
    public function buildMessagePageTable(
        string $message,
        bool $isSuccess = true,
        array $attributes = [],
        int $pad = 0
    ): string {
        return $this->tableBuilder->buildMessagePageTable(
            $message,
            $isSuccess,
            $attributes,
            $pad
        );
    }

    /**
     * Generates a styled HTML table layout for displaying multiple messages.
     *
     * This method creates a table to display one or more messages, styled for either
     * success or error contexts based on the `$isSuccess` flag. Additional table attributes
     * and indentation can be customized.
     *
     * @param string $messages    The message(s) to display in the table. For multiple messages, use a
     *                             pre-formatted string.
     * @param bool   $isSuccess   Indicates if the messages represent success (true) or error (false). Default is true.
     * @param array  $attributes  [optional] Additional attributes for the <table> element. Default is an empty array.
     * @param int    $pad         [optional] Indentation level for formatted output. Default is 0.
     *
     * @return string The generated HTML for the messages table.
     */
    public function buildMessagesPageTable(
        string $messages,
        bool $isSuccess = true,
        array $attributes = [],
        int $pad = 0
    ): string {
        return $this->tableBuilder->buildMessagesPageTable(
            $messages,
            $isSuccess,
            $attributes,
            $pad
        );
    }

    /**
     * Generates a table for semester selection with instructions and action buttons.
     *
     * The table includes year and semester pulldowns, a customizable instruction
     * row at the top, and a row of action buttons (e.g., Reset, Submit).
     *
     * @param string $instructions  Instructions to display at the top of the table.
     * @param array  $attributes    [optional] Additional attributes for the <table> element. Default is an empty array.
     * @param int    $pad           [optional] Indentation level for formatted output. Default is 0.
     *
     * @return string The generated HTML for the semester chooser table.
     */
    public function buildSemesterChooserTable(
        string $instructions,
        array $attributes = [],
        int $pad = 0
    ): string {
        return $this->tableBuilder->buildSemesterChooserTable(
            $instructions,
            $attributes,
            $pad
        );
    }

    /**
     * Generates the year and semester pulldown table.
     *
     * @param int $pad  [optional] Indentation level for formatted output. Default is 0.
     *
     * @return string The HTML for the year/semester pulldowns.
     */
    public function buildSemesterChooserPulldownsTable(
        int $pad = 0
    ): string {
        return $this->tableBuilder->buildSemesterChooserPulldownsTable(
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
    public function buildSemesterProposalListTable(
        string $action,
        string $instructions,
        array $proposals,
        array $attributes = [],
        int $pad = 0
    ): string {
        return $this->tableBuilder->buildSemesterProposalListTable(
            $action,
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
    public function buildProposalUpdateConfirmationTable(
        string $instructions,
        array $proposal,
        string $inputField,
        array $attributes = [],
        int $pad = 0
    ): string {
        return $this->tableBuilder->buildProposalUpdateConfirmationTable(
            $instructions,
            $proposal,
            $inputField,
            $attributes,
            $pad
        );
    }

    /**
     * Generates a table with a textarea for input and an optional note row.
     *
     * @param string $name       The name attribute for the textarea.
     * @param string $label      The label for the textarea.
     * @param string $value      [optional] The initial value for the textarea. Default is an empty string.
     * @param string $bgColor    Background color for the table rows.
     * @param string $note       [optional] An optional note to display in the table. Default is an empty string.
     * @param int    $pad        [optional] Indentation level for formatted output. Default is 0.
     *
     * @return string The HTML for the textarea table.
     */
    public function buildTextareaTable(
        string $name,
        string $label,
        string $value = '',
        string $bgColor = '',
        string $note = '',
        int $pad = 0
    ): string {
        return $this->tableBuilder->buildTextareaTable(
            $name,
            $label,
            $value,
            $bgColor,
            $note,
            $pad
        );
    }

    /**
     * Creates an HTML table containing a label and content, with optional styling and layout settings.
     *
     * This method generates a table with a specified background color, optional inline display for the label
     * and content, and configurable layout for the label row. It can produce either a single row with both
     * label and content in cells or two rows, one for the label and one for the content.
     *
     * @param string $label         The label text to display in the first cell.
     * @param string $content       The content to display in the second cell.
     * @param string $bgColor       The background color for the table rows.
     * @param bool   $labelRow      [optional] If true, the label appears in its own row; otherwise, both cells are
     *                               in the same row. Default is true.
     * @param bool   $inlineLabel   [optional] If true, displays the label as inline content. Default is false.
     * @param bool   $inlineContent [optional] If true, displays the content as inline content. Default is false.
     * @param int    $pad           [optional] The indentation level for formatted output. Default is 0.
     *
     * @return string The HTML for the table containing the label and content with specified layout.
     */
    public function buildLabeledElementTable(
        string $label,
        string $content,
        string $bgColor,
        bool $labelRow = true,
        bool $inlineLabel = false,
        bool $inlineContent = false,
        int $pad = 0
    ): string {
        return $this->tableBuilder->buildLabeledElementTable(
            $label,
            $content,
            $bgColor,
            $labelRow,
            $inlineLabel,
            $inlineContent,
            $pad
        );
    }

    /**
     * Generates a table for selecting the observation location (Remote or Onsite).
     *
     * @param string $name           The name attribute for the radio input.
     * @param string $label          The label for the radio buttons.
     * @param string $selectedOption The selected value (0 for Remote, 1 for Onsite).
     * @param string $bgColor        Background color for the table row.
     * @param bool   $position       Whether to place the label in the first column.
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     *
     * @return string The HTML for the observation location table.
     */
    public function buildLabeledRemoteObsTable(
        string $name,
        string $label,
        string $selectedOption,
        string $bgColor,
        bool $position = false,
        int $pad = 0
    ): string {
        return $this->tableBuilder->buildLabeledRemoteObsTable(
            $name,
            $label,
            $selectedOption,
            $bgColor,
            $position,
            $pad
        );
    }

    /**
     * Generates a rating table with radio buttons for feedback ratings.
     *
     * @param string $name           The name attribute for the radio inputs.
     * @param string $label          The label for the radio buttons.
     * @param string $selectedOption The selected value (rating 1-5).
     * @param string $bgColor        Background color for the table row.
     * @param bool   $addNA          Whether to add an "N/A" option (value 0).
     * @param bool   $labelRow       Whether the label should be on a separate row.
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     *
     * @return string The HTML for the rating table.
     */
    public function buildLabeledRatingTable(
        string $name,
        string $label,
        string $selectedOption,
        string $bgColor,
        bool $addNA = false,
        bool $labelRow = false,
        int $pad = 0
    ): string {
        return $this->tableBuilder->buildLabeledRatingTable(
            $name,
            $label,
            $selectedOption,
            $bgColor,
            $addNA,
            $labelRow,
            $pad
        );
    }

    /**
     * Generates a table with labeled checkboxes.
     *
     * This table has a label row or a label cell, with checkboxes displayed in a single row.
     * Each checkbox is labeled, and selected options are pre-checked.
     *
     * @param string $name            The base name attribute for each checkbox.
     * @param array  $options         An associative array of checkboxes (value => label).
     * @param array  $selectedOptions An array of selected checkbox values.
     * @param string $label           The label for the table or label cell.
     * @param string $bgColor         The background color for the table row.
     * @param bool   $labelRow        [optional] Whether to add a label row at the top. Default is false.
     * @param int    $pad             [optional] Indentation level for formatted output. Default is 0.
     *
     * @return string The generated HTML for the labeled checkbox table.
     */
    public function buildLabeledCheckboxTable(
        string $name,
        array $options,
        array $selectedOptions,
        string $label,
        string $bgColor,
        bool $labelRow = false,
        int $pad = 0
    ): string {
        return $this->tableBuilder->buildLabeledCheckboxTable(
            $name,
            $options,
            $selectedOptions,
            $label,
            $bgColor,
            $labelRow,
            $pad
        );
    }

    /**
     * Generates a table with checkboxes only (no label row).
     *
     * Each checkbox is labeled, and selected options are pre-checked. All checkboxes
     * are displayed in a single row, with each option label appearing beside its checkbox.
     *
     * @param string $name            The base name attribute for each checkbox.
     * @param array  $options         An associative array of checkboxes (value => label).
     * @param array  $selectedOptions An array of selected checkbox values.
     * @param string $bgColor         The background color for the table row.
     * @param int    $pad             [optional] Indentation level for formatted output. Default is 0.
     *
     * @return string The generated HTML for the checkbox-only table.
     */
    public function buildCheckboxTable(
        string $name,
        array $options,
        array $selectedOptions,
        string $bgColor,
        int $pad = 0
    ): string {
        return $this->tableBuilder->buildCheckboxTable(
            $name,
            $options,
            $selectedOptions,
            $bgColor,
            $pad
        );
    }

    /**
     * Generates a table with labeled checkboxes and a pulldown menu for instruments.
     *
     * This method creates an HTML table containing labeled checkboxes alongside
     * a pulldown menu for instrument selection. Selected options are pre-checked,
     * and the table's background color and indentation can be customized.
     *
     * @param array  $names           An associative array specifying names for the checkboxes and pulldown elements.
     * @param array  $options         An associative array of options for the pulldown menu, where keys are values
     *                                and values are labels.
     * @param array  $selectedOptions An array of values corresponding to pre-selected checkboxes.
     * @param string $bgColor         The background color for the table rows.
     * @param int    $pad             [optional] Indentation level for formatted output. Default is 0.
     *
     * @return string The generated HTML for the instrument selection table.
     */
    public function buildInstrumentCheckboxPulldownTable(
        array $names,
        array $options,
        array $selectedOptions,
        string $bgColor,
        int $pad = 0
    ): string {
        return $this->tableBuilder->buildInstrumentCheckboxPulldownTable(
            $names,
            $options,
            $selectedOptions,
            $bgColor,
            $pad
        );
    }

    /**
     * Generates a rating table with radio buttons for feedback ratings.
     *
     * @param string $name           The name attribute for the radio inputs.
     * @param string $label          The label for the radio buttons.
     * @param string $selectedOption The selected value (rating 1-5).
     * @param string $bgColor        Background color for the table row.
     * @param bool   $addNA          Whether to add an "N/A" option (value 0).
     * @param bool   $labelRow       Whether the label should be on a separate row.
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     *
     * @return string The HTML for the rating table.
     */
    public function buildRatingTable(
        string $name,
        string $selectedOption,
        string $bgColor,
        bool $addNA = false,
        int $pad = 0
    ): string {
        return $this->tableBuilder->buildRatingTable(
            $name,
            $selectedOption,
            $bgColor,
            $addNA,
            $pad
        );
    }

    /**
     * Generates a table for selecting the observation location (Remote or Onsite).
     *
     * @param string $name           The name attribute for the radio input.
     * @param string $selectedOption The selected value (0 for Remote, 1 for Onsite).
     * @param string $bgColor        Background color for the table row.
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     *
     * @return string The HTML for the observation location table.
     */
    public function buildRemoteObsTable(
        string $name,
        string $selectedOption,
        string $bgColor,
        int $pad = 0
    ): string {
        return $this->tableBuilder->buildRemoteObsTable(
            $name,
            $selectedOption,
            $bgColor,
            $pad
        );
    }

    /**
     * Generates an HTML table with labeled date pulldowns for selecting a date.
     *
     * This method creates a table containing labeled pulldowns for year, month, and day selection.
     * It defaults to the current date if `$options` are not provided, and offers a customizable
     * year range starting from `$startYear` up to `$endYear`.
     *
     * @param array  $names      An associative array with 'year', 'month', and 'day' keys,
     *                           each representing the name attribute for its respective pulldown.
     * @param string $label      The label for the date.
     * @param array  $options    [optional] An array with pre-selected options for 'year', 'month', and 'day'.
     *                           Defaults to the current date if not provided.
     * @param int    $startYear  [optional] Start year for the year pulldown. Defaults to current year - 5.
     * @param int    $endYear    [optional] End year for the year pulldown. Defaults to current year + 3.
     * @param string $bgColor    Background color for the table rows.
     * @param int    $pad        [optional] Indentation level for formatted output. Default is 0.
     *
     * @return string The HTML for the date pulldown table.
     */
    public function buildDatePulldownsTable(
        array $names,
        string $label,
        array $options = [],
        int $startYear = null,
        int $endYear = null,
        string $bgColor = '',
        int $pad = 0
    ): string {
        return $this->tableBuilder->buildDatePulldownsTable(
            $names,
            $label,
            $options,
            $startYear,
            $endYear,
            $bgColor,
            $pad
        );
    }

    /**
     * Generates an HTML table with three pulldown menus for selecting single-digit numbers.
     *
     * Each pulldown allows selection of numbers from 0 to 9, with the default value set to 0 if not specified.
     * The pulldowns have customizable `name` attributes, specified in `$names`.
     *
     * @param array  $names       An associative array specifying custom names for the pulldowns,
     *                            with keys [1], [2], and [3] for each pulldown's `name` attribute.
     * @param string $label       The label text displayed in the first row of the table.
     * @param array  $options     [optional] An array with default values for each pulldown, keyed by [1], [2], and [3].
     *                            Defaults to 0 if not provided.
     * @param string $bgColor     Background color for the table rows.
     * @param int    $pad         [optional] Indentation level for formatted output. Default is 0.
     *
     * @return string The HTML for the three-number pulldown table.
     */
    public function buildThreeNumberPulldownsTable(
        array $names,
        string $label,
        array $options = [],
        string $bgColor = '',
        int $pad = 0
    ): string {
        return $this->tableBuilder->buildThreeNumberPulldownsTable(
            $names,
            $label,
            $options,
            $bgColor,
            $pad
        );
    }

    /**
     * Generates a pulldown (dropdown) menu for selecting a semester program.
     *
     * This method uses `HtmlBuilder` to create a dropdown menu populated with
     * semester program options, where each option represents a program ID and
     * principal investigator. The selected option is highlighted based on the
     * provided `$selectedOption`.
     *
     * @param string $name           The name attribute for the dropdown element.
     * @param string $selectedOption  The option to be pre-selected in the dropdown.
     * @param array  $options         An associative array of options for the pulldown,
     *                                where the keys are display labels and values are program IDs.
     * @param int    $pad             [optional] Padding level for formatted output. Default is 0.
     *
     * @return string                 The HTML for the semester program pulldown menu.
     */
    public function buildSemesterProgramsPulldown(
        string $name,
        string $selectedOption,
        array $options,
        int $pad = 0
    ): string {
        return $this->tableBuilder->buildSemesterProgramsPulldown(
            $names,
            $selectedOption,
            $options,
            $pad
        );
    }

    /**
     * Generates a table with an optional label row and a pulldown menu for selecting a program.
     *
     * This method creates a styled HTML table with an optional label and a dropdown menu
     * populated with semester program options. The dropdown is formatted with padding, and
     * the background color for each row is adjustable.
     *
     * @param string $name            The name attribute for the pulldown element.
     * @param string $label           [optional] The label for the pulldown menu. If left blank, no label row
     *                                 is created.
     * @param array  $programs        Associative array of programs where keys are display labels and values are
     *                                 program IDs.
     * @param string $selectedOption  The option to be pre-selected in the pulldown.
     * @param string $bgColor         The background color for the table rows.
     * @param int    $pad             [optional] Padding level for formatted output. Default is 0.
     *
     * @return string                 The HTML for the programs list pulldown table.
     */
    public function buildProgramsListPulldownTable(
        string $name = '',
        string $label = '',
        array $programs = [],
        string $selectedOption = '',
        string $bgColor = '',
        int $pad = 0
    ): string {
        return $this->tableBuilder->buildProgramsListPulldownTable(
            $name,
            $label,
            $programs,
            $selectedOption,
            $bgColor,
            $pad
        );
    }

    /**
     * Generates a complex table layout for selecting a program and entering a PI name.
     *
     * Contains a semester label and pulldown, a row for selecting programs, and a PI name input field.
     *
     * @param array  $names          Associative array with field name mappings for the pulldown, hidden input,
     *                                and PI field.
     * @param array  $labels         Associative array with text labels for semester, program, and PI fields.
     * @param array  $programs       Array of program options.
     * @param array  $options        [optional] Default selections for fields.
     * @param string $bgColor        Background color for table rows.
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     *
     * @return string HTML string for the program pulldown and PI name entry table.
     */
    public function buildProgramPulldownPINameTable(
        array $names,
        array $labels,
        array $programs,
        array $options = [],
        string $bgColor = '',
        int $pad = 0
    ): string {
        return $this->tableBuilder->buildProgramPulldownPINameTable(
            $names,
            $labels,
            $programs,
            $options,
            $bgColor,
            $pad
        );
    }

    /**
     * Builds an HTML table for a single proposal entry with embedded hidden form inputs.
     *
     * This method generates a formatted HTML table row representing a single proposal entry.
     * It includes proposal details and relevant hidden fields (`ObsApp_id`, `PIName`, `Semester`).
     *
     * @param string $proposal The program number or proposal identifier to display.
     * @param array  $program  An associative array with program details:
     *                         - 'i': Observation application ID.
     *                         - 'n': PI's last name.
     *                         - 's': Semester identifier (e.g., '2024B').
     * @param string $bgColor  The background color for the table row.
     * @param int    $pad      Optional. Padding for the table and cells.
     *                         Defaults to 0.
     *
     * @return string The generated HTML string for the proposal table.
     */
    public function buildSingleProposalTable(
        string $proposal,
        array $program,
        string $bgColor,
        int $pad = 0
    ): string {
        return $this->tableBuilder->buildSingleProposalTable(
            $proposal,
            $program,
            $bgColor,
            $pad
        );
    }

    /**
     * Generates an HTML table for selecting a date range with labeled pulldowns.
     *
     * This method creates a table with two columns, each containing a date pulldown
     * component, to enable selection of a start and end date. Labels for each pulldown
     * are displayed as specified in the `$labels` array.
     *
     * @param array  $startnames   An associative array defining 'year', 'month', and 'day' keys
     *                             for the name attributes of the start date pulldown.
     * @param array  $endnames     An associative array defining 'year', 'month', and 'day' keys
     *                             for the name attributes of the end date pulldown.
     * @param array  $labels       An associative array containing labels for the start and end date
     *                             pulldowns, keyed by 'start' and 'end'.
     * @param string $bgColor      The background color for the table rows.
     * @param int    $pad          [optional] Indentation level for formatted output. Default is 0.
     *
     * @return string The HTML for the date range table.
     */
    public function buildDateRangeTable(
        array $startnames,
        array $endnames,
        array $labels,
        array $values,
        string $bgColor,
        int $pad = 0
    ): string {
        return $this->tableBuilder->buildDateRangeTable(
            $startnames,
            $endnames,
            $labels,
            $values,
            $bgColor,
            $pad
        );
    }

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
