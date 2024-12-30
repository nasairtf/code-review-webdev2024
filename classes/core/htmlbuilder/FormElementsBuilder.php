<?php

declare(strict_types=1);

namespace App\core\htmlbuilder;

use App\exceptions\HtmlBuilderException;
use App\core\htmlbuilder\HtmlBuildUtility;
use App\core\htmlbuilder\HtmlBuilder;
use App\core\htmlbuilder\BuilderValidationTrait;

/**
 * /home/webdev2024/classes/core/htmlbuilder/FormElementsBuilder.php
 *
 * A utility class responsible for building HTML layout elements with optional formatting.
 *
 * @category Utilities
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class FormElementsBuilder
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
     * Builder object for generating HTML components.
     *
     * @var HtmlBuilder
     */
    private $htmlBuilder;

    /**
     * Constructor to set the formatting preference and HTML builder instance.
     *
     * @param bool        $formatOutput  If true, output will be formatted with indentation.
     * @param HtmlBuilder $htmlBuilder   [optional] An instance of HtmlBuilder. Defaults to a new instance.
     */
    public function __construct(
        ?bool $formatOutput = null,
        ?HtmlBuilder $htmlBuilder = null
    ) {
        $this->formatOutput = $formatOutput ?? false;
        $this->htmlBuilder = $htmlBuilder ?? new HtmlBuilder($formatOutput);
    }

    /**
     * Generates a horizontal line inside a table cell, with specified padding and column span.
     *
     * @param int $colspan [optional] The number of columns to span the line. Default is 1.
     * @param int $pad     [optional] Indentation level for formatted output. Default is 0.
     *
     * @return string HTML for the horizontal line.
     */
    public function buildLineTableCell(
        int $colspan = 1,
        int $pad = 0
    ): string {
        return $this->htmlBuilder->getHorizontalLine(
            false,
            '#FFFFFF',
            $colspan,
            $pad,
            false
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
        $htmlParts = [
            '',
            $this->htmlBuilder->getLine([], $pad),
            '',
        ];
        return $this->htmlBuilder->formatParts($htmlParts, $this->formatOutput);
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
        array $tableAttr = ['border' => '0', 'cellspacing' => '0', 'cellpadding' => '6'],
        int $pad = 0
    ): string {
        // Validate the buttons array
        $this->validateButtons($buttons);

        // Render the buttons table
        $tablePad = $pad;
        $tableRowPad = $tablePad + 2;

        // Create inline display indicators for each button
        $inline = array_fill(0, count($buttons), true);

        // Generate the HTML for the buttons table
        $tableHtml = $this->htmlBuilder->getTableFromRows(
            [
                $this->htmlBuilder->getTableRowFromArray(
                    $buttons,
                    false,
                    $inline,
                    $rowAttr,
                    $tableRowPad,
                    true
                )
            ],
            $tableAttr,
            $tablePad
        );

        // Wrap the table in additional markup for centering and styling
        $htmlParts = [
            '',
            '<!--  Buttons Section  -->',
            '',
            '<center>',
            $tableHtml,
            '</center>',
            '',
        ];

        return $this->htmlBuilder->formatParts($htmlParts, $this->formatOutput);
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
        string $preamble,
        array $rowAttr = [],
        array $tableAttr = ['border' => '0', 'cellspacing' => '0', 'cellpadding' => '6'],
        int $pad = 0
    ): string {
        // Render the preamble table
        $tablePad = $pad;
        $tableRowPad = $tablePad + 2;

        // Generate the HTML for the preamble table
        $tableHtml = $this->htmlBuilder->getTableFromRows(
            [
                $this->htmlBuilder->getTableRowFromArray(
                    [$preamble],
                    false,
                    [false],
                    $rowAttr,
                    $tableRowPad,
                    true
                )
            ],
            $tableAttr,
            $tablePad
        );

        // Wrap the table in additional markup for centering and styling
        $htmlParts = [
            '',
            '<!--  Preamble  -->',
            '',
            '<center>',
            $tableHtml,
            '</center>',
            '',
        ];
        return $this->htmlBuilder->formatParts($htmlParts, $this->formatOutput);
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
        $tablePad = $pad;
        $tableRowPad = $tablePad + 2;

        // Generate rows based on field configurations
        $rows = [];
        foreach ($fieldConfigs as $fieldConfig) {
            // Extract field details with defaults
            $label = $fieldConfig['label'] ?? '';
            $name = $fieldConfig['name'] ?? '';
            $value = $fieldConfig['value'] ?? '';
            $type = $fieldConfig['type'] ?? 'text';
            $inputAttr = $fieldConfig['attr'] ?? [];

            // Generate input field and row
            $inputField = $this->htmlBuilder->getTextInput($name, $value, 10, $inputAttr, 0, false);
            $cells = ['&nbsp;', $label, '&nbsp;', $inputField, '&nbsp;'];
            $rows[] = $this->htmlBuilder->getTableRowFromArray(
                $cells,
                false,
                [true, true, true, true, true],
                $rowAttr,
                $tableRowPad,
                true
            );
        }

        // Generate the table HTML
        $tableHtml = $this->htmlBuilder->getTableFromRows($rows, $tableAttr, $tablePad);

        // Wrap the table in additional markup for centering and styling
        $htmlParts = [
            '',
            '<!--  Input Fields Section  -->',
            '',
            '<center>',
            $tableHtml,
            '</center>',
            '',
        ];

        return $this->htmlBuilder->formatParts($htmlParts, $this->formatOutput);
    }

    /**
     * Generates reset and submit buttons for a form, with a customizable submit button label.
     *
     * @param string $button The label for the submit button.
     * @param int    $pad    [optional] Indentation level for formatted output. Default is 0.
     *
     * @return string HTML for the reset and submit buttons.
     */
    public function buildSemesterChooserActionButtons(
        string $button,
        int $pad = 0
    ): string {
        $buttonPad = $pad;
        $resetButton = $this->htmlBuilder->getResetButton(
            'Reset',
            ['style' => 'width: 120px;'],
            $buttonPad
        );
        $submitButton = $this->htmlBuilder->getSubmitButton(
            $button,
            'Generate',
            ['style' => 'width: 120px;'],
            $buttonPad
        );
        return $this->htmlBuilder->formatParts(
            [$resetButton, $submitButton],
            $this->formatOutput
        );
    }

    /**
     * Generates a hidden input and submit button with a customizable button color.
     *
     * @param string $button   The label for the submit button.
     * @param string $label    The display text on the button.
     * @param string $id       The proposal ID for the hidden input.
     * @param string $color    Background color for the submit button.
     * @param int    $pad      [optional] Indentation level for formatted output. Default is 0.
     *
     * @return string HTML for the hidden input and submit button.
     */
    public function buildProposalActionTrigger(
        string $button,
        string $label,
        string $id,
        string $color,
        int $pad = 0
    ): string {
        $buttonPad = $pad;
        $inputPad = $pad;
        $buttonStyle = "width: 120px; background-color: {$color};";
        $hiddenInput = $this->htmlBuilder->getHiddenInput(
            'i',
            $id,
            [],
            $inputPad
        );
        $submitButton = $this->htmlBuilder->getSubmitButton(
            $button,
            $label,
            ['style' => $buttonStyle],
            $buttonPad
        );
        return $this->htmlBuilder->formatParts(
            [$hiddenInput, $submitButton],
            $this->formatOutput
        );
    }

    /**
     * Wraps a hidden input and submit button inside a form element.
     *
     * @param string $action The form's action URL.
     * @param string $id     The proposal ID for the hidden input.
     * @param string $color  Background color for the submit button.
     * @param int    $pad    [optional] Indentation level for formatted output. Default is 0.
     *
     * @return string HTML for the form.
     */
    public function buildSemesterProposalListButtonForm(
        string $action,
        string $id,
        string $color,
        int $pad = 0
    ): string {
        $triggerPad = $pad + 2;
        $formPad = $pad;
        $html = trim(
            $this->buildProposalActionTrigger(
                'select',
                'Edit',
                $id,
                $color,
                $triggerPad
            )
        );
        $html = $this->htmlBuilder->formatOutput(
            $html,
            $this->formatOutput,
            false,
            2
        );
        return $this->htmlBuilder->getForm(
            $action,
            'post',
            $html,
            ['enctype' => 'multipart/form-data', 'target' => '_blank', 'style' => 'margin: 0px; padding: 0px;'],
            $formPad,
            true
        );
    }

    /**
     * Generates a table row for a proposal lister, including a form for editing proposals.
     *
     * @param string $action   The form's action URL.
     * @param array  $proposal Array containing proposal data (id, code, program number, investigator).
     * @param string $bgColor  Background color for the row.
     * @param int    $pad      [optional] Indentation level for formatted output. Default is 0.
     *
     * @return string HTML for the proposal lister row.
     *
     * @throws HtmlBuilderException If the proposal array is missing required entries.
     */
    public function buildSemesterProposalListFormRow(
        string $action,
        array $proposal,
        string $bgColor,
        int $pad = 0
    ): string {
        // Validate proposal array elements
        $this->validateProposalFields($proposal);

        $cellPad = $pad + 2;
        $formPad = $pad + 4;
        $tableRowPad = $pad;
        $proposalId = HtmlBuildUtility::escape((string) $proposal['ObsApp_id'], false);
        $proposalCode = HtmlBuildUtility::escape((string) $proposal['code'], false);
        $programNumber = HtmlBuildUtility::escape(
            $proposal['semesterYear']
                . $proposal['semesterCode']
                . sprintf("%03d", $proposal['ProgramNumber']),
            false
        );
        $investigator = HtmlBuildUtility::escape('(' . $proposal['InvLastName1'] . ')', false);
        $buttonColor = $proposal['ProgramNumber'] === 0 ? 'lightblue' : 'lightgreen';
        $editForm = $this->buildSemesterProposalListButtonForm(
            $action,
            $proposalId,
            $buttonColor,
            $formPad
        );
        $cells = [
            $this->htmlBuilder->getTableCell(
                '&nbsp;',
                false,
                true,
                ['style' => 'width: 75px;'],
                $cellPad,
                true
            ),
            $this->htmlBuilder->getTableCell(
                $editForm,
                false,
                false,
                ['align' => 'right', 'valign' => 'middle', 'style' => 'padding: 0px 5px 0px 5px;'],
                $cellPad,
                true
            ),
            $this->htmlBuilder->getTableCell(
                $proposalCode,
                false,
                true,
                ['align' => 'center', 'valign' => 'middle', 'style' => 'padding: 0px 5px 0px 5px;'],
                $cellPad,
                true
            ),
            $this->htmlBuilder->getTableCell(
                $programNumber,
                false,
                true,
                ['align' => 'left', 'valign' => 'middle', 'style' => 'padding: 0px 5px 0px 5px;'],
                $cellPad,
                true
            ),
            $this->htmlBuilder->getTableCell(
                $investigator,
                false,
                true,
                ['align' => 'left', 'valign' => 'middle', 'style' => 'padding: 0px 5px 0px 5px;'],
                $cellPad,
                true
            ),
            $this->htmlBuilder->getTableCell(
                '&nbsp;',
                false,
                true,
                ['style' => 'width: 75px;'],
                $cellPad,
                true
            ),
        ];
        return $this->htmlBuilder->getTableRowFromCells(
            $cells,
            ['style' => 'height: 32px; background-color: ' . $bgColor],
            $tableRowPad
        );
    }

    /**
     * Creates a set of pulldown menus for selecting a date (year, month, and day).
     *
     * @param array $names     Associative array with 'year', 'month', and 'day' keys for pulldown name attributes.
     * @param array $options   [optional] Array with 'year', 'month', and 'day' keys for default selections.
     * @param int   $startYear [optional] Start year for year pulldown. Defaults to current year - 5.
     * @param int   $endYear   [optional] End year for year pulldown. Defaults to current year + 3.
     * @param int   $pad       [optional] Indentation level for formatted output. Default is 0.
     *
     * @return string HTML for the date pulldown set.
     */
    public function buildDatePulldowns(
        array $names,
        array $options = [],
        int $startYear = null,
        int $endYear = null,
        int $pad = 0
    ): string {
        $currentYear = date('Y');
        $startYear = $startYear ?? $currentYear - 5;
        $endYear = $endYear ?? 0;
        $selectedYear = (string) ($options['year'] ?? date('Y'));
        $selectedMonth = (string) ($options['month'] ?? date('n'));
        $selectedDay = (string) ($options['day'] ?? date('j'));

        $yearPulldown = $this->htmlBuilder->getYearsPulldown(
            $names['year'],
            $selectedYear,
            $startYear,
            $endYear,
            [],
            $pad,
            false
        );
        $monthPulldown = $this->htmlBuilder->getShortMonthNamesPulldown(
            $names['month'],
            $selectedMonth,
            [],
            $pad,
            false
        );
        $dayPulldown = $this->htmlBuilder->getDaysOfMonthPulldown(
            $names['day'],
            $selectedDay,
            false,
            [],
            $pad,
            false
        );
        return $this->htmlBuilder->formatParts(
            [$monthPulldown, $dayPulldown, $yearPulldown],
            $this->formatOutput
        );
    }

    /**
     * Generates three pulldown menus for selecting single-digit numbers (0 to 9).
     *
     * @param array $names     Array specifying names for each pulldown with keys [1], [2], and [3].
     * @param array $options   [optional] Array with default selected values for each pulldown.
     * @param int   $pad       [optional] Indentation level for formatted output. Default is 0.
     *
     * @return string HTML for the three-number pulldown set.
     */
    public function buildThreeNumberPulldowns(
        array $names,
        array $options = [],
        int $pad = 0
    ): string {
        $selectedFirst = (string) ($options[0] ?? 0);
        $selectedSecond = (string) ($options[1] ?? 0);
        $selectedThird = (string) ($options[2] ?? 0);
        $firstPulldown = $this->htmlBuilder->getNumbersPulldown(
            $names[0],
            $selectedFirst,
            0,
            9,
            false,
            [],
            $pad,
            false
        );
        $secondPulldown = $this->htmlBuilder->getNumbersPulldown(
            $names[1],
            $selectedSecond,
            0,
            9,
            false,
            [],
            $pad,
            false
        );
        $thirdPulldown = $this->htmlBuilder->getNumbersPulldown(
            $names[2],
            $selectedThird,
            0,
            9,
            false,
            [],
            $pad,
            false
        );
        return $this->htmlBuilder->formatParts(
            [$firstPulldown, $secondPulldown, $thirdPulldown],
            $this->formatOutput
        );
    }

    /**
     * Generates a pulldown menu for selecting a semester program.
     *
     * @param string $name           The name attribute for the dropdown.
     * @param string $selectedOption The option to pre-select in the dropdown.
     * @param array  $options        Associative array of options (label => program ID).
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     *
     * @return string HTML for the semester program pulldown.
     */
    public function buildSemesterProgramsPulldown(
        string $name,
        string $selectedOption,
        array $options,
        int $pad = 0
    ): string {
        $pulldown = $this->htmlBuilder->getPulldown(
            $name,
            $selectedOption,
            $options,
            [],
            $pad,
            false
        );
        return $this->htmlBuilder->formatParts(
            [$pulldown],
            $this->formatOutput
        );
    }
}
