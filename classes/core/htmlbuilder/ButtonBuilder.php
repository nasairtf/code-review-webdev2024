<?php

namespace App\core\htmlbuilder;

/**
 * /home/webdev2024/classes/core/htmlbuilder/ButtonBuilder.php
 *
 * A utility class responsible for building HTML buttons with optional formatting.
 *
 * @category Utilities
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class ButtonBuilder
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
     * @param bool $formatOutput If true, output will be formatted with indentation.
     */
    public function __construct(bool $formatOutput = false)
    {
        $this->formatOutput = $formatOutput;
    }

    /**
     * Builds the button HTML with type, attributes, name, and label.
     *
     * @param string      $type       The button type.
     * @param string      $label      The button label.
     * @param string|null $name       [optional] The name attribute for the button.
     * @param array       $attributes [optional] Additional attributes for the button.
     * @param bool        $isHtml     [optional] Whether the content is HTML. Default is false.
     *
     * @return string The complete button HTML.
     */
    private function buildElement(
        string $type,
        string $label,
        ?string $name,
        array $attributes,
        bool $isHtml
    ): string {
        $escapedType = HtmlBuildUtility::escape($type, $isHtml);
        $escapedLabel = HtmlBuildUtility::escape($label, $isHtml);
        $nameAttr = ($name !== null) ? sprintf(' name="%s"', HtmlBuildUtility::escape($name, $isHtml)) : '';
        $attrString = HtmlBuildUtility::buildAttributes($attributes);
        return sprintf('<button type="%s"%s%s>%s</button>', $escapedType, $attrString, $nameAttr, $escapedLabel);
    }

    /**
     * Generates a button element.
     *
     * @param string      $label       The label to be displayed on the button.
     * @param string      $type        [optional] The button type (e.g., "submit", "button", "reset"). Default is 'button'.
     * @param string|null $name        [optional] Optional name attribute for the button. Default is null.
     * @param array       $attributes  [optional] Additional attributes for the button. Default is an empty array.
     * @param int         $pad         [optional] Indentation level for formatted output. Default is 0.
     * @param bool        $isHtml      [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The HTML for the button element.
     */
    public function getButton(
        string $label,
        string $type = 'button',
        ?string $name = null,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        $html = $this->buildElement($type, $label, $name, $attributes, $isHtml);
        return HtmlBuildUtility::formatOutput($html, $this->formatOutput, false, $pad);
    }

    /**
     * Generates a submit button with optional width styling.
     *
     * @param string      $name        The name attribute for the submit button.
     * @param string      $label       The label for the submit button.
     * @param array       $attributes  [optional] Additional attributes for the button. Default is an empty array.
     * @param int         $pad         [optional] Indentation level for formatted output. Default is 0.
     * @param bool        $isHtml      [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The HTML for the submit button.
     */
    public function getSubmitButton(
        string $name,
        string $label,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        $html = $this->buildElement('submit', $label, $name, $attributes, $isHtml);
        return HtmlBuildUtility::formatOutput($html, $this->formatOutput, false, $pad);
    }

    /**
     * Generates a disabled button element.
     *
     * @param string      $label       The label to be displayed on the button.
     * @param string      $type        [optional] The button type (e.g., "submit", "button"). Default is 'button'.
     * @param string|null $name        [optional] Optional name attribute for the button. Default is null.
     * @param array       $attributes  [optional] Additional attributes for the button. Default is an empty array.
     * @param int         $pad         [optional] Indentation level for formatted output. Default is 0.
     * @param bool        $isHtml      [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The HTML for the disabled button element.
     */
    public function getDisabledButton(
        string $label,
        string $type = 'button',
        ?string $name = null,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        $attributes['disabled'] = 'disabled';
        return $this->getButton($label, $type, $name, $attributes, $pad, $isHtml);
    }

    /**
     * Generates a reset button element.
     *
     * @param string      $label       [optional] The label to be displayed on the reset button. Default is 'Reset'.
     * @param array       $attributes  [optional] Additional attributes for the button. Default is an empty array.
     * @param int         $pad         [optional] Indentation level for formatted output. Default is 0.
     * @param bool        $isHtml      [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The HTML for the reset button.
     */
    public function getResetButton(
        string $label = 'Reset',
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->getButton($label, 'reset', null, $attributes, $pad, $isHtml);
    }

    /**
     * Generates a link styled as a button element.
     *
     * @param string      $label       The label to be displayed on the link.
     * @param string      $href        The URL the link should point to.
     * @param array       $attributes  [optional] Additional attributes for the link. Default is an empty array.
     * @param int         $pad         [optional] Indentation level for formatted output. Default is 0.
     * @param bool        $isHtml      [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The HTML for the link styled as a button.
     */
    public function getLinkButton(
        string $label,
        string $href,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        $escapedHref = HtmlBuildUtility::escape($href, false);
        $escapedLabel = HtmlBuildUtility::escape($label, $isHtml);
        $attrString = HtmlBuildUtility::buildAttributes($attributes);
        $html = sprintf('<a href="%s"%s>%s</a>', $escapedHref, $attrString, $escapedLabel);
        return HtmlBuildUtility::formatOutput($html, $this->formatOutput, false, $pad);
    }

    /**
     * Generates a button with an icon.
     *
     * @param string      $label       The label to be displayed on the button.
     * @param string      $iconClass   The CSS class for the icon (e.g., FontAwesome or Material Icons class).
     * @param string      $type        [optional] The button type (e.g., "submit", "button"). Default is 'button'.
     * @param string|null $name        [optional] Optional name attribute for the button. Default is null.
     * @param array       $attributes  [optional] Additional attributes for the button. Default is an empty array.
     * @param int         $pad         [optional] Indentation level for formatted output. Default is 0.
     * @param bool        $isHtml      [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The HTML for the button with an icon.
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
        $iconTag = sprintf('<i class="%s"></i>', HtmlBuildUtility::escape($iconClass, $isHtml));
        $labelWithIcon = $iconTag . ' ' . HtmlBuildUtility::escape($label, $isHtml);
        $html = $this->buildElement($type, $labelWithIcon, $name, $attributes, $isHtml);
        return HtmlBuildUtility::formatOutput($html, $this->formatOutput, false, $pad);
    }

    /**
     * Generates a group of submit, reset, or custom buttons.
     *
     * @param array       $buttons     An array of button configurations (e.g., 'name', 'label', 'type', etc.).
     * @param int         $pad         [optional] Indentation level for formatted output. Default is 0.
     * @param bool        $isHtml      [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The HTML for the group of buttons.
     */
    public function getButtonGroup(
        array $buttons,
        int $pad = 0,
        bool $isHtml = false
    ): string {
        $html = '';
        foreach ($buttons as $button) {
            $name = $button['name'] ?? 'submit';
            $label = $button['label'] ?? 'Submit';
            $type = $button['type'] ?? 'submit';
            $attributes = $button['attributes'] ?? [];
            $buttonHtml = $this->buildElement($type, $label, $name, $attributes, $isHtml);
            $html .= HtmlBuildUtility::formatOutput($buttonHtml, $this->formatOutput, false, $pad);
        }
        return $html;
    }

    /**
     * Generates a button that triggers a confirmation dialog before an action is performed.
     *
     * @param string      $label              The label to be displayed on the button.
     * @param string      $confirmationMessage The message for the confirmation dialog.
     * @param string      $type               [optional] The button type (e.g., "submit", "button"). Default is 'button'.
     * @param string|null $name               [optional] Optional name attribute for the button. Default is null.
     * @param array       $attributes         [optional] Additional attributes for the button. Default is an empty array.
     * @param int         $pad                [optional] Indentation level for formatted output. Default is 0.
     * @param bool        $isHtml             [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The HTML for the confirmation button.
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
        $attributes['onclick'] = sprintf("return confirm('%s')", HtmlBuildUtility::escape($confirmationMessage, $isHtml));
        $html = $this->buildElement($type, $label, $name, $attributes, $isHtml);
        return HtmlBuildUtility::formatOutput($html, $this->formatOutput, false, $pad);
    }

    /**
     * Generates a dropdown button with a list of items.
     *
     * @param string      $label       The label to be displayed on the button.
     * @param array       $dropdownItems An array of items with 'link' and 'label' keys for the dropdown.
     * @param string      $type        [optional] The button type (e.g., "button"). Default is 'button'.
     * @param string|null $name        [optional] Optional name attribute for the button. Default is null.
     * @param array       $buttonAttributes  [optional] Additional attributes for the button. Default is an empty array.
     * @param array       $dropdownAttributes [optional] Additional attributes for the dropdown container. Default is an empty array.
     * @param array       $contentAttributes [optional] Additional attributes for the dropdown-content div. Default is an empty array.
     * @param int         $pad         [optional] Indentation level for formatted output. Default is 0.
     * @param bool        $isHtml      [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The HTML for the dropdown button.
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
        $buttonHtml = $this->buildElement($type, $label, $name, $buttonAttributes, $isHtml);
        $dropdownDivAttrString = HtmlBuildUtility::buildAttributes($dropdownAttributes);
        $contentDivAttrString = HtmlBuildUtility::buildAttributes($contentAttributes);
        $html = sprintf('<div%s>%s<div%s>', $dropdownDivAttrString, $buttonHtml, $contentDivAttrString);
        foreach ($dropdownItems as $item) {
            $escapedItemLabel = HtmlBuildUtility::escape($item['label'], $isHtml);
            $escapedItemLink = HtmlBuildUtility::escape($item['link'], false);
            $html .= sprintf('<a href="%s">%s</a>', $escapedItemLink, $escapedItemLabel);
        }
        $html .= '</div></div>';
        return HtmlBuildUtility::formatOutput($html, $this->formatOutput, false, $pad);
    }
}
