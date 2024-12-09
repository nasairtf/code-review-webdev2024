<?php

declare(strict_types=1);

namespace App\core\htmlbuilder;

use App\core\htmlbuilder\HtmlBuildUtility;

/**
 * /home/webdev2024/classes/core/htmlbuilder/RadioBuilder.php
 *
 * A utility class for generating HTML radio buttons with optional labels and formatting.
 *
 * Features include:
 * - Single radio buttons with attributes and labels.
 * - Groups of radio buttons, including inline layouts and disabled states.
 * - Custom formatting options, such as indentation and line breaks.
 *
 * @category Utilities
 * @package  IRTF
 * @version  1.0.0
 * @since    1.0.0
 */

class RadioBuilder
{
    /**
     * Whether to format the HTML output (indent and add line breaks).
     *
     * @var bool
     */
    private $formatOutput;

    /**
     * Initializes the RadioBuilder with a specified formatting preference.
     *
     * @param bool|null $formatOutput [optional] Whether to format the HTML with indentation and line breaks.
     *                                 Defaults to false if not specified.
     */
    public function __construct(
        ?bool $formatOutput = null
    ) {
        $this->formatOutput = $formatOutput ?? false;
    }

    /**
     * Generates a single radio button.
     *
     * @param string $name           The name attribute for the radio input.
     * @param string $value          The value attribute for the radio input.
     * @param string $status         Status attributes (e.g., "checked", "disabled").
     * @param string $data           The value to match for the checked status.
     * @param array  $attributes     [optional] Additional attributes for the radio input. Default is an empty array.
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml         [optional] If true, content is treated as pre-escaped HTML. Default is false.
     *
     * @return string The formatted radio input.
     */
    public function getRadioButton(
        string $name,
        string $value,
        string $status,
        string $data,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        $html = $this->buildElement(
            $name,
            $value,
            $data,
            $status,
            $attributes,
            $isHtml
        );
        return HtmlBuildUtility::formatOutput(
            $html,
            $this->formatOutput,
            false,
            $pad
        );
    }

    /**
     * Generates a single radio button with an optional label.
     *
     * @param string $name           The name attribute for the radio input.
     * @param string $value          The value attribute for the radio input.
     * @param string $status         The status of the radio button (e.g., "checked", "disabled").
     * @param string $data           The data used to determine whether the radio button is checked.
     * @param string|null $label     [optional] Optional label for the radio button. Default is null.
     * @param bool   $labelAfter     [optional] Whether to place the label after the radio button. Default is true.
     * @param array  $attributes     [optional] Additional attributes for the radio input. Default is an empty array.
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml         [optional] If true, content is treated as pre-escaped HTML. Default is false.
     *
     * @return string The HTML for the radio button with label.
     */
    public function getLabeledRadioButton(
        string $name,
        string $value,
        string $status,
        string $data,
        ?string $label = null,
        bool $labelAfter = true,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        $radioTag = $this->getRadioButton(
            $name,
            $value,
            $status,
            $data,
            $attributes,
            0,
            $isHtml
        );
        $labelTag = $label
            ? HtmlBuildUtility::escape($label, $isHtml)
            : '';
        $html = $labelAfter
            ? $radioTag . ' ' . $labelTag
            : $labelTag . ' ' . $radioTag;
        return HtmlBuildUtility::formatOutput(
            $html,
            $this->formatOutput,
            false,
            $pad
        );
    }

    /**
     * Generates a radio button with a unique ID for label association.
     *
     * @param string     $name       The name attribute for the radio input.
     * @param string     $value      The value attribute for the radio input.
     * @param string     $status     The status of the radio button (e.g., "checked", "disabled").
     * @param string     $data       The data used to determine whether the radio button is checked.
     * @param string|null $id        [optional] Optional unique ID for the radio button. Default is null.
     * @param array      $attributes [optional] Additional attributes for the radio input. Default is an empty array.
     * @param int        $pad        [optional] Indentation level for formatted output. Default is 0.
     * @param bool       $isHtml     [optional] If true, content is treated as pre-escaped HTML. Default is false.
     *
     * @return string The HTML for the radio button element.
     */
    public function getRadioButtonWithID(
        string $name,
        string $value,
        string $status,
        string $data,
        ?string $id = null,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        if ($id) {
            $attributes['id'] = $id;
        }
        return $this->getRadioButton(
            $name,
            $value,
            $status,
            $data,
            $attributes,
            $pad,
            $isHtml
        );
    }

    /**
     * Generates a group of radio buttons with an option for inline layout.
     *
     * Each radio button is paired with its corresponding label. The layout can be inline or block. If `$options`
     * is empty, the method returns an empty string.
     *
     * @param string $name           The `name` attribute for the radio input.
     * @param string $selectedOption The value of the option to pre-select.
     * @param array  $options        An associative array of options (key => value) for the radio buttons.
     * @param bool   $inline         [optional] Whether to display the radio buttons inline. Default is false.
     * @param array  $attributes     [optional] Additional attributes for the radio inputs. Default is an empty array.
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml         [optional] If true, content is treated as pre-escaped HTML. Default is false.
     *
     * @return string The HTML for the group of radio buttons. Returns an empty string if no options are provided.
     */
    public function getRadioGroupWithInlineOption(
        string $name,
        string $selectedOption,
        array $options,
        bool $inline = false,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        $html = '';
        foreach ($options as $value => $label) {
            $radioButton = $this->getRadioButton(
                $name,
                $value,
                'checked',
                $selectedOption,
                $attributes,
                $pad,
                $isHtml
            );
            $escapedLabel = HtmlBuildUtility::escape($label, $isHtml);
            $html .= HtmlBuildUtility::formatOutput(
                $radioButton . ' ' . $escapedLabel,
                $this->formatOutput,
                false,
                $pad
            );
            $html .= $this->formatOutput
                ? ($inline ? '' : PHP_EOL)
                : ($inline ? ' ' : '');
        }
        return $html;
    }

    /**
     * Generates a group of disabled radio buttons.
     *
     * @param string $name           The name attribute for the radio input.
     * @param array  $options        An associative array of options (key => value).
     * @param array  $attributes     [optional] Additional attributes for the radio inputs. Default is an empty array.
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml         [optional] If true, content is treated as pre-escaped HTML. Default is false.
     *
     * @return string The HTML for the disabled radio buttons group.
     */
    public function getDisabledRadioGroup(
        string $name,
        array $options,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        $html = '';
        foreach ($options as $value => $label) {
            $radioButton = $this->getRadioButton(
                $name,
                $value,
                'disabled',
                '',
                $attributes,
                $pad,
                $isHtml
            );
            $escapedLabel = HtmlBuildUtility::escape($label, $isHtml);
            $html .= HtmlBuildUtility::formatOutput(
                $radioButton . ' ' . $escapedLabel,
                $this->formatOutput,
                false,
                $pad
            );
        }
        return $html;
    }

    /**
     * Generates a group of radio buttons from an associative array of options.
     *
     * @param string $name           The name attribute for the radio input.
     * @param string $selectedOption The value of the option that should be pre-selected.
     * @param array  $options        An associative array of options (key => value).
     * @param array  $attributes     [optional] Additional attributes for the radio inputs. Default is an empty array.
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml         [optional] If true, content is treated as pre-escaped HTML. Default is false.
     *
     * @return string The HTML for the group of radio buttons.
     */
    public function getRadioGroup(
        string $name,
        string $selectedOption,
        array $options,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        $html = '';
        foreach ($options as $value => $label) {
            $radioButton = $this->getRadioButton(
                $name,
                $value,
                'checked',
                $selectedOption,
                $attributes,
                $pad,
                $isHtml
            );
            $escapedLabel = HtmlBuildUtility::escape($label, $isHtml);
            $html .= HtmlBuildUtility::formatOutput(
                $radioButton . ' ' . $escapedLabel,
                $this->formatOutput,
                false,
                $pad
            );
        }
        return $html;
    }

    /**
     * Builds a radio button HTML element with specific attributes and state.
     *
     * @param string       $name       The `name` attribute for the radio input.
     * @param string       $value      The `value` attribute for the radio input.
     * @param string       $data       The value to compare to determine if the radio button is checked.
     * @param string|array $status     The status of the radio button (e.g., "checked", "disabled").
     *                                  Can be a string or an array of statuses.
     * @param array        $attributes [optional] Additional attributes for the radio input.
     *                                  Default is an empty array.
     * @param bool         $isHtml     [optional] If true, content is treated as pre-escaped HTML. Default is false.
     *
     * @return string The HTML for the radio button element.
     */
    private function buildElement(
        string $name,
        string $value,
        string $data,
        $status,
        array $attributes,
        bool $isHtml
    ): string {
        $escapedName = HtmlBuildUtility::escape($name, $isHtml);
        $escapedValue = HtmlBuildUtility::escape($value, $isHtml);
        $statusAttributes = $this->getStatusAttribute($status, $value, $data);
        $attrString = HtmlBuildUtility::buildAttributes($attributes);
        return sprintf(
            '<input type="radio" name="%s" value="%s"%s%s>',
            $escapedName,
            $escapedValue,
            $statusAttributes,
            $attrString
        );
    }

    /**
     * Determines the status attributes (e.g., "checked", "disabled") for the radio button.
     *
     * - If `$status` is an array, it iterates through each status and applies it based on the conditions.
     * - If `$status` is a string, only the specified status is applied.
     *
     * @param string|array $status The status of the radio button. Accepts a string or an array of statuses
     *                             (e.g., "checked", "disabled").
     * @param string       $value  The value of the radio button.
     * @param string       $data   The value to match for the "checked" status.
     *
     * @return string A space-separated string of status attributes (e.g., "checked disabled"), or an empty string
     *                if no statuses apply.
     */
    private function getStatusAttribute(
        $status,
        string $value,
        string $data
    ): string {
        $attributes = [];
        if (is_array($status)) {
            foreach ($status as $stat) {
                if ($stat === 'checked' && $value === $data) {
                    $attributes[] = 'checked';
                } elseif ($stat === 'disabled') {
                    $attributes[] = 'disabled';
                }
            }
        } else {
            if ($status === 'checked' && $value === $data) {
                $attributes[] = 'checked';
            } elseif ($status === 'disabled') {
                $attributes[] = 'disabled';
            }
        }
        return $attributes
            ? ' ' . implode(' ', $attributes)
            : '';
    }
}
