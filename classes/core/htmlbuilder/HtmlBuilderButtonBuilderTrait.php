<?php

declare(strict_types=1);

namespace App\core\htmlbuilder;

/**
 * Wrapper for HTML button generation methods.
 *
 * Provides a unified interface for creating various types of button
 * elements, including submit buttons, icon buttons, reset buttons,
 * confirmation buttons, and grouped buttons using the ButtonBuilder class.
 *
 * This trait encapsulates specialized logic for interactive elements,
 * with consistent formatting and accessibility support.
 *
 * @see ButtonBuilder
 */
trait HtmlBuilderButtonBuilderTrait
{
    /**
     * Generates a button element using ButtonBuilder.
     *
     * @param string      $label       The label to be displayed on the button.
     * @param string      $type        [optional] The button type (e.g., "submit", "button"). Default is 'button'.
     * @param string|null $name        [optional] Optional name attribute for the button. Default is null.
     * @param array       $attributes  [optional] Additional attributes for the button. Default is an empty array.
     * @param int         $pad         [optional] Indentation level for formatted output. Default is 0.
     * @param bool        $isHtml      [optional] If true, content is treated as pre-escaped HTML. Default is false.
     *
     * @return string The generated HTML for the button element.
     */
    public function getButton(
        string $label,
        string $type = 'button',
        ?string $name = null,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->buttonBuilder->getButton(
            $label,
            $type,
            $name,
            $attributes,
            $pad,
            $isHtml
        );
    }

    /**
     * Generates a submit button using ButtonBuilder.
     *
     * @param string      $name        The name attribute for the submit button.
     * @param string      $label       The label for the submit button.
     * @param array       $attributes  [optional] Additional attributes for the button. Default is an empty array.
     * @param int         $pad         [optional] Indentation level for formatted output. Default is 0.
     * @param bool        $isHtml      [optional] If true, content is treated as pre-escaped HTML. Default is false.
     *
     * @return string The generated HTML for the submit button.
     */
    public function getSubmitButton(
        string $name,
        string $label,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->buttonBuilder->getSubmitButton(
            $name,
            $label,
            $attributes,
            $pad,
            $isHtml
        );
    }

    /**
     * Generates a button with an icon using ButtonBuilder.
     *
     * @param string      $label       The label to be displayed on the button.
     * @param string      $iconClass   The CSS class for the icon.
     * @param string      $type        [optional] The button type (e.g., "submit", "button"). Default is 'button'.
     * @param string|null $name        [optional] Optional name attribute for the button. Default is null.
     * @param array       $attributes  [optional] Additional attributes for the button. Default is an empty array.
     * @param int         $pad         [optional] Indentation level for formatted output. Default is 0.
     * @param bool        $isHtml      [optional] If true, content is treated as pre-escaped HTML. Default is false.
     *
     * @return string The generated HTML for the button with an icon.
     */
    public function getIconButton(
        string $label,
        string $iconClass,
        string $type = 'button',
        ?string $name = null,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->buttonBuilder->getIconButton(
            $label,
            $iconClass,
            $type,
            $name,
            $attributes,
            $pad,
            $isHtml
        );
    }

    /**
     * Generates a confirmation button using ButtonBuilder.
     *
     * @param string      $label              The label to be displayed on the button.
     * @param string      $confirmationMessage The message for the confirmation dialog.
     * @param string      $type               [optional] The button type (e.g., "submit", "button").
     *                                         Default is 'button'.
     * @param string|null $name               [optional] Optional name attribute for the button. Default is null.
     * @param array       $attributes         [optional] Additional attributes for the button.
     *                                         Default is an empty array.
     * @param int         $pad                [optional] Indentation level for formatted output. Default is 0.
     * @param bool        $isHtml             [optional] If true, content is treated as pre-escaped HTML.
     *                                         Default is false.
     *
     * @return string The generated HTML for the confirmation button.
     */
    public function getConfirmButton(
        string $label,
        string $confirmationMessage,
        string $type = 'button',
        ?string $name = null,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->buttonBuilder->getConfirmButton(
            $label,
            $confirmationMessage,
            $type,
            $name,
            $attributes,
            $pad,
            $isHtml
        );
    }

    /**
     * Generates a disabled button element using ButtonBuilder.
     *
     * @param string      $label       The label to be displayed on the button.
     * @param string      $type        [optional] The button type (e.g., "submit", "button"). Default is 'button'.
     * @param string|null $name        [optional] Optional name attribute for the button. Default is null.
     * @param array       $attributes  [optional] Additional attributes for the button. Default is an empty array.
     * @param int         $pad         [optional] Indentation level for formatted output. Default is 0.
     * @param bool        $isHtml      [optional] If true, content is treated as pre-escaped HTML. Default is false.
     *
     * @return string The generated HTML for the disabled button.
     */
    public function getDisabledButton(
        string $label,
        string $type = 'button',
        ?string $name = null,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->buttonBuilder->getDisabledButton(
            $label,
            $type,
            $name,
            $attributes,
            $pad,
            $isHtml
        );
    }

    /**
     * Generates a dropdown button with a list of items using ButtonBuilder.
     *
     * @param string      $label         The label to be displayed on the button.
     * @param array       $dropdownItems An array of items with 'link' and 'label' keys for the dropdown.
     * @param string      $type          [optional] The button type (e.g., "button"). Default is 'button'.
     * @param string|null $name          [optional] Optional name attribute for the button. Default is null.
     * @param array       $buttonAttributes  [optional] Additional attributes for the button.
     *                                    Default is an empty array.
     * @param array       $dropdownAttributes [optional] Additional attributes for the dropdown container.
     *                                    Default is an empty array.
     * @param array       $contentAttributes [optional] Additional attributes for the dropdown-content div.
     *                                    Default is an empty array.
     * @param int         $pad           [optional] Indentation level for formatted output. Default is 0.
     * @param bool        $isHtml        [optional] If true, content is treated as pre-escaped HTML. Default is false.
     *
     * @return string The generated HTML for the dropdown button.
     */
    public function getDropdownButton(
        string $label,
        array $dropdownItems,
        string $type = 'button',
        ?string $name = null,
        array $buttonAttributes = [],
        array $dropdownAttributes = [],
        array $contentAttributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->buttonBuilder->getDropdownButton(
            $label,
            $dropdownItems,
            $type,
            $name,
            $buttonAttributes,
            $dropdownAttributes,
            $contentAttributes,
            $pad,
            $isHtml
        );
    }

    /**
     * Generates a reset button element using ButtonBuilder.
     *
     * @param string      $label       [optional] The label to be displayed on the reset button. Default is 'Reset'.
     * @param array       $attributes  [optional] Additional attributes for the button. Default is an empty array.
     * @param int         $pad         [optional] Indentation level for formatted output. Default is 0.
     * @param bool        $isHtml      [optional] If true, content is treated as pre-escaped HTML. Default is false.
     *
     * @return string The generated HTML for the reset button.
     */
    public function getResetButton(
        string $label = 'Reset',
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->buttonBuilder->getResetButton(
            $label,
            $attributes,
            $pad,
            $isHtml
        );
    }

    /**
     * Generates a link styled as a button element using ButtonBuilder.
     *
     * @param string      $label       The label to be displayed on the link.
     * @param string      $href        The URL the link should point to.
     * @param array       $attributes  [optional] Additional attributes for the link. Default is an empty array.
     * @param int         $pad         [optional] Indentation level for formatted output. Default is 0.
     * @param bool        $isHtml      [optional] If true, content is treated as pre-escaped HTML. Default is false.
     *
     * @return string The generated HTML for the link styled as a button.
     */
    public function getLinkButton(
        string $label,
        string $href,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->buttonBuilder->getLinkButton(
            $label,
            $href,
            $attributes,
            $pad,
            $isHtml
        );
    }

    /**
     * Generates a group of buttons (submit, reset, or custom) using ButtonBuilder.
     *
     * @param array $buttons     An array of button configurations (e.g., 'name', 'label', 'type', etc.).
     * @param int   $pad         [optional] Indentation level for formatted output. Default is 0.
     * @param bool  $isHtml      [optional] If true, content is treated as pre-escaped HTML. Default is false.
     *
     * @return string The generated HTML for the group of buttons.
     */
    public function getButtonGroup(
        array $buttons,
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->buttonBuilder->getButtonGroup(
            $buttons,
            $buttonWidth,
            $pad,
            $isHtml
        );
    }
}
