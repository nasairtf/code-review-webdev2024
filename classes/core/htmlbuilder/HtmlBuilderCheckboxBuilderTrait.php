<?php

declare(strict_types=1);

namespace App\core\htmlbuilder;

/**
 * Wrapper for HTML checkbox generation methods.
 *
 * Exposes a high-level interface for creating individual checkboxes,
 * checkbox groups, labeled or hidden checkboxes, and custom checkbox
 * configurations using the CheckboxBuilder class.
 *
 * This trait is used by HtmlBuilder to support form component rendering
 * with semantic labeling and data-driven selections.
 *
 * @see CheckboxBuilder
 */
trait HtmlBuilderCheckboxBuilderTrait
{
    /**
     * Generates a generic checkbox HTML element using CheckboxBuilder.
     *
     * @param string $name       The name attribute for the checkbox input.
     * @param string $value      [optional] The value attribute for the checkbox input. Default is 'on'.
     * @param bool   $isChecked  [optional] Whether the checkbox is checked. Default is false.
     * @param bool   $isDisabled [optional] Whether the checkbox is disabled. Default is false.
     * @param array  $attributes [optional] Additional attributes for the checkbox input. Default is an empty array.
     * @param int    $pad        [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml     [optional] If true, content is treated as pre-escaped HTML. Default is false.
     *
     * @return string The generated HTML for the checkbox element.
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
        return $this->checkboxBuilder->getCheckbox(
            $name,
            $value,
            $isChecked,
            $isDisabled,
            $attributes,
            $pad,
            $isHtml
        );
    }

    /**
     * Generates a customizable checkbox using CheckboxBuilder.
     *
     * @param string $name       The name attribute for the checkbox input.
     * @param string $value      The value attribute for the checkbox input.
     * @param string $data       The data used to determine whether the checkbox is checked.
     * @param bool   $isDisabled [optional] Whether the checkbox is disabled. Default is false.
     * @param array  $attributes [optional] Additional attributes for the checkbox input. Default is an empty array.
     * @param int    $pad        [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml     [optional] If true, content is treated as pre-escaped HTML. Default is false.
     *
     * @return string The generated HTML for the custom checkbox.
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
        return $this->checkboxBuilder->getCustomCheckbox(
            $name,
            $value,
            $data,
            $isDisabled,
            $attributes,
            $pad,
            $isHtml
        );
    }

    /**
     * Generates a labeled checkbox using CheckboxBuilder.
     *
     * @param string $name       The name attribute for the checkbox input.
     * @param string $value      The value attribute for the checkbox input.
     * @param string $label      The label to be displayed next to the checkbox.
     * @param bool   $isChecked  [optional] Whether the checkbox is checked. Default is false.
     * @param bool   $isDisabled [optional] Whether the checkbox is disabled. Default is false.
     * @param bool   $labelAfter [optional] Whether the label should appear after the checkbox. Default is true.
     * @param array  $attributes [optional] Additional attributes for the checkbox input. Default is an empty array.
     * @param int    $pad        [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml     [optional] If true, content is treated as pre-escaped HTML. Default is false.
     *
     * @return string The generated HTML for the labeled checkbox.
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
        return $this->checkboxBuilder->getLabeledCheckbox(
            $name,
            $value,
            $label,
            $isChecked,
            $isDisabled,
            $labelAfter,
            $attributes,
            $pad,
            $isHtml
        );
    }

    /**
     * Generates a group of checkboxes using CheckboxBuilder.
     *
     * @param string $name           The name attribute for the checkbox inputs.
     * @param array  $selectedValues An array of values that should be pre-selected (checked).
     * @param array  $options        An associative array of options (key => value).
     * @param array  $attributes     [optional] Additional attributes for the checkbox inputs.
     *                                Default is an empty array.
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml         [optional] If true, content is treated as pre-escaped HTML. Default is false.
     *
     * @return string The generated HTML for the group of checkboxes.
     */
    public function getCheckboxGroup(
        string $name,
        array $selectedValues,
        array $options,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->checkboxBuilder->getCheckboxGroup(
            $name,
            $selectedValues,
            $options,
            $attributes,
            $pad,
            $isHtml
        );
    }

    /**
     * Generates a group of disabled checkboxes using CheckboxBuilder.
     *
     * @param string $name      The name attribute for the checkbox inputs.
     * @param array  $options   An associative array of options (key => value).
     * @param int    $pad       [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml    [optional] If true, content is treated as pre-escaped HTML. Default is false.
     *
     * @return string The generated HTML for the disabled checkbox group.
     */
    public function getDisabledCheckboxGroup(
        string $name,
        array $options,
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->checkboxBuilder->getDisabledCheckboxGroup(
            $name,
            $options,
            $pad,
            $isHtml
        );
    }

    /**
     * Generates a hidden checkbox using CheckboxBuilder.
     *
     * @param string $name      The name attribute for the hidden checkbox.
     * @param string $value     [optional] The value attribute for the hidden checkbox. Default is 'on'.
     * @param bool   $isChecked [optional] Whether the checkbox is checked. Default is false.
     * @param int    $pad       [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml    [optional] If true, content is treated as pre-escaped HTML. Default is false.
     *
     * @return string The generated HTML for the hidden checkbox.
     */
    public function getHiddenCheckbox(
        string $name,
        string $value = 'on',
        bool $isChecked = false,
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->checkboxBuilder->getHiddenCheckbox(
            $name,
            $value,
            $isChecked,
            $pad,
            $isHtml
        );
    }
}
