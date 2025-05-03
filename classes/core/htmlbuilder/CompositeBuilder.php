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
    // ============================================================================
    // COMPOSITE BUILDER CLASS METHODS
    // ============================================================================

    //** FormElementsBuilder **//
    // public function buildLineTableCell(...) {}
    // public function buildPageSectionBreak(...) {}
    // public function buildPageSection(...) {}
    // public function buildButtonsFormSection(...) {}
    // public function buildPreambleFormSection(...) {}
    // public function buildInputFieldsFormSection(...) {}
    // public function buildSemesterChooserActionButtons(...) {}
    // public function buildProposalActionTrigger(...) {}
    // public function buildSemesterProposalListButtonForm(...) {}
    // public function buildSemesterProposalListPdfLink(...) {}
    // public function buildSemesterProposalListFormRow(...) {}
    // public function buildSemesterProposalListPageRow(...) {}
    // public function buildDatePulldowns(...) {}
    // public function buildThreeNumberPulldowns(...) {}
    // public function buildSemesterProgramsPulldown(...) {}
    use CompositeBuilderFormElementsBuilderTrait;

    //** TableLayoutBuilder **//
    // public function buildMessagePageTable(...) {}
    // public function buildMessagesPageTable(...) {}
    // public function buildSemesterChooserTable(...) {}
    // public function buildSemesterChooserPulldownsTable(...) {}
    // public function buildSemesterProposalListTable(...) {}
    // public function buildSemesterProposalListPageTable(...) {}
    // public function buildProposalUpdateConfirmationTable(...) {}
    // public function buildTextareaTable(...) {}
    // public function buildLabeledElementTable(...) {}
    // public function buildLabeledRemoteObsTable(...) {}
    // public function buildLabeledRatingTable(...) {}
    // public function buildLabeledCheckboxTable(...) {}
    // public function buildCheckboxTable(...) {}
    // public function buildInstrumentCheckboxPulldownTable(...) {}
    // public function buildRatingTable(...) {}
    // public function buildRemoteObsTable(...) {}
    // public function buildDatePulldownsTable(...) {}
    // public function buildDateRangeTable(...) {}
    // public function buildThreeNumberPulldownsTable(...) {}
    // public function buildProgramsListPulldownTable(...) {}
    // public function buildProgramPulldownPINameTable(...) {}
    // public function buildSingleProposalTable(...) {}
    use CompositeBuilderTableLayoutBuilderTrait;

    //** LayoutBuilder *//
    // public function buildResultsPage(...) {}
    // public function buildResultsBlockPage(...) {}
    // public function buildErrorPage(...) {}
    // public function buildSemesterChooserForm(...) {}
    // public function buildSemesterProposalListForm(...) {}
    // public function buildSemesterProposalListPage(...) {}
    // public function buildProposalUpdateConfirmationForm(...) {}
    use CompositeBuilderLayoutBuilderTrait;

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
}
