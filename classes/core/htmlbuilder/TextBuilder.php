<?php

namespace App\core\htmlbuilder;

/**
 * /home/webdev2024/classes/core/htmlbuilder/TextBuilder.php
 *
 * A utility class responsible for building HTML text input fields with optional formatting.
 *
 * @category Utilities
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.1
 */

class TextBuilder
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
     * Generates a generic input field.
     *
     * @param string $type        The input type (e.g., 'text', 'email', 'password').
     * @param string $name        The name attribute for the input field.
     * @param string $value       [optional] The value of the input field. Default is an empty string.
     * @param int    $size        [optional] The size of the input field. Default is 25.
     * @param array  $attributes  [optional] Additional attributes for the input field. Default is an empty array.
     * @param int    $pad         [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml      [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The HTML for the input field.
     */
    private function buildElement(
        string $type,
        string $name,
        string $value = '',
        ?int $size = null,
        ?int $min = null,
        ?int $max = null,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        $escapedName = HtmlBuildUtility::escape($name, $isHtml);
        $escapedValue = HtmlBuildUtility::escape($value, $isHtml);
        $attrString = HtmlBuildUtility::buildAttributes($attributes);
        $html = sprintf('<input type="%s" name="%s" value="%s"', $type, $escapedName, $escapedValue);
        $html .= ($size !== null) ? sprintf(' size="%d"', $size) : '';
        $html .= ($min !== null) ? sprintf(' min="%d"', $min) : '';
        $html .= ($max !== null) ? sprintf(' max="%d"', $max) : '';
        $html .= $attrString . ' />';
        return HtmlBuildUtility::formatOutput($html, $this->formatOutput, false, $pad);
    }

    /**
     * Generates a textarea element.
     *
     * @param string $name        The name attribute for the textarea.
     * @param string $content     [optional] The content inside the textarea. Default is an empty string.
     * @param int    $rows        [optional] The number of rows for the textarea. Default is 5.
     * @param int    $cols        [optional] The number of columns for the textarea. Default is 40.
     * @param array  $attributes  [optional] Additional attributes for the input field. Default is an empty array.
     * @param int    $pad         [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml      [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The HTML for the textarea element.
     */
    public function getTextarea(
        string $name,
        string $content = '',
        int $rows = 5,
        int $cols = 40,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        $escapedName = HtmlBuildUtility::escape($name, $isHtml);
        $escapedContents = HtmlBuildUtility::escape($content, $isHtml);
        $attrString = HtmlBuildUtility::buildAttributes($attributes);
        $html = sprintf(
            '<textarea name="%s" rows="%d" cols="%d"%s>%s</textarea>',
            $escapedName,
            $rows,
            $cols,
            $attrString,
            $escapedContents
        );
        return HtmlBuildUtility::formatOutput($html, $this->formatOutput, false, $pad);
    }

    /**
     * Generates a text input field.
     *
     * @param string $name        The name attribute for the input field.
     * @param string $value       [optional] The value of the input field. Default is an empty string.
     * @param int    $size        [optional] The size of the input field. Default is 25.
     * @param array  $attributes  [optional] Additional attributes for the input field. Default is an empty array.
     * @param int    $pad         [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml      [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The HTML for the text input field.
     */
    public function getTextInput(
        string $name,
        string $value = '',
        int $size = 25,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->buildElement('text', $name, $value, $size, null, null, $attributes, $pad, $isHtml);
    }

    /**
     * Generates a password input field.
     *
     * @param string $name        The name attribute for the password field.
     * @param int    $size        [optional] The size of the password field. Default is 25.
     * @param array  $attributes  [optional] Additional attributes for the input field. Default is an empty array.
     * @param int    $pad         [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml      [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The HTML for the password input field.
     */
    public function getPasswordInput(
        string $name,
        int $size = 25,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->buildElement('password', $name, '', $size, null, null, $attributes, $pad, $isHtml);
    }

    /**
     * Generates an email input field.
     *
     * @param string $name        The name attribute for the email field.
     * @param string $value       [optional] The value of the email field. Default is an empty string.
     * @param int    $size        [optional] The size of the email field. Default is 25.
     * @param array  $attributes  [optional] Additional attributes for the input field. Default is an empty array.
     * @param int    $pad         [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml      [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The HTML for the email input field.
     */
    public function getEmailInput(
        string $name,
        string $value = '',
        int $size = 25,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->buildElement('email', $name, $value, $size, null, null, $attributes, $pad, $isHtml);
    }

    /**
     * Generates a number input field.
     *
     * @param string $name        The name attribute for the number field.
     * @param string $value       [optional] The value of the number field. Default is an empty string.
     * @param int    $min         [optional] The minimum value allowed. Default is 0.
     * @param int    $max         [optional] The maximum value allowed. Default is 100.
     * @param array  $attributes  [optional] Additional attributes for the input field. Default is an empty array.
     * @param int    $pad         [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml      [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The HTML for the number input field.
     */
    public function getNumberInput(
        string $name,
        string $value = '',
        int $min = 0,
        int $max = 100,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->buildElement('number', $name, $value, null, $min, $max, $attributes, $pad, $isHtml);
    }

    /**
     * Generates the input field for the Unix timestamp.
     *
     * @param string $name        The name attribute for the timestamp field.
     * @param string $value       [optional] The value of the timestamp field. Default is an empty string.
     * @param array  $attributes  [optional] Additional attributes for the input field. Default is an empty array.
     * @param int    $pad         [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml      [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The HTML for the Unix timestamp input field.
     */
    public function getUnixTimestampInput(
        string $name,
        string $value = '',
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        $minTimestamp = 0;           // Earliest possible Unix timestamp
        $maxTimestamp = 2147483647;  // Maximum value for 32-bit Unix timestamps
        return $this->getNumberInput($name, $value, $minTimestamp, $maxTimestamp, $attributes, $pad, $isHtml);
    }

    /**
     * Generates a URL input field.
     *
     * @param string $name        The name attribute for the URL field.
     * @param string $value       [optional] The value of the URL field. Default is an empty string.
     * @param array  $attributes  [optional] Additional attributes for the input field. Default is an empty array.
     * @param int    $pad         [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml      [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The HTML for the URL input field.
     */
    public function getUrlInput(
        string $name,
        string $value = '',
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->buildElement('url', $name, $value, null, null, null, $attributes, $pad, $isHtml);
    }

    /**
     * Generates a hidden input field.
     *
     * @param string $name        The name attribute for the hidden field.
     * @param string $value       [optional] The value of the hidden field. Default is an empty string.
     * @param array  $attributes  [optional] Additional attributes for the input field. Default is an empty array.
     * @param int    $pad         [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml      [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The HTML for the hidden input field.
     */
    public function getHiddenInput(
        string $name,
        string $value = '',
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->buildElement('hidden', $name, $value, null, null, null, $attributes, $pad, $isHtml);
    }

    /**
     * Generates a search input field.
     *
     * @param string $name        The name attribute for the search field.
     * @param string $value       [optional] The value of the search field. Default is an empty string.
     * @param array  $attributes  [optional] Additional attributes for the input field. Default is an empty array.
     * @param int    $pad         [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml      [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The HTML for the search input field.
     */
    public function getSearchInput(
        string $name,
        string $value = '',
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->buildElement('search', $name, $value, null, null, null, $attributes, $pad, $isHtml);
    }

    /**
     * Generates a tel input field.
     *
     * @param string $name        The name attribute for the tel field.
     * @param string $value       [optional] The value of the tel field. Default is an empty string.
     * @param array  $attributes  [optional] Additional attributes for the input field. Default is an empty array.
     * @param int    $pad         [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml      [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The HTML for the tel input field.
     */
    public function getTelInput(
        string $name,
        string $value = '',
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->buildElement('tel', $name, $value, null, null, null, $attributes, $pad, $isHtml);
    }
}
