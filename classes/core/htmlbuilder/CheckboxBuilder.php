<?php

namespace App\core\htmlbuilder;

/**
 * /home/webdev2024/classes/core/htmlbuilder/CheckboxBuilder.php
 *
 * A utility class responsible for building assorted HTML checkboxes with optional formatting.
 *
 * @category Utilities
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class CheckboxBuilder
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
     * Builds a checkbox input HTML element with specific attributes.
     *
     * @param string      $name       The name attribute for the checkbox input.
     * @param string      $value      The value attribute for the checkbox input.
     * @param bool        $isChecked  Whether the checkbox is checked.
     * @param bool        $isDisabled Whether the checkbox is disabled.
     * @param array       $attributes Additional attributes for the checkbox input.
     * @param bool        $isHtml     Whether to skip escaping if content is HTML.
     *
     * @return string The HTML for the checkbox element.
     */
    private function buildElement(
        string $name,
        string $value,
        bool $isChecked,
        bool $isDisabled,
        array $attributes,
        bool $isHtml
    ): string {
        $escapedName = HtmlBuildUtility::escape($name, $isHtml);
        $escapedValue = HtmlBuildUtility::escape($value, $isHtml);
        $checkedAttribute = $isChecked ? ' checked' : '';
        $disabledAttribute = $isDisabled ? ' disabled' : '';
        $attributesString = HtmlBuildUtility::buildAttributes($attributes);
        return sprintf(
            '<input type="checkbox" name="%s" value="%s"%s%s%s />',
            $escapedName,
            $escapedValue,
            $checkedAttribute,
            $disabledAttribute,
            $attributesString
        );
    }

    /**
     * Generates a generic checkbox HTML element with values of "on" or "off".
     *
     * @param string $name           The name attribute for the checkbox input.
     * @param string $value          [optional] The value attribute for the checkbox input. Default is 'on'.
     * @param bool   $isChecked      [optional] Whether the checkbox is checked. Default is false.
     * @param bool   $isDisabled     [optional] Whether the checkbox is disabled. Default is false.
     * @param array  $attributes     [optional] Additional attributes for the checkbox input. Default is an empty array.
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml         [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The HTML for the checkbox element.
     */
    public function getCheckbox(
        string $name,
        string $value = 'on',
        bool $isChecked = false,
        bool $isDisabled = false,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        $html = $this->buildElement($name, $value, $isChecked, $isDisabled, $attributes, $isHtml);
        return HtmlBuildUtility::formatOutput($html, $this->formatOutput, false, $pad);
    }

    /**
     * Generates a customizable checkbox with values other than "on/off".
     *
     * @param string $name           The name attribute for the checkbox input.
     * @param string $value          The value attribute for the checkbox input.
     * @param string $data           The data used to determine whether the checkbox is checked.
     * @param bool   $isDisabled     [optional] Whether the checkbox is disabled. Default is false.
     * @param array  $attributes     [optional] Additional attributes for the checkbox input. Default is an empty array.
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml         [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The HTML for the checkbox element.
     */
    public function getCustomCheckbox(
        string $name,
        string $value,
        string $data,
        bool $isDisabled = false,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        $isChecked = ($data === $value);
        return $this->getCheckbox($name, $value, $isChecked, $isDisabled, $attributes, $pad, $isHtml);
    }

    /**
     * Generates a checkbox with an associated label element.
     *
     * @param string $name           The name attribute for the checkbox input.
     * @param string $value          The value attribute for the checkbox input.
     * @param string $label          The label to be displayed next to the checkbox.
     * @param bool   $isChecked      [optional] Whether the checkbox is checked. Default is false.
     * @param bool   $isDisabled     [optional] Whether the checkbox is disabled. Default is false.
     * @param bool   $labelAfter     [optional] Whether the label should appear after the checkbox. Default is true.
     * @param array  $attributes     [optional] Additional attributes for the checkbox input. Default is an empty array.
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml         [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The HTML for the labeled checkbox element.
     */
    public function getLabeledCheckbox(
        string $name,
        string $value,
        string $label,
        bool $isChecked = false,
        bool $isDisabled = false,
        bool $labelAfter = true,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        $escapedLabel = HtmlBuildUtility::escape($label, $isHtml);
        $checkboxTag = $this->getCheckbox($name, $value, $isChecked, $isDisabled, $attributes, $pad, $isHtml);
        $labelTag = sprintf('<label>%s</label>', $escapedLabel);
        $html = $labelAfter
            ? $checkboxTag . ' ' . $labelTag
            : $labelTag . ' ' . $checkboxTag;
        return HtmlBuildUtility::formatOutput($html, $this->formatOutput, false, $pad);
    }

    /**
     * Generates a group of checkboxes from an associative array of options.
     *
     * @param string $name           The name attribute for the checkbox inputs.
     * @param array  $selectedValues An array of values that should be pre-selected (checked).
     * @param array  $options        An associative array of options (key => value).
     * @param array  $attributes     [optional] Additional attributes for the checkbox inputs. Default is an empty array.
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml         [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The HTML for the group of checkboxes.
     */
    public function getCheckboxGroup(
        string $name,
        array $selectedValues,
        array $options,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        $html = '';
        foreach ($options as $value => $label) {
            $isChecked = in_array($value, $selectedValues);
            $checkboxTag = $this->getLabeledCheckbox($name, $value, $label, $isChecked, false, true, $attributes, $pad, $isHtml);
            $html .= HtmlBuildUtility::formatOutput($checkboxTag, $this->formatOutput, false, $pad);
        }
        return $html;
    }

    /**
     * Generates a group of disabled checkboxes.
     *
     * @param string $name           The name attribute for the checkbox inputs.
     * @param array  $options        An associative array of options (key => value).
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml         [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The HTML for the group of disabled checkboxes.
     */
    public function getDisabledCheckboxGroup(
        string $name,
        array $options,
        int $pad = 0,
        bool $isHtml = false
    ): string {
        $html = '';
        foreach ($options as $value => $label) {
            $checkboxTag = $this->getLabeledCheckbox($name, $value, $label, false, true, true, [], $pad, $isHtml);
            $html .= HtmlBuildUtility::formatOutput($checkboxTag, $this->formatOutput, false, $pad);
        }
        return $html;
    }

    /**
     * Generates a hidden checkbox element.
     *
     * @param string $name           The name attribute for the hidden checkbox.
     * @param string $value          [optional] The value attribute for the hidden checkbox. Default is 'on'.
     * @param bool   $isChecked      [optional] Whether the checkbox is checked. Default is false.
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml         [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The HTML for the hidden checkbox element.
     */
    public function getHiddenCheckbox(
        string $name,
        string $value = 'on',
        bool $isChecked = false,
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->getCheckbox($name, $value, $isChecked, false, ['style' => 'display:none;'], $pad, $isHtml);
    }
}
