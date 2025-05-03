<?php

declare(strict_types=1);

namespace App\core\htmlbuilder;

use App\core\htmlbuilder\HtmlBuildUtility;
use App\core\htmlbuilder\BaseHtmlBuilder;
use App\core\htmlbuilder\ButtonBuilder;
use App\core\htmlbuilder\CheckboxBuilder;
use App\core\htmlbuilder\PulldownBuilder;
use App\core\htmlbuilder\RadioBuilder;
use App\core\htmlbuilder\TableBuilder;
use App\core\htmlbuilder\TextBuilder;

/**
 * Provides a unified interface for generating common HTML elements.
 *
 * This utility class simplifies the creation of HTML components such as
 * forms, tables, input fields, buttons, and dropdowns. It utilizes specialized
 * builder classes (e.g., TableBuilder, TextBuilder) to handle the details
 * of generating clean, reusable HTML code with optional formatting.
 *
 * Features:
 * - Encapsulation of HTML generation logic.
 * - Support for formatted or raw HTML output.
 * - Extensibility via dependency injection of specific builders.
 *
 * @category Utilities
 * @package  App\Core\HtmlBuilder
 * @author
 * @license  MIT License
 * @version  1.0.0
 */

class HtmlBuilder
{
    // ============================================================================
    // BASE BUILDER CLASS METHODS
    // ============================================================================

    //** BaseHtmlBuilder **//
    // public function getBreak(...) {}
    // public function getLine(...) {}
    // public function getLink(...) {}
    // public function getEmailLink(...) {}
    // public function getFileInput(...) {}
    // public function getImage(...) {}
    // public function getLabel(...) {}
    // public function getParagraph(...) {}
    // public function getSpan(...) {}
    // public function getForm(...) {}
    // public function getHeading(...) {}
    // public function getList(...) {}
    // public function getNavBar(...) {}
    use HtmlBuilderBaseHtmlBuilderTrait;

    //** ButtonBuilder **//
    // public function getButton(...) {}
    // public function getSubmitButton(...) {}
    // public function getDisabledButton(...) {}
    // public function getResetButton(...) {}
    // public function getLinkButton(...) {}
    // public function getIconButton(...) {}
    // public function getButtonGroup(...) {}
    // public function getConfirmButton(...) {}
    // public function getDropdownButton(...) {}
    use HtmlBuilderButtonBuilderTrait;

    //** CheckboxBuilder **//
    // public function getCheckbox(...) {}
    // public function getCustomCheckbox(...) {}
    // public function getLabeledCheckbox(...) {}
    // public function getCheckboxGroup(...) {}
    // public function getDisabledCheckboxGroup(...) {}
    // public function getHiddenCheckbox(...) {}
    use HtmlBuilderCheckboxBuilderTrait;

    //** PulldownBuilder **//
    // public function getPulldown(...) {}
    // public function getMultiSelectPulldown(...) {}
    // public function getGroupedPulldown(...) {}
    // public function getDisabledPulldown(...) {}
    // public function getNumbersPulldown(...) {}
    // public function getYesNoPulldown(...) {}
    // public function getSemestersPulldown(...) {}
    // public function getYearsPulldown(...) {}
    // public function getMonthsPulldown(...) {}
    // public function getShortMonthNamesPulldown(...) {}
    // public function getFullMonthNamesPulldown(...) {}
    // public function getDaysOfMonthPulldown(...) {}
    // public function getDaysOfWeekPulldown(...) {}
    // public function getShortDayNamesPulldown(...) {}
    // public function getFullDayNamesPulldown(...) {}
    // public function getLabeledPulldown(...) {}
    use HtmlBuilderPulldownBuilderTrait;

    //** RadioBuilder *//
    // public function getRadioButton(...) {}
    // public function getLabeledRadioButton(...) {}
    // public function getRadioButtonWithID(...) {}
    // public function getRadioGroupWithInlineOption(...) {}
    // public function getDisabledRadioGroup(...) {}
    // public function getRadioGroup(...) {}
    use HtmlBuilderRadioBuilderTrait;

    //** TableBuilder **//
    // public function getTableOpenTag(...) {}
    // public function getTableCloseTag(...) {}
    // public function getTableFromRows(...) {}
    // public function getTableFromArray(...) {}
    // public function getSingleColumnTable(...) {}
    // public function getHorizontalLine(...) {}
    // public function getTableCell(...) {}
    // public function getTableRowFromArray(...) {}
    // public function getTableRowFromCells(...) {}
    // public function getSingleCellRow(...) {}
    // public function getTableRowFromArrayWithAlternatingColor(...) {}
    // public function getTableRowFromArrayWithStatusColor(...) {}
    use HtmlBuilderTableBuilderTrait;

    //** TextBuilder **//
    // public function getTextarea(...) {}
    // public function getTextInput(...) {}
    // public function getPasswordInput(...) {}
    // public function getEmailInput(...) {}
    // public function getNumberInput(...) {}
    // public function getUnixTimestampInput(...) {}
    // public function getUrlInput(...) {}
    // public function getHiddenInput(...) {}
    // public function getSearchInput(...) {}
    // public function getTelInput(...) {}
    use HtmlBuilderTextBuilderTrait;

    //** HtmlBuildUtility **//
    // public function formatOutput(...) {}
    // public function formatParts(...) {}
    // public function escape(...) {}
    use HtmlBuilderHtmlBuildUtilityTrait;

    /**
     * Whether to format the HTML output (indent and add line breaks).
     *
     * @var bool
     */
    private $formatOutput;

    /**
     * Various builder objects for generating HTML components.
     *
     * @var BaseHtmlBuilder
     * @var ButtonBuilder
     * @var CheckboxBuilder
     * @var PulldownBuilder
     * @var RadioBuilder
     * @var TableBuilder
     * @var TextBuilder
     */
    private $baseHtmlBuilder;
    private $buttonBuilder;
    private $checkboxBuilder;
    private $pulldownBuilder;
    private $radioBuilder;
    private $tableBuilder;
    private $textBuilder;

    /**
     * Initializes the HtmlBuilder class with optional dependencies and configuration.
     *
     * This constructor allows for dependency injection of specific builder instances
     * or automatic initialization of default builders. It also supports a global flag
     * for enabling formatted output, which applies indentation and line breaks to
     * the generated HTML.
     *
     * @param bool|null            $formatOutput     If true, enables formatted output with indentation
     *                                               and line breaks.
     * @param BaseHtmlBuilder|null $baseHtmlBuilder [optional] Custom instance of BaseHtmlBuilder.
     *                                               Defaults to a new instance.
     * @param ButtonBuilder|null   $buttonBuilder   [optional] Custom instance of ButtonBuilder.
     *                                               Defaults to a new instance.
     * @param CheckboxBuilder|null $checkboxBuilder [optional] Custom instance of CheckboxBuilder.
     *                                               Defaults to a new instance.
     * @param PulldownBuilder|null $pulldownBuilder [optional] Custom instance of PulldownBuilder.
     *                                               Defaults to a new instance.
     * @param RadioBuilder|null    $radioBuilder    [optional] Custom instance of RadioBuilder.
     *                                               Defaults to a new instance.
     * @param TableBuilder|null    $tableBuilder    [optional] Custom instance of TableBuilder.
     *                                               Defaults to a new instance.
     * @param TextBuilder|null     $textBuilder     [optional] Custom instance of TextBuilder.
     *                                               Defaults to a new instance.
     */
    public function __construct(
        ?bool $formatOutput = null,
        ?BaseHtmlBuilder $baseHtmlBuilder = null,
        ?ButtonBuilder $buttonBuilder = null,
        ?CheckboxBuilder $checkboxBuilder = null,
        ?PulldownBuilder $pulldownBuilder = null,
        ?RadioBuilder $radioBuilder = null,
        ?TableBuilder $tableBuilder = null,
        ?TextBuilder $textBuilder = null
    ) {
        $this->formatOutput = $formatOutput ?? false;
        $this->baseHtmlBuilder = $baseHtmlBuilder ?? new BaseHtmlBuilder($formatOutput);
        $this->buttonBuilder = $buttonBuilder ?? new ButtonBuilder($formatOutput);
        $this->checkboxBuilder = $checkboxBuilder ?? new CheckboxBuilder($formatOutput);
        $this->pulldownBuilder = $pulldownBuilder ?? new PulldownBuilder($formatOutput);
        $this->radioBuilder = $radioBuilder ?? new RadioBuilder($formatOutput);
        $this->tableBuilder = $tableBuilder ?? new TableBuilder($formatOutput);
        $this->textBuilder = $textBuilder ?? new TextBuilder($formatOutput);
    }
}
