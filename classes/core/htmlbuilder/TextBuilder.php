<?php

declare(strict_types=1);

namespace App\core\htmlbuilder;

use App\core\htmlbuilder\HtmlBuildUtility;

/**
 * /home/webdev2024/classes/core/htmlbuilder/TextBuilder.php
 *
 * A utility class for generating HTML input fields, including text, password, email, and textarea elements.
 * Offers options for size, additional attributes, and pre-escaped content handling.
 *
 * Formatting preferences determine whether the output includes indentation and line breaks.
 *
 * Example:
 * ```
 * $builder = new TextBuilder(true);
 * echo $builder->getTextInput('username', 'JohnDoe', 25, ['class' => 'input-class']);
 * ```
 *
 * @category Utilities
 * @package  IRTF
 * @version  1.0.1
 * @since    1.0.0
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
     * @param bool $formatOutput Whether to format the HTML (indentation, line breaks).
     */
    public function __construct(
        ?bool $formatOutput = null
    ) {
        $this->formatOutput = $formatOutput ?? false;
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
     * @param bool   $isHtml      [optional] If true, content is treated as pre-escaped HTML. Default is false.
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
        return HtmlBuildUtility::formatOutput(
            $html,
            $this->formatOutput,
            false,
            $pad
        );
    }

    /**
     * Generates a text input field.
     *
     * @param string $name        The name attribute for the input field.
     * @param string $value       [optional] The default value for the input field. Default: empty string.
     * @param int    $size        [optional] The size of the input field. Default: 25.
     * @param array  $attributes  [optional] Additional attributes for the input field. Default: empty array.
     * @param int    $pad         [optional] Indentation level for formatted output. Default: 0.
     * @param bool   $isHtml      [optional] If true, content is treated as pre-escaped HTML. Default: false.
     *
     * @return string The formatted text input element.
     */
    public function getTextInput(
        string $name,
        string $value = '',
        int $size = 25,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->buildElement(
            'text',
            $name,
            $value,
            $size,
            null,
            null,
            $attributes,
            $pad,
            $isHtml
        );
    }

    /**
     * Generates a password input field.
     *
     * @param string $name        The name attribute for the password field.
     * @param int    $size        [optional] The size of the password field. Default is 25.
     * @param array  $attributes  [optional] Additional attributes for the input field. Default is an empty array.
     * @param int    $pad         [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml      [optional] If true, content is treated as pre-escaped HTML. Default is false.
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
        return $this->buildElement(
            'password',
            $name,
            '',
            $size,
            null,
            null,
            $attributes,
            $pad,
            $isHtml
        );
    }

    /**
     * Generates an email input field.
     *
     * @param string $name        The name attribute for the email field.
     * @param string $value       [optional] The value of the email field. Default is an empty string.
     * @param int    $size        [optional] The size of the email field. Default is 25.
     * @param array  $attributes  [optional] Additional attributes for the input field. Default is an empty array.
     * @param int    $pad         [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml      [optional] If true, content is treated as pre-escaped HTML. Default is false.
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
        return $this->buildElement(
            'email',
            $name,
            $value,
            $size,
            null,
            null,
            $attributes,
            $pad,
            $isHtml
        );
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
     * @param bool   $isHtml      [optional] If true, content is treated as pre-escaped HTML. Default is false.
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
        return $this->buildElement(
            'number',
            $name,
            $value,
            null,
            $min,
            $max,
            $attributes,
            $pad,
            $isHtml
        );
    }

    /**
     * Generates the input field for the Unix timestamp.
     *
     * @param string $name        The name attribute for the timestamp field.
     * @param string $value       [optional] The value of the timestamp field. Default is an empty string.
     * @param array  $attributes  [optional] Additional attributes for the input field. Default is an empty array.
     * @param int    $pad         [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml      [optional] If true, content is treated as pre-escaped HTML. Default is false.
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
        return $this->getNumberInput(
            $name,
            $value,
            $minTimestamp,
            $maxTimestamp,
            $attributes,
            $pad,
            $isHtml
        );
    }

    /**
     * Generates a URL input field.
     *
     * @param string $name        The name attribute for the URL field.
     * @param string $value       [optional] The value of the URL field. Default is an empty string.
     * @param array  $attributes  [optional] Additional attributes for the input field. Default is an empty array.
     * @param int    $pad         [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml      [optional] If true, content is treated as pre-escaped HTML. Default is false.
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
        return $this->buildElement(
            'url',
            $name,
            $value,
            null,
            null,
            null,
            $attributes,
            $pad,
            $isHtml
        );
    }

    /**
     * Generates a hidden input field.
     *
     * @param string $name        The name attribute for the hidden field.
     * @param string $value       [optional] The value of the hidden field. Default is an empty string.
     * @param array  $attributes  [optional] Additional attributes for the input field. Default is an empty array.
     * @param int    $pad         [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml      [optional] If true, content is treated as pre-escaped HTML. Default is false.
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
        return $this->buildElement(
            'hidden',
            $name,
            $value,
            null,
            null,
            null,
            $attributes,
            $pad,
            $isHtml
        );
    }

    /**
     * Generates a search input field.
     *
     * @param string $name        The name attribute for the search field.
     * @param string $value       [optional] The value of the search field. Default is an empty string.
     * @param array  $attributes  [optional] Additional attributes for the input field. Default is an empty array.
     * @param int    $pad         [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml      [optional] If true, content is treated as pre-escaped HTML. Default is false.
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
        return $this->buildElement(
            'search',
            $name,
            $value,
            null,
            null,
            null,
            $attributes,
            $pad,
            $isHtml
        );
    }

    /**
     * Generates a tel input field.
     *
     * @param string $name        The name attribute for the tel field.
     * @param string $value       [optional] The value of the tel field. Default is an empty string.
     * @param array  $attributes  [optional] Additional attributes for the input field. Default is an empty array.
     * @param int    $pad         [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml      [optional] If true, content is treated as pre-escaped HTML. Default is false.
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
        return $this->buildElement(
            'tel',
            $name,
            $value,
            null,
            null,
            null,
            $attributes,
            $pad,
            $isHtml
        );
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
     * @param bool   $isHtml      [optional] If true, content is treated as pre-escaped HTML. Default is false.
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
        $html = sprintf(
            '<input type="%s" name="%s" value="%s"',
            $type,
            $escapedName,
            $escapedValue
        );
        $html .= ($size !== null) ? sprintf(' size="%d"', $size) : '';
        $html .= ($min !== null) ? sprintf(' min="%d"', $min) : '';
        $html .= ($max !== null) ? sprintf(' max="%d"', $max) : '';
        $html .= $attrString . ' />';
        return HtmlBuildUtility::formatOutput(
            $html,
            $this->formatOutput,
            false,
            $pad
        );
    }
}
