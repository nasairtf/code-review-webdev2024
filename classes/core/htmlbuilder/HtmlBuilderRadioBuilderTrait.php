<?php

declare(strict_types=1);

namespace App\core\htmlbuilder;

/**
 * Wrapper for HTML radio button generation methods.
 *
 * Offers methods for creating individual radio buttons, labeled options,
 * and radio groups (including inline and disabled variants), using the
 * RadioBuilder class for logic centralization and rendering consistency.
 *
 * This trait simplifies construction of grouped choice inputs in forms.
 *
 * @see RadioBuilder
 */
trait HtmlBuilderRadioBuilderTrait
{
    /**
     * Generates a single radio button using RadioBuilder.
     *
     * @param string $name       The name attribute for the radio input.
     * @param string $value      The value attribute for the radio input.
     * @param string $status     The status of the radio button (e.g., "checked", "disabled").
     * @param string $data       The data used to determine whether the radio button is checked.
     * @param array  $attributes [optional] Additional attributes for the radio input. Default is an empty array.
     * @param int    $pad        [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml     [optional] If true, content is treated as pre-escaped HTML. Default is false.
     *
     * @return string The generated HTML for the radio button element.
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
        return $this->radioBuilder->getRadioButton(
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
     * Generates a labeled radio button using RadioBuilder.
     *
     * @param string      $name       The name attribute for the radio input.
     * @param string      $value      The value attribute for the radio input.
     * @param string      $status     The status of the radio button (e.g., "checked", "disabled").
     * @param string      $data       The data used to determine whether the radio button is checked.
     * @param string|null $label      [optional] Optional label for the radio button. Default is null.
     * @param bool        $labelAfter [optional] Whether to place the label after the radio button. Default is true.
     * @param array       $attributes [optional] Additional attributes for the radio input. Default is an empty array.
     * @param int         $pad        [optional] Indentation level for formatted output. Default is 0.
     * @param bool        $isHtml     [optional] If true, content is treated as pre-escaped HTML. Default is false.
     *
     * @return string The generated HTML for the labeled radio button.
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
        return $this->radioBuilder->getLabeledRadioButton(
            $name,
            $value,
            $status,
            $data,
            $label,
            $labelAfter,
            $attributes,
            $pad,
            $isHtml
        );
    }

    /**
     * Generates a radio button with a unique ID using RadioBuilder.
     *
     * @param string      $name       The name attribute for the radio input.
     * @param string      $value      The value attribute for the radio input.
     * @param string      $status     The status of the radio button (e.g., "checked", "disabled").
     * @param string      $data       The data used to determine whether the radio button is checked.
     * @param string|null $id         [optional] Optional unique ID for the radio button. Default is null.
     * @param array       $attributes [optional] Additional attributes for the radio input. Default is an empty array.
     * @param int         $pad        [optional] Indentation level for formatted output. Default is 0.
     * @param bool        $isHtml     [optional] If true, content is treated as pre-escaped HTML. Default is false.
     *
     * @return string The generated HTML for the radio button with an ID.
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
        return $this->radioBuilder->getRadioButtonWithID(
            $name,
            $value,
            $status,
            $data,
            $id,
            $attributes,
            $pad,
            $isHtml
        );
    }

    /**
     * Generates a group of radio buttons with an inline layout option using RadioBuilder.
     *
     * @param string $name           The name attribute for the radio inputs.
     * @param string $selectedOption The value of the option that should be pre-selected.
     * @param array  $options        An associative array of options (key => value).
     * @param bool   $inline         [optional] Whether to display the radio buttons inline. Default is false.
     * @param array  $attributes     [optional] Additional attributes for the radio inputs. Default is an empty array.
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml         [optional] If true, content is treated as pre-escaped HTML. Default is false.
     *
     * @return string The generated HTML for the group of radio buttons.
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
        return $this->radioBuilder->getRadioGroupWithInlineOption(
            $name,
            $selectedOption,
            $options,
            $inline,
            $attributes,
            $pad,
            $isHtml
        );
    }

    /**
     * Generates a group of disabled radio buttons using RadioBuilder.
     *
     * @param string $name       The name attribute for the radio inputs.
     * @param array  $options    An associative array of options (key => value).
     * @param array  $attributes [optional] Additional attributes for the radio inputs. Default is an empty array.
     * @param int    $pad        [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml     [optional] If true, content is treated as pre-escaped HTML. Default is false.
     *
     * @return string The generated HTML for the disabled radio group.
     */
    public function getDisabledRadioGroup(
        string $name,
        array $options,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->radioBuilder->getDisabledRadioGroup(
            $name,
            $options,
            $attributes,
            $pad,
            $isHtml
        );
    }

    /**
     * Generates a group of radio buttons from an associative array of options using RadioBuilder.
     *
     * @param string $name           The name attribute for the radio inputs.
     * @param string $selectedOption The value of the option that should be pre-selected.
     * @param array  $options        An associative array of options (key => value).
     * @param array  $attributes     [optional] Additional attributes for the radio inputs. Default is an empty array.
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml         [optional] If true, content is treated as pre-escaped HTML. Default is false.
     *
     * @return string The generated HTML for the radio group.
     */
    public function getRadioGroup(
        string $name,
        string $selectedOption,
        array $options,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->radioBuilder->getRadioGroup(
            $name,
            $selectedOption,
            $options,
            $attributes,
            $pad,
            $isHtml
        );
    }
}
