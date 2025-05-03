<?php

declare(strict_types=1);

namespace App\core\htmlbuilder;

/**
 * Wrapper for HTML dropdown (select box) generation methods.
 *
 * Provides access to a variety of select box builders, including grouped
 * selects, multi-selects, and labeled pulldowns with support for date,
 * time, and semantic option grouping using PulldownBuilder.
 *
 * This trait allows HtmlBuilder to standardize dropdown behavior and
 * labeling across user interfaces.
 *
 * @see PulldownBuilder
 */
trait HtmlBuilderPulldownBuilderTrait
{
    /**
     * Generates a generic dropdown (select box) using PulldownBuilder.
     *
     * @param string $name           The name attribute for the <select> element.
     * @param string $selectedOption The value of the option that should be pre-selected.
     * @param array  $options        An associative array of options (key = value attribute, value = display text).
     * @param array  $attributes     [optional] Additional attributes for the <select> element.
     *                                Default is an empty array.
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml         [optional] If true, content is treated as pre-escaped HTML. Default is false.
     *
     * @return string The generated HTML for the dropdown.
     */
    public function getPulldown(
        string $name,
        string $selectedOption,
        array $options,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->pulldownBuilder->getPulldown(
            $name,
            $selectedOption,
            $options,
            $attributes,
            $pad,
            $isHtml
        );
    }

    /**
     * Generates a multi-select dropdown using PulldownBuilder.
     *
     * @param string $name           The name attribute for the <select> element.
     * @param array  $selectedOptions An array of options that should be pre-selected.
     * @param array  $options        An associative array of options (key = value attribute, value = display text).
     * @param array  $attributes     [optional] Additional attributes for the <select> element.
     *                                Default is an empty array.
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml         [optional] If true, content is treated as pre-escaped HTML. Default is false.
     *
     * @return string The generated HTML for the multi-select dropdown.
     */
    public function getMultiSelectPulldown(
        string $name,
        array $selectedOptions,
        array $options,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->pulldownBuilder->getMultiSelectPulldown(
            $name,
            $selectedOptions,
            $options,
            $attributes,
            $pad,
            $isHtml
        );
    }

    /**
     * Generates a grouped dropdown (with <optgroup>) using PulldownBuilder.
     *
     * @param string $name           The name attribute for the <select> element.
     * @param string $selectedOption The value of the option that should be pre-selected.
     * @param array  $groups         An associative array of groups, where each key is the group label and the
     *                                value is an array of options.
     * @param array  $attributes     [optional] Additional attributes for the <select> element.
     *                                Default is an empty array.
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml         [optional] If true, content is treated as pre-escaped HTML. Default is false.
     *
     * @return string The generated HTML for the grouped pulldown.
     */
    public function getGroupedPulldown(
        string $name,
        string $selectedOption,
        array $groups,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->pulldownBuilder->getGroupedPulldown(
            $name,
            $selectedOption,
            $groups,
            $attributes,
            $pad,
            $isHtml
        );
    }

    /**
     * Generates a disabled dropdown using PulldownBuilder.
     *
     * @param string $name   The name attribute for the <select> element.
     * @param array  $options An associative array of options (key = value attribute, value = display text).
     * @param int    $pad    [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml [optional] If true, content is treated as pre-escaped HTML. Default is false.
     *
     * @return string The generated HTML for the disabled dropdown.
     */
    public function getDisabledPulldown(
        string $name,
        array $options,
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->pulldownBuilder->getDisabledPulldown(
            $name,
            $options,
            $pad,
            $isHtml
        );
    }

    /**
     * Generates a numeric range dropdown using PulldownBuilder.
     *
     * @param string $name           The name attribute for the <select> element.
     * @param string $selectedOption The selected number in the range.
     * @param int    $start          The starting number.
     * @param int    $end            The ending number.
     * @param bool   $zeroPadded     [optional] Whether the numbers should be zero-padded. Default is false.
     * @param array  $attributes     [optional] Additional attributes for the <select> element.
     *                                Default is an empty array.
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml         [optional] If true, content is treated as pre-escaped HTML. Default is false.
     *
     * @return string The generated HTML for the numbers pulldown.
     */
    public function getNumbersPulldown(
        string $name,
        string $selectedOption,
        int $start,
        int $end,
        bool $zeroPadded = false,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->pulldownBuilder->getNumbersPulldown(
            $name,
            $selectedOption,
            $start,
            $end,
            $zeroPadded,
            $attributes,
            $pad,
            $isHtml
        );
    }

    /**
     * Generates a Yes/No dropdown using PulldownBuilder.
     *
     * @param string $name           The name attribute for the <select> element.
     * @param string $selectedOption The selected option (1 for Yes, 0 for No).
     * @param array  $attributes     [optional] Additional attributes for the <select> element.
     *                                Default is an empty array.
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml         [optional] If true, content is treated as pre-escaped HTML. Default is false.
     *
     * @return string The generated HTML for the Yes/No pulldown.
     */
    public function getYesNoPulldown(
        string $name,
        string $selectedOption,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->pulldownBuilder->getYesNoPulldown(
            $name,
            $selectedOption,
            $attributes,
            $pad,
            $isHtml
        );
    }

    /**
     * Generates a semester dropdown (Spring/Fall) using PulldownBuilder.
     *
     * @param string $name           The name attribute for the <select> element.
     * @param string $selectedOption The selected semester (A for Spring, B for Fall).
     * @param array  $attributes     [optional] Additional attributes for the <select> element.
     *                                Default is an empty array.
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml         [optional] If true, content is treated as pre-escaped HTML. Default is false.
     *
     * @return string The generated HTML for the semester pulldown.
     */
    public function getSemestersPulldown(
        string $name,
        string $selectedOption,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->pulldownBuilder->getSemestersPulldown(
            $name,
            $selectedOption,
            $attributes,
            $pad,
            $isHtml
        );
    }

    /**
     * Generates a years range dropdown using PulldownBuilder.
     *
     * @param string $name           The name attribute for the <select> element.
     * @param string $selectedOption The selected year in the range.
     * @param int    $startYear      The starting year.
     * @param int    $endYear        [optional] The ending year. Default is 3 years from the current year.
     * @param array  $attributes     [optional] Additional attributes for the <select> element.
     *                                Default is an empty array.
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml         [optional] If true, content is treated as pre-escaped HTML. Default is false.
     *
     * @return string The generated HTML for the years pulldown.
     */
    public function getYearsPulldown(
        string $name,
        string $selectedOption,
        int $startYear,
        int $endYear = 0,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->pulldownBuilder->getYearsPulldown(
            $name,
            $selectedOption,
            $startYear,
            $endYear,
            $attributes,
            $pad,
            $isHtml
        );
    }

    /**
     * Generates a numeric months dropdown (1-12|01-12) using PulldownBuilder.
     *
     * @param string $name           The name attribute for the <select> element.
     * @param string $selectedOption The selected month.
     * @param bool   $zeroPadded     [optional] Whether the months should be zero-padded (e.g., 01, 02, ...).
     *                                Default is false.
     * @param array  $attributes     [optional] Additional attributes for the <select> element.
     *                                Default is an empty array.
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml         [optional] If true, content is treated as pre-escaped HTML. Default is false.
     *
     * @return string The generated HTML for the numeric months pulldown.
     */
    public function getMonthsPulldown(
        string $name,
        string $selectedOption,
        bool $zeroPadded = false,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->pulldownBuilder->getMonthsPulldown(
            $name,
            $selectedOption,
            $zeroPadded,
            $attributes,
            $pad,
            $isHtml
        );
    }

    /**
     * Generates a short month names dropdown (Jan, Feb, etc.) using PulldownBuilder.
     *
     * @param string $name           The name attribute for the <select> element.
     * @param string $selectedOption The selected month.
     * @param array  $attributes     [optional] Additional attributes for the <select> element.
     *                                Default is an empty array.
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml         [optional] If true, content is treated as pre-escaped HTML. Default is false.
     *
     * @return string The generated HTML for the short month names pulldown.
     */
    public function getShortMonthNamesPulldown(
        string $name,
        string $selectedOption,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->pulldownBuilder->getShortMonthNamesPulldown(
            $name,
            $selectedOption,
            $attributes,
            $pad,
            $isHtml
        );
    }

    /**
     * Generates a full month names dropdown (January, February, etc.) using PulldownBuilder.
     *
     * @param string $name           The name attribute for the <select> element.
     * @param string $selectedOption The selected month.
     * @param array  $attributes     [optional] Additional attributes for the <select> element.
     *                                Default is an empty array.
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml         [optional] If true, content is treated as pre-escaped HTML. Default is false.
     *
     * @return string The generated HTML for the full month names pulldown.
     */
    public function getFullMonthNamesPulldown(
        string $name,
        string $selectedOption,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->pulldownBuilder->getFullMonthNamesPulldown(
            $name,
            $selectedOption,
            $attributes,
            $pad,
            $isHtml
        );
    }

    /**
     * Generates a days in a month dropdown (1-31|01-31) using PulldownBuilder.
     *
     * @param string $name           The name attribute for the <select> element.
     * @param string $selectedOption The selected day.
     * @param bool   $zeroPadded     [optional] Whether the days should be zero-padded (e.g., 01, 02, ...).
     *                                Default is false.
     * @param array  $attributes     [optional] Additional attributes for the <select> element.
     *                                Default is an empty array.
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml         [optional] If true, content is treated as pre-escaped HTML. Default is false.
     *
     * @return string The generated HTML for the days pulldown.
     */
    public function getDaysOfMonthPulldown(
        string $name,
        string $selectedOption,
        bool $zeroPadded = false,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->pulldownBuilder->getDaysOfMonthPulldown(
            $name,
            $selectedOption,
            $zeroPadded,
            $attributes,
            $pad,
            $isHtml
        );
    }

    /**
     * Generates a numeric days of the week dropdown (1-7|01-07) using PulldownBuilder.
     *
     * @param string $name           The name attribute for the <select> element.
     * @param string $selectedOption The selected day.
     * @param bool   $zeroPadded     [optional] Whether the days should be zero-padded (e.g., 01 for Monday).
     *                                Default is false.
     * @param array  $attributes     [optional] Additional attributes for the <select> element.
     *                                Default is an empty array.
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml         [optional] If true, content is treated as pre-escaped HTML. Default is false.
     *
     * @return string The generated HTML for the days of the week pulldown.
     */
    public function getDaysOfWeekPulldown(
        string $name,
        string $selectedOption,
        bool $zeroPadded = false,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->pulldownBuilder->getDaysOfWeekPulldown(
            $name,
            $selectedOption,
            $zeroPadded,
            $attributes,
            $pad,
            $isHtml
        );
    }

    /**
     * Generates a short day names dropdown (Mon, Tue, etc.) using PulldownBuilder.
     *
     * @param string $name           The name attribute for the <select> element.
     * @param string $selectedOption The selected day of the week.
     * @param array  $attributes     [optional] Additional attributes for the <select> element.
     *                                Default is an empty array.
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml         [optional] If true, content is treated as pre-escaped HTML. Default is false.
     *
     * @return string The generated HTML for the short day names pulldown.
     */
    public function getShortDayNamesPulldown(
        string $name,
        string $selectedOption,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->pulldownBuilder->getShortDayNamesPulldown(
            $name,
            $selectedOption,
            $attributes,
            $pad,
            $isHtml
        );
    }

    /**
     * Generates a full day names dropdown (Monday, Tuesday, etc.) using PulldownBuilder.
     *
     * @param string $name           The name attribute for the <select> element.
     * @param string $selectedOption The selected day of the week.
     * @param array  $attributes     [optional] Additional attributes for the <select> element.
     *                                Default is an empty array.
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml         [optional] If true, content is treated as pre-escaped HTML. Default is false.
     *
     * @return string The generated HTML for the full day names pulldown.
     */
    public function getFullDayNamesPulldown(
        string $name,
        string $selectedOption,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->pulldownBuilder->getFullDayNamesPulldown(
            $name,
            $selectedOption,
            $attributes,
            $pad,
            $isHtml
        );
    }

    /**
     * Generates a dropdown (pulldown) with an associated label element.
     *
     * @param string $name           The name attribute for the dropdown.
     * @param string $selectedOption The value of the option to be selected by default.
     * @param array  $options        Array of options for the dropdown, where keys are option values and
     *                               values are the displayed text.
     * @param string $label          The label text displayed with the dropdown.
     * @param bool   $labelAfter     [optional] If true, the label appears after the dropdown. Default is true.
     * @param array  $attributes     [optional] Additional attributes for the dropdown. Default is an empty array.
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml         [optional] If true, content is treated as pre-escaped HTML. Default is false.
     *
     * @return string The HTML for the labeled dropdown element.
     */
    public function getLabeledPulldown(
        string $name,
        string $selectedOption,
        array $options,
        string $label,
        bool $labelAfter = true,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->pulldownBuilder->getLabeledPulldown(
            $name,
            $selectedOption,
            $options,
            $label,
            $labelAfter,
            $attributes,
            $pad,
            $isHtml
        );
    }
}
