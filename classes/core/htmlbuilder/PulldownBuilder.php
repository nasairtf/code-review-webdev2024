<?php

namespace App\core\htmlbuilder;

/**
 * /home/webdev2024/classes/core/htmlbuilder/PulldownBuilder.php
 *
 * A utility class responsible for building assorted HTML pulldowns with optional formatting.
 *
 * @category Utilities
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class PulldownBuilder
{
    /**
     * Whether to format the HTML output (indent and add line breaks).
     *
     * @var bool
     */
    private $formatOutput;

    /**
     * Constructor to set the formatting preference.
     *
     * @param bool $formatOutput Whether to format the HTML (indentation, line breaks).
     */
    public function __construct(bool $formatOutput = false)
    {
        $this->formatOutput = $formatOutput;
    }

    /**
     * Builds the HTML for a <select> element with specified options, attributes, and selected value(s).
     *
     * @param string       $name           The name attribute for the <select> element.
     * @param array        $options        An associative array of options (key => value).
     * @param string|array $selectedOption The selected option(s); single string for a single-select or an array for multi-select.
     * @param array        $attributes     [optional] Additional attributes for the <select> element. Default is an empty array.
     * @param int          $pad            [optional] Indentation level for formatted output. Default is 0.
     * @param bool         $isHtml         [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The HTML for the <select> element, with options rendered and the selected option(s) highlighted.
     */
    private function buildElement(
        string $name,
        array $options,
        ?string $selectedOption = null,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        $escapedName = HtmlBuildUtility::escape($name, $isHtml);
        $selectTag = sprintf(
            '<select name="%s"%s>',
            $escapedName,
            HtmlBuildUtility::buildAttributes($attributes)
        );
        $html = HtmlBuildUtility::formatOutput($selectTag, $this->formatOutput, false, $pad);
        foreach ($options as $key => $value) {
            $escapedOptionKey = HtmlBuildUtility::escape($key, $isHtml);
            $escapedOptionValue = HtmlBuildUtility::escape($value, $isHtml);
            $isSelected = ((string)$key === (string)$selectedOption) ? ' selected' : '';
            $option = sprintf(
                '<option value="%s"%s>%s</option>',
                $escapedOptionKey,
                $isSelected,
                $escapedOptionValue
            );
            $html .= HtmlBuildUtility::formatOutput($option, $this->formatOutput, true, $pad + 2);
        }
        $html .= HtmlBuildUtility::formatOutput('</select>', $this->formatOutput, true, $pad);
        return $html;
    }

    /**
     * Generates a generic dropdown (select box) HTML element.
     *
     * @param string $name           The name attribute for the <select> element.
     * @param string $selectedOption The value of the option that should be pre-selected.
     * @param array  $options        An associative array of options (key = value attribute, value = display text).
     * @param array  $attributes     [optional] Additional attributes for the <select> element. Default is an empty array.
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml         [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The HTML for the pulldown.
     */
    public function getPulldown(
        string $name,
        string $selectedOption,
        array $options,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->buildElement($name, $options, $selectedOption, $attributes, $pad, $isHtml);
    }

    /**
     * Generates a multi-select dropdown HTML element.
     *
     * @param string $name           The name attribute for the <select> element.
     * @param array  $selectedOptions An array of options that should be pre-selected.
     * @param array  $options        An associative array of options (key = value attribute, value = display text).
     * @param array  $attributes     [optional] Additional attributes for the <select> element. Default is an empty array.
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml         [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The HTML for the multi-select pulldown.
     */
    public function getMultiSelectPulldown(
        string $name,
        array $selectedOptions,
        array $options,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        $attributes['multiple'] = 'multiple';
        return $this->buildElement($name, $options, $selectedOption, $attributes, $pad, $isHtml);
    }

    /**
     * Generates a dropdown with grouped options (optgroup).
     *
     * @param string $name           The name attribute for the <select> element.
     * @param string $selectedOption The value of the option that should be pre-selected.
     * @param array  $groups         An associative array of groups, where each key is the group label and the value is an associative array of options.
     * @param array  $attributes     [optional] Additional attributes for the <select> element. Default is an empty array.
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml         [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The HTML for the grouped pulldown.
     */
    public function getGroupedPulldown(
        string $name,
        string $selectedOption,
        array $groups,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        $escapedName = HtmlBuildUtility::escape($name, $isHtml);
        $selectTag = sprintf(
            '<select name="%s"%s>',
            $escapedName,
            HtmlBuildUtility::buildAttributes($attributes)
        );
        $html = HtmlBuildUtility::formatOutput($selectTag, $this->formatOutput, false, $pad);
        foreach ($groups as $groupLabel => $options) {
            $escapedLabel = HtmlBuildUtility::escape($groupLabel, $isHtml);
            $optgroup = sprintf('<optgroup label="%s">', $escapedLabel);
            $html .= HtmlBuildUtility::formatOutput($optgroup, $this->formatOutput, true, $pad + 2);
            foreach ($options as $key => $value) {
                $escapedOptionKey = HtmlBuildUtility::escape($key, $isHtml);
                $escapedOptionValue = HtmlBuildUtility::escape($value, $isHtml);
                $isSelected = ($key === $selectedOption) ? ' selected' : '';
                $option = sprintf(
                    '<option value="%s"%s>%s</option>',
                    $escapedOptionKey,
                    $isSelected,
                    $escapedOptionValue
                );
                $html .= HtmlBuildUtility::formatOutput($option, $this->formatOutput, true, $pad + 4);
            }
            $html .= HtmlBuildUtility::formatOutput('</optgroup>', $this->formatOutput, true, $pad + 2);
        }
        $html .= HtmlBuildUtility::formatOutput('</select>', $this->formatOutput, true, $pad);
        return $html;
    }

    /**
     * Generates a disabled dropdown.
     *
     * @param string $name           The name attribute for the <select> element.
     * @param array  $options        An associative array of options (key = value attribute, value = display text).
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml         [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The HTML for the disabled pulldown.
     */
    public function getDisabledPulldown(
        string $name,
        array $options,
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->getPulldown($name, '', $options, ['disabled' => 'disabled'], $pad, $isHtml);
    }

    /**
     * Generates a numeric range dropdown (select box) HTML element.
     *
     * @param string $name           The name attribute for the <select> element.
     * @param string $selectedOption The selected number in the range.
     * @param int    $start          The starting number.
     * @param int    $end            The ending number.
     * @param bool   $zeroPadded     [optional] Whether the numbers should be zero-padded. Default is false.
     * @param array  $attributes     [optional] Additional attributes for the <select> element. Default is an empty array.
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml         [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The HTML for the numbers pulldown.
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
        $options = [];
        $padLength = $zeroPadded ? strlen((string) $end) : 0;
        for ($i = $start; $i <= $end; $i++) {
            $options[$i] = $zeroPadded
                ? HtmlBuildUtility::padLeftZero((string)$i, $padLength)
                : (string)$i;
        }
        return $this->getPulldown($name, $selectedOption, $options, $attributes, $pad, $isHtml);
    }

    /**
     * Generates a Yes/No dropdown (select box) HTML element.
     *
     * @param string $name           The name attribute for the <select> element.
     * @param string $selectedOption The selected option (1 for Yes, 0 for No).
     * @param array  $attributes     [optional] Additional attributes for the <select> element. Default is an empty array.
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml         [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The HTML for the Yes/No pulldown.
     */
    public function getYesNoPulldown(
        string $name,
        string $selectedOption,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        $options = ['1' => 'Yes', '0' => 'No'];
        return $this->getPulldown($name, $selectedOption, $options, $attributes, $pad, $isHtml);
    }

    /**
     * Generates a semester dropdown (select box) HTML element.
     *
     * @param string $name           The name attribute for the <select> element.
     * @param string $selectedOption The selected semester (A for Spring, B for Fall).
     * @param array  $attributes     [optional] Additional attributes for the <select> element. Default is an empty array.
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml         [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The HTML for the semester pulldown.
     */
    public function getSemestersPulldown(
        string $name,
        string $selectedOption,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        $options = ['A' => 'Spring', 'B' => 'Fall'];
        return $this->getPulldown($name, $selectedOption, $options, $attributes, $pad, $isHtml);
    }

    /**
     * Generates a years range dropdown (select box) HTML element.
     *
     * @param string $name           The name attribute for the <select> element.
     * @param string $selectedOption The selected year in the range.
     * @param int    $startYear      The starting year.
     * @param int    $endYear        [optional] The ending year. Default is 0 (3 years from the current year).
     * @param array  $attributes     [optional] Additional attributes for the <select> element. Default is an empty array.
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml         [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The HTML for the years pulldown.
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
        $endYear = $endYear ?: (int)date('Y') + 3;
        return $this->getNumbersPulldown($name, $selectedOption, $startYear, $endYear, false, $attributes, $pad, $isHtml);
    }

    /**
     * Generates a numeric months (1-12|01-12) dropdown (select box) HTML element.
     *
     * @param string $name           The name attribute for the <select> element.
     * @param string $selectedOption The selected month.
     * @param bool   $zeroPadded     [optional] Whether the months should be zero-padded (e.g., 01, 02, ...). Default is false.
     * @param array  $attributes     [optional] Additional attributes for the <select> element. Default is an empty array.
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml         [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The HTML for the numeric months pulldown.
     */
    public function getMonthsPulldown(
        string $name,
        string $selectedOption,
        bool $zeroPadded = false,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->getNumbersPulldown($name, $selectedOption, 1, 12, $zeroPadded, $attributes, $pad, $isHtml);
    }

    /**
     * Generates a short month names (Jan, Feb, etc.) dropdown (select box) HTML element.
     *
     * @param string $name           The name attribute for the <select> element.
     * @param string $selectedOption The selected month.
     * @param array  $attributes     [optional] Additional attributes for the <select> element. Default is an empty array.
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml         [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The HTML for the short month names pulldown.
     */
    public function getShortMonthNamesPulldown(
        string $name,
        string $selectedOption,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        $options = [
            '1' => 'Jan', '2' => 'Feb', '3' => 'Mar', '4' => 'Apr', '5' => 'May',
            '6' => 'Jun', '7' => 'Jul', '8' => 'Aug', '9' => 'Sep', '10' => 'Oct',
            '11' => 'Nov', '12' => 'Dec'
        ];
        return $this->getPulldown($name, $selectedOption, $options, $attributes, $pad, $isHtml);
    }

    /**
     * Generates a full month names (January, February, etc.) dropdown (select box) HTML element.
     *
     * @param string $name           The name attribute for the <select> element.
     * @param string $selectedOption The selected month.
     * @param array  $attributes     [optional] Additional attributes for the <select> element. Default is an empty array.
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml         [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The HTML for the full month names pulldown.
     */
    public function getFullMonthNamesPulldown(
        string $name,
        string $selectedOption,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        $options = [
            '1' => 'January', '2' => 'February', '3' => 'March', '4' => 'April', '5' => 'May',
            '6' => 'June', '7' => 'July', '8' => 'August', '9' => 'September', '10' => 'October',
            '11' => 'November', '12' => 'December'
        ];
        return $this->getPulldown($name, $selectedOption, $options, $attributes, $pad, $isHtml);
    }

    /**
     * Generates a days in the month (1-31|01-31) dropdown (select box) HTML element.
     *
     * @param string $name           The name attribute for the <select> element.
     * @param string $selectedOption The selected day.
     * @param bool   $zeroPadded     [optional] Whether the days should be zero-padded (e.g., 01, 02, ...). Default is false.
     * @param array  $attributes     [optional] Additional attributes for the <select> element. Default is an empty array.
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml         [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The HTML for the days in a month pulldown.
     */
    public function getDaysOfMonthPulldown(
        string $name,
        string $selectedOption,
        bool $zeroPadded = false,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->getNumbersPulldown($name, $selectedOption, 1, 31, $zeroPadded, $attributes, $pad, $isHtml);
    }

    /**
     * Generates a numeric days of the week (1-7|01-07) dropdown (select box) HTML element.
     *
     * @param string $name           The name attribute for the <select> element.
     * @param string $selectedOption The selected day.
     * @param bool   $zeroPadded     [optional] Whether the days should be zero-padded (e.g., 01 for Monday). Default is false.
     * @param array  $attributes     [optional] Additional attributes for the <select> element. Default is an empty array.
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml         [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The HTML for the days of the week pulldown.
     */
    public function getDaysOfWeekPulldown(
        string $name,
        string $selectedOption,
        bool $zeroPadded = false,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->getNumbersPulldown($name, $selectedOption, 1, 7, $zeroPadded, $attributes, $pad, $isHtml);
    }

    /**
     * Generates a short day names (Mon, Tue, etc.) dropdown (select box) HTML element.
     *
     * @param string $name           The name attribute for the <select> element.
     * @param string $selectedOption The selected day of the week.
     * @param array  $attributes     [optional] Additional attributes for the <select> element. Default is an empty array.
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml         [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The HTML for the short day names pulldown.
     */
    public function getShortDayNamesPulldown(
        string $name,
        string $selectedOption,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        $options = [
            '1' => 'Mon', '2' => 'Tue', '3' => 'Wed', '4' => 'Thu',
            '5' => 'Fri', '6' => 'Sat', '7' => 'Sun'
        ];
        return $this->getPulldown($name, $selectedOption, $options, $attributes, $pad, $isHtml);
    }

    /**
     * Generates a full day names (Monday, Tuesday, etc.) dropdown (select box) HTML element.
     *
     * @param string $name           The name attribute for the <select> element.
     * @param string $selectedOption The selected day of the week.
     * @param array  $attributes     [optional] Additional attributes for the <select> element. Default is an empty array.
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml         [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The HTML for the full day names pulldown.
     */
    public function getFullDayNamesPulldown(
        string $name,
        string $selectedOption,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        $options = [
            '1' => 'Monday', '2' => 'Tuesday', '3' => 'Wednesday', '4' => 'Thursday',
            '5' => 'Friday', '6' => 'Saturday', '7' => 'Sunday'
        ];
        return $this->getPulldown($name, $selectedOption, $options, $attributes, $pad, $isHtml);
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
     * @param bool   $isHtml         [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
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
        $escapedLabel = HtmlBuildUtility::escape($label, $isHtml);
        $pulldownTag = $this->getPulldown($name, $selectedOption, $options, $attributes, $pad, $isHtml);
        $labelTag = sprintf('<label>%s</label>', $escapedLabel);
        $html = $labelAfter
            ? $pulldownTag . ' ' . $labelTag
            : $labelTag . ' ' . $pulldownTag;
        return HtmlBuildUtility::formatOutput($html, $this->formatOutput, false, $pad);
    }
}
