<?php

declare(strict_types=1);

namespace App\core\htmlbuilder;

use App\core\htmlbuilder\HtmlBuildUtility;
use App\core\htmlbuilder\FormElementsBuilder;
use App\core\htmlbuilder\HtmlBuilder;
use App\core\htmlbuilder\BuilderValidationTrait;

/**
 * /home/webdev2024/classes/core/htmlbuilder/TableLayoutBuilder.php
 *
 * Generates a multi-select dropdown HTML element.
 *
 * @param string $name           The name attribute for the <select> element.
 * @param array  $selectedOptions An array of options that should be pre-selected.
 * @param array  $options        An associative array of options (key = value attribute, value = display text).
 * @param array  $attributes     [optional] Additional attributes for the <select> element. Default is an empty array.
 * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
 * @param bool   $isHtml         [optional] If true, content is treated as pre-escaped HTML. Default is false.
 *
 * @return string The HTML for the multi-select pulldown.
 */

class TableLayoutBuilder
{
    use BuilderValidationTrait;

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
     * Builder object for generating base-level HTML components.
     *
     * @var HtmlBuilder
     */
    private $htmlBuilder;

    /**
     * Builder object for generating complex HTML components.
     *
     * @var FormElementsBuilder
     */
    private $formBuilder;

    /**
     * Constructor to set the formatting preference.
     *
     * @param bool|null                $formatOutput If true, output will be formatted with indentation.
     * @param HtmlBuilder|null         $htmlBuilder  [optional] An instance of HtmlBuilder. Defaults to a new instance.
     * @param FormElementsBuilder|null $formBuilder  [optional] An instance of FormElementsBuilder.
     *                                                Defaults to a new instance.
     */
    public function __construct(
        ?bool $formatOutput = null,
        ?HtmlBuilder $htmlBuilder = null,
        ?FormElementsBuilder $formBuilder = null
    ) {
        $this->formatOutput = $formatOutput ?? false;
        $this->htmlBuilder = $htmlBuilder ?? new HtmlBuilder($formatOutput);
        $this->formBuilder = $formBuilder ?? new FormElementsBuilder($formatOutput, $this->htmlBuilder);
    }

    /**
     * Generates a styled HTML table layout with a centered message.
     *
     * This method creates a basic table layout, commonly used for displaying
     * either success or error messages. It expects the message contents to be
     * pre-sanitized and does not perform additional encoding or sanitization.
     *
     * Example Output:
     * <table>
     *   <tr><td>--- Horizontal Line ---</td></tr>
     *   <tr><td align="center">Your Message Here</td></tr>
     *   <tr><td>--- Horizontal Line ---</td></tr>
     * </table>
     *
     * @param string $message     The pre-sanitized message to display.
     * @param bool   $isSuccess   Indicates whether the table is for success (true) or error (false).
     * @param array  $attributes  [optional] HTML attributes for the <table> element. Default is an empty array.
     * @param int    $pad         [optional] Indentation level for formatted output. Default is 0.
     *
     * @return string The generated HTML for the message table layout.
     */
    public function buildMessagePageTable(
        string $message,
        bool $isSuccess = true,
        array $attributes = [],
        int $pad = 0
    ): string {
        $tablePad = $pad;
        $tableRowPad = $tablePad + 2;
        $tableCellPad = $tableRowPad + 2;
        $rowAttributes = ['style' => 'height: 45px;', 'align' => 'center'];
        $horizontalLine = $this->formBuilder->buildLineTableCell(1, $tableRowPad);
        $messageCell = $this->htmlBuilder->getTableCell($message, false, $isSuccess, [], $tableCellPad, false);
        $messageRow = $this->htmlBuilder->getTableRowFromCells([$messageCell], $rowAttributes, $tableRowPad);
        $tableAttributes = array_merge(
            ['width' => '100%', 'border' => '0', 'cellspacing' => '0', 'cellpadding' => '6'],
            $attributes
        );
        return $this->htmlBuilder->getTableFromRows(
            [$horizontalLine, $messageRow, $horizontalLine],
            $tableAttributes,
            $tablePad
        );
    }

    /**
     * Generates a styled HTML table layout with a centered message.
     *
     * This method accepts raw message content, sanitizes it, and outputs a formatted
     * HTML table layout. Commonly used for scenarios where the message content needs
     * additional handling before display.
     *
     * @param string $messages    The raw message content to be displayed. Sanitization will be applied.
     * @param bool   $isSuccess   Indicates whether the table is for success (true) or error (false).
     * @param array  $attributes  [optional] HTML attributes for the <table> element. Default is an empty array.
     * @param int    $pad         [optional] Indentation level for formatted output. Default is 0.
     *
     * @return string The generated HTML for the message table layout.
     */
    public function buildMessagesPageTable(
        string $messages,
        bool $isSuccess = true,
        array $attributes = [],
        int $pad = 0
    ): string {
        $tablePad = $pad;
        $tableRowPad = $tablePad + 2;
        $tableCellPad = $tableRowPad + 2;
        $rowAttributes = ['style' => 'height: 45px;', 'align' => 'center'];
        $horizontalLine = $this->formBuilder->buildLineTableCell(1, $tableRowPad);
        $messagesCell = $this->htmlBuilder->getTableCell($messages, false, $isSuccess, [], $tableCellPad, true);
        $messagesRow = $this->htmlBuilder->getTableRowFromCells([$messagesCell], $rowAttributes, $tableRowPad);
        $tableAttributes = array_merge(
            ['width' => '100%', 'border' => '0', 'cellspacing' => '0', 'cellpadding' => '6'],
            $attributes
        );
        return $this->htmlBuilder->getTableFromRows(
            [$horizontalLine, $messagesRow, $horizontalLine],
            $tableAttributes,
            $tablePad
        );
    }

    /**
     * Generates an HTML table layout for selecting a semester.
     *
     * Includes instructions, year and semester pulldowns, and reset/submit buttons,
     * wrapped in a styled table structure with horizontal lines. Typically used
     * as part of a semester selection form.
     *
     * Example Structure:
     * <table>
     *   <tr><td>Instructions</td></tr>
     *   <tr><td>Year Pulldown | Semester Pulldown</td></tr>
     *   <tr><td>Reset | Submit Buttons</td></tr>
     * </table>
     *
     * @param string $instructions  Instructions displayed at the top of the table.
     * @param array  $attributes    [optional] HTML attributes for the <table> element. Default is an empty array.
     * @param int    $pad           [optional] Indentation level for formatted output. Default is 0.
     *
     * @return string The generated HTML for the semester chooser table.
     */
    public function buildSemesterChooserTable(
        string $instructions,
        array $attributes = [],
        int $pad = 0
    ): string {
        $tablePad = $pad;
        $tableRowPad = $tablePad + 2;
        $buttonPad = $tableRowPad + 4;
        $pulldownPad = $tableRowPad + 4;
        $colors = ['#C0C0C0', '#CCCCCC'];
        $rowAttributes = ['style' => 'height: 45px;', 'align' => 'center'];
        $tableAttributes = array_merge(
            ['width' => '100%', 'border' => '0', 'cellspacing' => '0', 'cellpadding' => '6'],
            $attributes
        );
        $horizontalLine = $this->formBuilder->buildLineTableCell(1, $tableRowPad);
        $pulldowns = $this->buildSemesterChooserPulldownsTable($pulldownPad);
        $buttons = $this->formBuilder->buildSemesterChooserActionButtons('submit', $buttonPad);
        $tableInstructions = $this->htmlBuilder->getTableRowFromArrayWithAlternatingColor(
            [$instructions],
            '',
            $colors,
            [true],
            $rowAttributes,
            $tableRowPad,
            false
        );
        $tablePulldowns = $this->htmlBuilder->getTableRowFromArrayWithAlternatingColor(
            [$pulldowns],
            $colors[0],
            $colors,
            [false],
            $rowAttributes,
            $tableRowPad,
            true
        );
        $tableButtons = $this->htmlBuilder->getTableRowFromArrayWithAlternatingColor(
            [$buttons],
            $colors[1],
            $colors,
            [false],
            $rowAttributes,
            $tableRowPad,
            true
        );
        return $this->htmlBuilder->getTableFromRows(
            [$horizontalLine, $tableInstructions, $tablePulldowns, $tableButtons, $horizontalLine],
            $tableAttributes,
            $tablePad
        );
    }

    /**
     * Generates an HTML table layout for the year and semester pulldowns.
     *
     * @param int $pad [optional] Indentation level for formatted output. Default is 0.
     *
     * @return string HTML string for the year/semester pulldowns.
     */
    public function buildSemesterChooserPulldownsTable(
        int $pad = 0
    ): string {
        $pulldownPad = $pad + 6;
        $tablePad = $pad;
        $yearLabel = 'Year:';
        $yearPulldown = $this->htmlBuilder->getYearsPulldown('y', date('Y'), 2001, date('Y') + 1, [], $pulldownPad);
        $semesterLabel = 'Semester:';
        $semesterPulldown = $this->htmlBuilder->getSemestersPulldown('s', '', [], $pulldownPad);
        return $this->htmlBuilder->getTableFromArray(
            [[$yearLabel, $yearPulldown, $semesterLabel, $semesterPulldown]],
            false,
            [[true, false, true, false]],
            [],
            $tablePad,
            true
        );
    }

    /**
     * Generates an HTML table layout listing proposals for a semester.
     *
     * Displays each proposal in its own row within a styled table.
     *
     * @param string $action        The form's action URL.
     * @param string $instructions  Instructions displayed at the top of the table.
     * @param array  $proposals     Array of proposal data to be displayed in the table.
     * @param array  $attributes    [optional] Additional attributes for the <table> element.
     *                               Default is an empty array.
     * @param int    $pad           [optional] Indentation level for formatted output. Default is 0.
     *
     * @return string HTML string for the semester proposal list table.
     */
    public function buildSemesterProposalListTable(
        string $action,
        string $instructions,
        array $proposals,
        array $attributes = [],
        int $pad = 0
    ): string {
        $tablePad = $pad;
        $tableRowPad = $pad + 2;
        $tableCellPad = $pad + 4;
        $colors = ['#CCCCCC', '#C0C0C0'];
        $tableAttributes = array_merge(
            ['width' => '100%', 'border' => '0', 'cellspacing' => '0', 'cellpadding' => '6'],
            $attributes
        );
        $tableParts = [];
        $instructionsCell = $this->htmlBuilder->getTableCell(
            $instructions,
            false,
            true,
            ['colspan' => '6'],
            $tableCellPad,
            false
        );
        $tableParts[] = $this->htmlBuilder->getTableRowFromCells(
            [$instructionsCell],
            ['style' => 'height: 45px;', 'align' => 'center', 'bgcolor' => '#C0C0C0' ],
            $tableRowPad
        );
        foreach ($proposals as $index => $proposal) {
            $color = $colors[$index % 2];
            $tableParts[] = $this->formBuilder->buildSemesterProposalListFormRow(
                $action,
                $proposal,
                $color,
                $tableRowPad
            );
        }
        return $this->htmlBuilder->getTableFromRows(
            $tableParts,
            $tableAttributes,
            $tablePad
        );
    }

    /**
     * Generates an HTML table layout for confirming a proposal update.
     *
     * Includes instructions, a customizable input field, and a submit button.
     *
     * @param string $instructions  Instructions to display at the top of the table.
     * @param array  $proposal      Array of proposal data (id, code, program number, investigator).
     * @param string $inputField    The input field HTML, customizable per form.
     * @param array  $attributes    [optional] Additional attributes for the <table> element.
     *                               Default is an empty array.
     * @param int    $pad           [optional] Padding level for formatted output. Default is 0.
     *
     * @return string HTML string for the proposal update confirmation table.
     */
    public function buildProposalUpdateConfirmationTable(
        string $instructions,
        array $proposal,
        string $inputField,
        array $attributes = [],
        int $pad = 0
    ): string {
        // Validate proposal array elements
        $this->validateProposalFields($proposal);

        $tablePad = $pad + 2;
        $tableRowPad = $tablePad + 2;
        $tableCellPad = $tableRowPad + 2;
        $buttonPad = $tableCellPad + 2;
        $tableAttributes = array_merge(
            ['width' => '100%', 'border' => '0', 'cellspacing' => '0', 'cellpadding' => '6'],
            $attributes
        );
        $proposalId = HtmlBuildUtility::escape((string) $proposal['ObsApp_id'], false);
        $proposalCode = HtmlBuildUtility::escape((string) $proposal['code'], false);
        $programNumber = HtmlBuildUtility::escape(
            $proposal['semesterYear']
                . $proposal['semesterCode']
                . sprintf("%03d", $proposal['ProgramNumber']),
            false
        );
        $investigator = HtmlBuildUtility::escape('(' . $proposal['InvLastName1'] . ')', false);
        $instructionsCell = $this->htmlBuilder->getTableCell(
            $instructions,
            false,
            true,
            ['colspan' => '7'],
            $tableCellPad,
            true
        );
        $instructionRow = $this->htmlBuilder->getTableRowFromCells(
            [$instructionsCell],
            ['style' => 'height: 32px; background-color: #c0c0c0;'],
            $tableRowPad
        );
        $buttonColor = $proposal['ProgramNumber'] === 0 ? 'lightblue' : 'lightgreen';
        $buttonInputs = $this->formBuilder->buildProposalActionTrigger(
            'confirm',
            'Update',
            $proposalId,
            $buttonColor,
            $buttonPad
        );
        $cells = [
            $this->htmlBuilder->getTableCell(
                '&nbsp;',
                false,
                true,
                ['style' => 'width: 75px;'],
                $tableCellPad,
                true
            ),
            $this->htmlBuilder->getTableCell(
                $buttonInputs,
                false,
                false,
                ['align' => 'right', 'valign' => 'middle', 'style' => 'padding: 0px 5px 0px 5px;'],
                $tableCellPad,
                true
            ),
            $this->htmlBuilder->getTableCell(
                $proposalCode,
                false,
                true,
                ['align' => 'center', 'valign' => 'middle', 'style' => 'padding: 0px 5px 0px 5px;'],
                $tableCellPad,
                true
            ),
            $this->htmlBuilder->getTableCell(
                $inputField,
                false,
                false,
                ['align' => 'center', 'valign' => 'middle', 'style' => 'padding: 0px 5px 0px 5px;'],
                $tableCellPad,
                true
            ),
            $this->htmlBuilder->getTableCell(
                $programNumber,
                false,
                true,
                ['align' => 'left', 'valign' => 'middle', 'style' => 'padding: 0px 5px 0px 5px;'],
                $tableCellPad,
                true
            ),
            $this->htmlBuilder->getTableCell(
                $investigator,
                false,
                true,
                ['align' => 'left', 'valign' => 'middle', 'style' => 'padding: 0px 5px 0px 5px;'],
                $tableCellPad,
                true
            ),
            $this->htmlBuilder->getTableCell(
                '&nbsp;',
                false,
                true,
                ['style' => 'width: 75px;'],
                $tableCellPad,
                true
            ),
        ];
        $proposalRow = $this->htmlBuilder->getTableRowFromCells(
            $cells,
            ['style' => 'height: 32px; background-color: #cccccc;'],
            $tableRowPad
        );
        return $this->htmlBuilder->getTableFromRows(
            [$instructionRow, $proposalRow],
            $tableAttributes,
            $tablePad
        );
    }

    /**
     * Generates an HTML table layout with a textarea input and an optional note.
     *
     * @param string $name       The name attribute for the textarea.
     * @param string $label      The label for the textarea.
     * @param string $value      [optional] Initial value for the textarea. Default is an empty string.
     * @param string $bgColor    Background color for the table rows.
     * @param string $note       [optional] An optional note to display in the table. Default is an empty string.
     * @param int    $pad        [optional] Indentation level for formatted output. Default is 0.
     *
     * @return string HTML string for the textarea table.
     */
    public function buildTextareaTable(
        string $name,
        string $label,
        string $value = '',
        string $bgColor = '',
        string $note = '',
        int $pad = 0
    ): string {
        $tablePad = $pad;
        $tableRowPad = $tablePad + 2;
        $tableCellPad = $tableRowPad + 2;
        $tableAttributes = ['width' => '100%', 'border' => '0', 'cellspacing' => '0', 'cellpadding' => '6'];
        $rowAttributes = ['bgcolor' => $bgColor];
        $textarea = $this->htmlBuilder->getTextarea($name, $value, 10, 56, [], 0, false);
        $rows = [];
        $htmlParts = [];
        $rows[] = $this->htmlBuilder->getTableRowFromArray(
            [$label],
            false,
            [true],
            $rowAttributes,
            $tableRowPad,
            true
        );
        if (!empty($note)) {
            $rows[] = $this->htmlBuilder->getTableRowFromArray(
                [$note],
                false,
                [true],
                $rowAttributes,
                $tableRowPad,
                true
            );
        }
        $rows[] = $this->htmlBuilder->getTableRowFromArray(
            [$textarea],
            false,
            [true],
            $rowAttributes,
            $tableRowPad,
            true
        );
        $htmlParts[] = $this->htmlBuilder->getTableFromRows($rows, $tableAttributes, $tablePad);
        return $this->htmlBuilder->formatParts($htmlParts, $this->formatOutput);
    }

    /**
     * Creates an HTML table layout with a label and content, with configurable layout.
     *
     * Can generate either a single row with both label and content in cells, or two rows,
     * with one row for the label and another for the content.
     *
     * @param string $label         The label text to display in the first cell.
     * @param string $content       The content to display in the second cell.
     * @param string $bgColor       The background color for the table rows.
     * @param bool   $labelRow      [optional] If true, the label appears in its own row. Default is true.
     * @param bool   $inlineLabel   [optional] If true, displays the label as inline content. Default is false.
     * @param bool   $inlineContent [optional] If true, displays the content as inline content. Default is false.
     * @param int    $pad           [optional] The indentation level for formatted output. Default is 0.
     *
     * @return string HTML string for the table containing the label and content.
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
        $tablePad = $pad;
        $tableRowPad = $tablePad + 2;
        $tableCellPad = $tableRowPad + 2;
        $tableAttributes = ['width' => '100%', 'border' => '0', 'cellspacing' => '0', 'cellpadding' => '0'];
        $rowAttributes = ['bgcolor' => $bgColor];
        $cells = [];
        $htmlParts = [];
        $htmlParts[] = $this->htmlBuilder->getTableOpenTag($tableAttributes, $tablePad);
        $labelCell = $this->htmlBuilder->getTableCell(
            $label,
            false,
            $inlineLabel,
            ['width' => '150px'],
            $tableCellPad,
            true
        );
        if ($labelRow) {
            $htmlParts[] = $this->htmlBuilder->getTableRowFromCells(
                [$labelCell],
                $rowAttributes,
                $tableRowPad,
                true
            );
        } else {
            $cells[] = $labelCell;
        }
        $cells[] = $this->htmlBuilder->getTableCell($content, false, $inlineContent, [], $tableCellPad, true);
        $htmlParts[] = $this->htmlBuilder->getTableRowFromCells($cells, $rowAttributes, $tableRowPad, true);
        $htmlParts[] = $this->htmlBuilder->getTableCloseTag($tablePad);
        return $this->htmlBuilder->formatParts($htmlParts, $this->formatOutput);
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
     * @return string HTML string for the observation location table.
     */
    public function buildLabeledRemoteObsTable(
        string $name,
        string $label,
        string $selectedOption,
        string $bgColor,
        bool $position = false,
        int $pad = 0
    ): string {
        $tablePad = $pad;
        $tableRowPad = $tablePad + 2;
        $tableCellPad = $tableRowPad + 2;
        $tableAttributes = ['width' => '100%', 'border' => '0', 'cellspacing' => '0', 'cellpadding' => '6'];
        $rowAttributes = ['bgcolor' => $bgColor];
        $htmlParts = [];
        $labelCell = $this->htmlBuilder->getTableCell($label, false, true, [], $tableCellPad, true);
        $radioCells = [
            $this->htmlBuilder->getTableCell(
                $this->htmlBuilder->getLabeledRadioButton($name, '0', 'checked', $selectedOption, 'Remote', true),
                false,
                true,
                [],
                $tableCellPad,
                true
            ),
            $this->htmlBuilder->getTableCell(
                $this->htmlBuilder->getLabeledRadioButton(
                    $name,
                    '1',
                    'checked',
                    $selectedOption,
                    'Onsite (at the summit)',
                    true
                ),
                false,
                true,
                [],
                $tableCellPad,
                true
            ),
        ];
        $cells = $position ? array_merge([$labelCell], $radioCells) : array_merge($radioCells, [$labelCell]);
        $htmlParts[] = $this->htmlBuilder->getTableOpenTag($tableAttributes, $tablePad);
        $htmlParts[] = $this->htmlBuilder->getTableRowFromCells($cells, $rowAttributes, $tableRowPad, true);
        $htmlParts[] = $this->htmlBuilder->getTableCloseTag($tablePad);
        return $this->htmlBuilder->formatParts($htmlParts, $this->formatOutput);
    }

    /**
     * Generates a rating table with labeled radio buttons for feedback ratings.
     *
     * @param string $name           The name attribute for the radio inputs.
     * @param string $label          The label for the radio buttons.
     * @param string $selectedOption The selected value (rating 1-5).
     * @param string $bgColor        Background color for the table row.
     * @param bool   $addNA          Whether to add an "N/A" option (value 0).
     * @param bool   $labelRow       Whether the label should be on a separate row.
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     *
     * @return string HTML string for the rating table.
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
        $tablePad = $pad;
        $tableRowPad = $tablePad + 2;
        $tableCellPad = $tableRowPad + 2;
        $tableAttributes = ['width' => '100%', 'border' => '0', 'cellspacing' => '0', 'cellpadding' => '6'];
        $rowAttributes = ['bgcolor' => $bgColor];
        $cells = [];
        $htmlParts = [];
        $htmlParts[] = $this->htmlBuilder->getTableOpenTag($tableAttributes, $tablePad);
        if ($labelRow) {
            $colspan = $addNA ? 6 : 5; // Adjust colspan based on whether N/A is added
            $htmlParts[] = $this->htmlBuilder->getTableRowFromCells(
                [$this->htmlBuilder->getTableCell($label, false, true, ['colspan' => $colspan], $tableCellPad, true)],
                $rowAttributes,
                $tableRowPad,
                true
            );
        } else {
            $cells[] = $this->htmlBuilder->getTableCell($label, false, true, ['width' => '100px'], $tableCellPad, true);
        }
        $cells[] = $this->htmlBuilder->getTableCell(
            $this->htmlBuilder->getLabeledRadioButton($name, '5', 'checked', $selectedOption, 'Excellent', true),
            false,
            true,
            [],
            $tableCellPad,
            true
        );
        $cells[] = $this->htmlBuilder->getTableCell(
            $this->htmlBuilder->getLabeledRadioButton($name, '4', 'checked', $selectedOption, 'Very Good', true),
            false,
            true,
            [],
            $tableCellPad,
            true
        );
        $cells[] = $this->htmlBuilder->getTableCell(
            $this->htmlBuilder->getLabeledRadioButton($name, '3', 'checked', $selectedOption, 'Good', true),
            false,
            true,
            [],
            $tableCellPad,
            true
        );
        $cells[] = $this->htmlBuilder->getTableCell(
            $this->htmlBuilder->getLabeledRadioButton($name, '2', 'checked', $selectedOption, 'Fair', true),
            false,
            true,
            [],
            $tableCellPad,
            true
        );
        $cells[] = $this->htmlBuilder->getTableCell(
            $this->htmlBuilder->getLabeledRadioButton($name, '1', 'checked', $selectedOption, 'Poor', true),
            false,
            true,
            [],
            $tableCellPad,
            true
        );
        if ($addNA) {
            $cells[] = $this->htmlBuilder->getTableCell(
                $this->htmlBuilder->getLabeledRadioButton($name, '0', 'checked', $selectedOption, 'N/A', true),
                false,
                true,
                [],
                $tableCellPad,
                true
            );
        }
        $htmlParts[] = $this->htmlBuilder->getTableRowFromCells($cells, $rowAttributes, $tableRowPad, true);
        $htmlParts[] = $this->htmlBuilder->getTableCloseTag($tablePad);
        return $this->htmlBuilder->formatParts($htmlParts, $this->formatOutput);
    }

    /**
     * Generates a table with labeled checkboxes.
     *
     * Displays a label row or label cell with checkboxes in a single row.
     *
     * @param string $name            The base name attribute for each checkbox.
     * @param array  $options         Associative array of checkboxes (value => label).
     * @param array  $selectedOptions Array of selected checkbox values.
     * @param string $label           The label for the table or label cell.
     * @param string $bgColor         Background color for the table row.
     * @param bool   $labelRow        [optional] Whether to add a label row at the top. Default is false.
     * @param int    $pad             [optional] Indentation level for formatted output. Default is 0.
     *
     * @return string HTML string for the labeled checkbox table.
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
        $tablePad = $pad;
        $tableRowPad = $tablePad + 2;
        $tableCellPad = $tableRowPad + 2;
        $tableAttributes = ['width' => '100%', 'border' => '0', 'cellspacing' => '0', 'cellpadding' => '6'];
        $rowAttributes = ['bgcolor' => $bgColor];
        $cells = [];
        $htmlParts = [];

        $htmlParts[] = $this->htmlBuilder->getTableOpenTag($tableAttributes, $tablePad);
        if ($labelRow) {
            $colspan = count($options);
            $htmlParts[] = $this->htmlBuilder->getTableRowFromCells(
                [$this->htmlBuilder->getTableCell($label, false, true, ['colspan' => $colspan], $tableCellPad, true)],
                $rowAttributes,
                $tableRowPad,
                true
            );
        } else {
            $cells[] = $this->htmlBuilder->getTableCell($label, false, true, ['width' => '100px'], $tableCellPad, true);
        }
        foreach ($options as $value => $optionLabel) {
            $isChecked = in_array($value, $selectedOptions) ? 'checked' : '';
            $cells[] = $this->htmlBuilder->getTableCell(
                $this->htmlBuilder->getLabeledCheckbox(
                    $name . '[]',
                    $value,
                    $optionLabel,
                    in_array($value, $selectedOptions),
                    false,
                    true
                ),
                false,
                true,
                [],
                $tableCellPad,
                true
            );
        }
        $htmlParts[] = $this->htmlBuilder->getTableRowFromCells($cells, $rowAttributes, $tableRowPad, true);
        $htmlParts[] = $this->htmlBuilder->getTableCloseTag($tablePad);
        return $this->htmlBuilder->formatParts($htmlParts, $this->formatOutput);
    }

    /**
     * Generates a table with checkboxes only, without a label row.
     *
     * @param string $name            The base name attribute for each checkbox.
     * @param array  $options         Associative array of checkboxes (value => label).
     * @param array  $selectedOptions Array of selected checkbox values.
     * @param string $bgColor         Background color for the table row.
     * @param int    $pad             [optional] Indentation level for formatted output. Default is 0.
     *
     * @return string HTML string for the checkbox-only table.
     */
    public function buildCheckboxTable(
        string $name,
        array $options,
        array $selectedOptions,
        string $bgColor,
        int $pad = 0
    ): string {
        $tablePad = $pad;
        $tableRowPad = $tablePad + 2;
        $tableCellPad = $tableRowPad + 2;
        $tableAttributes = ['width' => '100%', 'border' => '0', 'cellspacing' => '0', 'cellpadding' => '6'];
        $rowAttributes = ['bgcolor' => $bgColor];
        $cells = [];
        $htmlParts = [];
        $htmlParts[] = $this->htmlBuilder->getTableOpenTag($tableAttributes, $tablePad);
        foreach ($options as $value => $optionLabel) {
            $isChecked = in_array($value, $selectedOptions);
            $labeledCheckbox = $this->htmlBuilder->getLabeledCheckbox(
                $name . '[]',
                $value,
                $optionLabel,
                $isChecked,
                false,
                true
            );
            $cells[] = $this->htmlBuilder->getTableCell(
                $labeledCheckbox,
                false,
                true,
                [],
                $tableCellPad,
                true
            );
        }
        $htmlParts[] = $this->htmlBuilder->getTableRowFromCells($cells, $rowAttributes, $tableRowPad, true);
        $htmlParts[] = $this->htmlBuilder->getTableCloseTag($tablePad);

        return $this->htmlBuilder->formatParts($htmlParts, $this->formatOutput);
    }

    public function buildInstrumentCheckboxPulldownTable(
        array $names,
        array $options,
        array $selectedOptions,
        string $bgColor,
        int $pad = 0
    ): string {
        $tablePad = $pad;
        $tableRowPad = $tablePad + 2;
        $tableCellPad = $tableRowPad + 2;
        $pulldownPad = $tableCellPad + 2;
        $tableAttributes = ['width' => '100%', 'border' => '0', 'cellspacing' => '0', 'cellpadding' => '6'];
        $rowAttributes = ['bgcolor' => $bgColor];
        $cells = [];
        $htmlParts = [];
        $htmlParts[] = $this->htmlBuilder->getTableOpenTag($tableAttributes, $tablePad);
        // Facility Instruments Checkboxes
        foreach ($options['facility'] as $value => $optionLabel) {
            $isChecked = in_array($value, $selectedOptions['facility']);
            $labeledCheckbox = $this->htmlBuilder->getLabeledCheckbox(
                $names['facility'] . '[]',
                $value,
                $optionLabel,
                $isChecked,
                false,
                true
            );
            $cells[] = $this->htmlBuilder->getTableCell(
                $labeledCheckbox,
                false,
                true,
                [],
                $tableCellPad,
                true
            );
        }
        $labeledPulldown = $this->htmlBuilder->getLabeledPulldown(
            $names['visitor'],
            $selectedOptions['visitor'],
            $options['visitor'],
            'Visitor Instrument',
            true,
            [],
            $pulldownPad,
            true
        );
        $cells[] = $this->htmlBuilder->getTableCell(
            $labeledPulldown,
            false,
            false,
            [],
            $tableCellPad,
            true
        );
        $htmlParts[] = $this->htmlBuilder->getTableRowFromCells($cells, $rowAttributes, $tableRowPad, true);
        $htmlParts[] = $this->htmlBuilder->getTableCloseTag($tablePad);

        return $this->htmlBuilder->formatParts($htmlParts, $this->formatOutput);
    }

    /**
     * Generates a rating table with radio buttons for feedback ratings.
     *
     * @param string $name           The name attribute for the radio inputs.
     * @param string $selectedOption The selected value (rating 1-5).
     * @param string $bgColor        Background color for the table row.
     * @param bool   $addNA          Whether to add an "N/A" option (value 0).
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     *
     * @return string HTML string for the rating table.
     */
    public function buildRatingTable(
        string $name,
        string $selectedOption,
        string $bgColor,
        bool $addNA = false,
        int $pad = 0
    ): string {
        $tablePad = $pad;
        $tableRowPad = $tablePad + 2;
        $tableCellPad = $tableRowPad + 2;
        $tableAttributes = ['width' => '100%', 'border' => '0', 'cellspacing' => '0', 'cellpadding' => '6'];
        $rowAttributes = ['bgcolor' => $bgColor];
        $cells = [];
        $htmlParts = [];
        $cells = [
            $this->htmlBuilder->getTableCell(
                $this->htmlBuilder->getLabeledRadioButton($name, '5', 'checked', $selectedOption, 'Excellent', true),
                false,
                true,
                [],
                $tableCellPad,
                true
            ),
            $this->htmlBuilder->getTableCell(
                $this->htmlBuilder->getLabeledRadioButton($name, '4', 'checked', $selectedOption, 'Very Good', true),
                false,
                true,
                [],
                $tableCellPad,
                true
            ),
            $this->htmlBuilder->getTableCell(
                $this->htmlBuilder->getLabeledRadioButton($name, '3', 'checked', $selectedOption, 'Good', true),
                false,
                true,
                [],
                $tableCellPad,
                true
            ),
            $this->htmlBuilder->getTableCell(
                $this->htmlBuilder->getLabeledRadioButton($name, '2', 'checked', $selectedOption, 'Fair', true),
                false,
                true,
                [],
                $tableCellPad,
                true
            ),
            $this->htmlBuilder->getTableCell(
                $this->htmlBuilder->getLabeledRadioButton($name, '1', 'checked', $selectedOption, 'Poor', true),
                false,
                true,
                [],
                $tableCellPad,
                true
            ),
        ];
        if ($addNA) {
            $cells[] = $this->htmlBuilder->getTableCell(
                $this->htmlBuilder->getLabeledRadioButton($name, '0', 'checked', $selectedOption, 'N/A', true),
                false,
                true,
                [],
                $tableCellPad,
                true
            );
        }
        $htmlParts = [
            $this->htmlBuilder->getTableOpenTag($tableAttributes, $tablePad),
            $this->htmlBuilder->getTableRowFromCells($cells, $rowAttributes, $tableRowPad, true),
            $this->htmlBuilder->getTableCloseTag($tablePad),
        ];
        return $this->htmlBuilder->formatParts($htmlParts, $this->formatOutput);
    }

    /**
     * Generates a table for selecting the observation location (Remote or Onsite).
     *
     * @param string $name           The name attribute for the radio input.
     * @param string $selectedOption The selected value (0 for Remote, 1 for Onsite).
     * @param string $bgColor        Background color for the table row.
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     *
     * @return string HTML string for the observation location table.
     */
    public function buildRemoteObsTable(
        string $name,
        string $selectedOption,
        string $bgColor,
        bool $position = false,
        int $pad = 0
    ): string {
        $tablePad = $pad;
        $tableRowPad = $tablePad + 2;
        $tableCellPad = $tableRowPad + 2;
        $tableAttributes = ['width' => '100%', 'border' => '0', 'cellspacing' => '0', 'cellpadding' => '6'];
        $rowAttributes = ['bgcolor' => $bgColor];
        $htmlParts = [];
        $cells = [
            $this->htmlBuilder->getTableCell(
                $this->htmlBuilder->getLabeledRadioButton(
                    $name,
                    '0',
                    'checked',
                    $selectedOption,
                    'Remote',
                    true
                ),
                false,
                true,
                [],
                $tableCellPad,
                true
            ),
            $this->htmlBuilder->getTableCell(
                $this->htmlBuilder->getLabeledRadioButton(
                    $name,
                    '1',
                    'checked',
                    $selectedOption,
                    'Onsite (at the summit)',
                    true
                ),
                false,
                true,
                [],
                $tableCellPad,
                true
            ),
        ];
        $htmlParts = [
            $this->htmlBuilder->getTableOpenTag($tableAttributes, $tablePad),
            $this->htmlBuilder->getTableRowFromCells($cells, $rowAttributes, $tableRowPad, true),
            $this->htmlBuilder->getTableCloseTag($tablePad),
        ];
        return $this->htmlBuilder->formatParts($htmlParts, $this->formatOutput);
    }

    /**
     * Generates a date pulldown table with year, month, and day selectors.
     *
     * @param array  $names       Associative array with 'year', 'month', and 'day' as keys.
     * @param string $label       Label for the date pulldown table.
     * @param array  $options     [optional] Array specifying default selected values for 'year', 'month', and 'day'.
     * @param int    $startYear   [optional] Start year for the year pulldown. Default is the current year - 5.
     * @param int    $endYear     [optional] End year for the year pulldown. Default is 3 years from the current year.
     * @param string $bgColor     Background color for the table rows.
     * @param int    $pad         [optional] Indentation level for formatted output. Default is 0.
     *
     * @return string HTML string for the date pulldown table.
     */
    public function buildDatePulldownsTable(
        array $names = [],
        string $label = '',
        array $options = [],
        int $startYear = null,
        int $endYear = null,
        string $bgColor = '',
        int $pad = 0
    ): string {
        $tablePad = $pad;
        $tableRowPad = $tablePad + 2;
        $tableCellPad = $tableRowPad + 2;
        $pulldownPad = $tableCellPad + 2;
        $tableAttributes = ['width' => '100%', 'border' => '0', 'cellspacing' => '0', 'cellpadding' => '0'];
        $rowAttributes = ['bgcolor' => $bgColor];
        $pulldowns = $this->formBuilder->buildDatePulldowns($names, $options, $startYear, $endYear, $pulldownPad);
        $rows = [
            $this->htmlBuilder->getTableRowFromArray([$label], false, [true], $rowAttributes, $tableRowPad, true),
            $this->htmlBuilder->getTableRowFromArray([$pulldowns], false, [false], $rowAttributes, $tableRowPad, true),
        ];
        $htmlParts = [
            $this->htmlBuilder->getTableFromRows($rows, $tableAttributes, $tablePad),
        ];
        return $this->htmlBuilder->formatParts($htmlParts, $this->formatOutput);
    }

    /**
     * Generates an HTML table with two date pulldowns for selecting a start and end date.
     *
     * This method creates a table with two columns, each containing a date pulldown component.
     * Labels for each pulldown are specified in `$labels`.
     *
     * @param array  $startnames   Associative array for the start date pulldown with 'year', 'month', and 'day' keys.
     * @param array  $endnames     Associative array for the end date pulldown with 'year', 'month', and 'day' keys.
     * @param array  $labels       Associative array with 'start' and 'end' labels for each pulldown.
     * @param string $bgColor      Background color for the table rows.
     * @param int    $pad          [optional] Indentation level for formatted output. Default is 0.
     *
     * @return string HTML string for the date range table.
     */
    public function buildDateRangeTable(
        array $startnames,
        array $endnames,
        array $labels,
        array $values,
        string $bgColor,
        int $pad = 0
    ): string {
        $tablePad = $pad;
        $tableRowPad = $tablePad + 2;
        $tableCellPad = $tableRowPad + 2;
        $tableAttributes = ['width' => '100%', 'border' => '0', 'cellspacing' => '0', 'cellpadding' => '0'];
        $rowAttributes = ['bgcolor' => $bgColor];
        $startPulldowns = $this->buildDatePulldownsTable(
            $startnames,
            $labels['start'],
            $values['start'],
            date('Y') - 5,
            date('Y') + 5,
            $bgColor,
            12
        );
        $startCell = $this->htmlBuilder->getTableCell(
            $startPulldowns,
            false,
            false,
            [],
            $tableCellPad,
            true
        );
        $endPulldowns = $this->buildDatePulldownsTable(
            $endnames,
            $labels['end'],
            $values['end'],
            date('Y') - 5,
            date('Y') + 5,
            $bgColor,
            12
        );
        $endCell = $this->htmlBuilder->getTableCell(
            $endPulldowns,
            false,
            false,
            [],
            $tableCellPad,
            true
        );
        $rows = [
            $this->htmlBuilder->getTableRowFromCells(
                [$startCell, $endCell],
                $rowAttributes,
                $tableRowPad
            ),
        ];
        return $this->htmlBuilder->getTableFromRows($rows, $tableAttributes, $tablePad);
    }

    /**
     * Generates a table with three single-digit pulldowns and a label row.
     *
     * Creates a table with a label row and a row containing three pulldown menus
     * for selecting numbers from 0 to 9.
     *
     * @param array  $names       Associative array with [1], [2], and [3] keys for the pulldown `name` attributes.
     * @param string $label       Label for the pulldown table.
     * @param array  $options     [optional] Default selected values for each pulldown, with keys [1], [2], and [3].
     * @param string $bgColor     Background color for the table rows.
     * @param int    $pad         [optional] Indentation level for formatted output. Default is 0.
     *
     * @return string HTML string for the three-number pulldown table.
     */
    public function buildThreeNumberPulldownsTable(
        array $names = [],
        string $label = '',
        array $options = [],
        string $bgColor = '',
        int $pad = 0
    ): string {
        $tablePad = $pad;
        $tableRowPad = $tablePad + 2;
        $tableCellPad = $tableRowPad + 2;
        $pulldownPad = $tableCellPad + 2;
        $tableAttributes = ['width' => '100%', 'border' => '0', 'cellspacing' => '0', 'cellpadding' => '0'];
        $rowAttributes = ['bgcolor' => $bgColor];
        $pulldowns = $this->formBuilder->buildThreeNumberPulldowns($names, $options, $pulldownPad);
        $rows = [
            $this->htmlBuilder->getTableRowFromArray([$label], false, [true], $rowAttributes, $tableRowPad, true),
            $this->htmlBuilder->getTableRowFromArray([$pulldowns], false, [false], $rowAttributes, $tableRowPad, true),
        ];
        $htmlParts = [
            $this->htmlBuilder->getTableFromRows($rows, $tableAttributes, $tablePad),
        ];
        return $this->htmlBuilder->formatParts($htmlParts, $this->formatOutput);
    }

    /**
     * Generates a table with an optional label row and a pulldown menu for selecting a program.
     *
     * Creates a styled HTML table with an optional label and a dropdown menu
     * populated with semester program options. Background color and padding are adjustable.
     *
     * @param string $name            The name attribute for the pulldown element.
     * @param string $label           [optional] The label for the pulldown menu. Default is an empty string.
     * @param array  $programs        Array of programs displayed as pulldown options.
     * @param string $selectedOption  Option to be pre-selected in the pulldown.
     * @param string $bgColor         Background color for the table rows.
     * @param int    $pad             [optional] Padding level for formatted output. Default is 0.
     *
     * @return string HTML string for the programs list pulldown table.
     */
    public function buildProgramsListPulldownTable(
        string $name = '',
        string $label = '',
        array $programs = [],
        string $selectedOption = '',
        string $bgColor = '',
        int $pad = 0
    ): string {
        $tablePad = $pad;
        $tableRowPad = $tablePad + 2;
        $tableCellPad = $tableRowPad + 2;
        $pulldownPad = $tableCellPad + 2;
        $tableAttributes = ['width' => '100%', 'border' => '0', 'cellspacing' => '0', 'cellpadding' => '0'];
        $rowAttributes = ['bgcolor' => $bgColor];
        $pulldown = $this->formBuilder->buildSemesterProgramsPulldown(
            $name,
            $selectedOption,
            $programs,
            $pulldownPad
        );
        $rows = [];
        if ($label !== '') {
            $rows[] = $this->htmlBuilder->getTableRowFromArray(
                [$label],
                false,
                [true],
                $rowAttributes,
                $tableRowPad,
                true
            );
        }
        $rows[] = $this->htmlBuilder->getTableRowFromArray(
            [$pulldown],
            false,
            [false],
            $rowAttributes,
            $tableRowPad,
            true
        );
        return $this->htmlBuilder->formatParts(
            [$this->htmlBuilder->getTableFromRows($rows, $tableAttributes, $tablePad)],
            $this->formatOutput
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
        array $names = [],
        array $labels = [],
        array $programs = [],
        array $options = [],
        string $bgColor = '',
        int $pad = 0
    ): string {
        // Validate proposal array elements
        $this->validateProgramFields($names);
        $this->validateProgramFields($labels);
        $this->validateProgramFields($options);

        $tablePad = $pad;
        $tableRowPad = $tablePad + 2;
        $tableCellPad = $tableRowPad + 2;
        $elementPad = $tableCellPad + 2;
        $tableAttributes = ['width' => '100%', 'border' => '0', 'cellspacing' => '0', 'cellpadding' => '0'];
        $rowAttributes = ['bgcolor' => $bgColor];
        $hiddenElement = $this->buildLabeledElementTable(
            $labels['semester'],
            $this->htmlBuilder->getHiddenInput(
                $names['semester'],
                '2024B', // FIX THIS HARDCODED SEMESTER TAG
                [],
                0,
                false
            ),
            $bgColor,
            true,
            true,
            true,
            $elementPad
        );
        $programsLabel = $labels['programs'];
        $programElement = $this->buildProgramsListPulldownTable(
            $names['programs'],
            '',
            $programs,
            $options['programs'],
            $bgColor,
            $elementPad
        );
        $pulldownElement = $this->buildThreeNumberPulldownsTable(
            $names['pulldowns'],
            $labels['pulldowns'],
            $options['pulldowns'],
            $bgColor,
            $elementPad
        );
        $piElement = $this->buildLabeledElementTable(
            $labels['pi'],
            $this->htmlBuilder->getTextInput(
                $names['pi'],
                $options['pi'],
                10,
                ['maxlength' => '50'],
                0,
                false
            ),
            $bgColor,
            true,
            true,
            true,
            $elementPad
        );
        $programAndPIElementsRow = $this->htmlBuilder->getTableRowFromArray(
            [$pulldownElement, $piElement],
            false,
            [false, false],
            $rowAttributes,
            $tableRowPad,
            true
        );
        $programAndPIElements = $this->htmlBuilder->getTableFromRows(
            [$programAndPIElementsRow],
            $tableAttributes,
            $tablePad
        );
        $rows = [
            $this->htmlBuilder->getTableRowFromArray(
                [$hiddenElement, $programElement],
                false,
                [false, false],
                $rowAttributes,
                $tableRowPad,
                true
            ),
            $this->htmlBuilder->getHorizontalLine(
                false,
                $bgColor,
                2,
                $tableRowPad
            ),
            $this->htmlBuilder->getTableRowFromArray(
                [$programsLabel, $programAndPIElements],
                false,
                [false, false],
                $rowAttributes,
                $tableRowPad,
                true
            ),
        ];
        return $this->htmlBuilder->formatParts(
            [$this->htmlBuilder->getTableFromRows($rows, $tableAttributes, $tablePad)],
            $this->formatOutput
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
     *                         - 'a': Program ID.
     *                         - 'i': Database application ID.
     *                         - 'n': PI's last name.
     *                         - 's': Semester tag (e.g., '2024B').
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
        // Validate proposal array elements
        $this->validateFeedbackProposalFields($program);

        $tablePad = $pad + 2;
        $tableRowPad = $tablePad + 2;
        $tableCellPad = $tableRowPad + 2;
        $tableAttributes = ['width' => '100%', 'border' => '0', 'cellspacing' => '0', 'cellpadding' => '0'];
        $rowAttributes = ['bgcolor' => $bgColor];
        $programNumber = HtmlBuildUtility::escape((string) $proposal, false);
        $investigator = HtmlBuildUtility::escape('(' . $program['n'] . ')', false);
        $hiddenProgram = $this->htmlBuilder->getHiddenInput('a', (string) $program['a'], [], 0, false);
        $hiddenObsAppId = $this->htmlBuilder->getHiddenInput('i', (string) $program['i'], [], 0, false);
        $hiddenPIName = $this->htmlBuilder->getHiddenInput('n', (string) $program['n'], [], 0, false);
        $hiddenSemester = $this->htmlBuilder->getHiddenInput('s', (string) $program['s'], [], 0, false);
        $output = $programNumber . ' ' . $investigator;
        $cells = [
            $this->htmlBuilder->getTableCell('&nbsp;', false, true, [], $tableCellPad, true),
            $this->htmlBuilder->getTableCell($output, false, true, [], $tableCellPad, true),
            $this->htmlBuilder->getTableCell($hiddenProgram, false, true, [], $tableCellPad, true),
            $this->htmlBuilder->getTableCell($hiddenObsAppId, false, true, [], $tableCellPad, true),
            $this->htmlBuilder->getTableCell($hiddenPIName, false, true, [], $tableCellPad, true),
            $this->htmlBuilder->getTableCell($hiddenSemester, false, true, [], $tableCellPad, true),
            $this->htmlBuilder->getTableCell('&nbsp;', false, true, [], $tableCellPad, true),
        ];
        $proposalRow = $this->htmlBuilder->getTableRowFromCells($cells, $rowAttributes, $tableRowPad);
        return $this->htmlBuilder->getTableFromRows([$proposalRow], $tableAttributes, $tablePad);
    }
}
