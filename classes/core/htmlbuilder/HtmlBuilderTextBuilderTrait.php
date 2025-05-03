<?php

declare(strict_types=1);

namespace App\core\htmlbuilder;

/**
 * Wrapper for textual input element generation methods.
 *
 * Provides a unified interface for rendering HTML text inputs, textareas,
 * email and number fields, search and URL inputs, and other standard
 * form fields using TextBuilder.
 *
 * This trait enables HtmlBuilder to generate complete form inputs
 * with formatting and escape safety.
 *
 * @see TextBuilder
 */
trait HtmlBuilderTextBuilderTrait
{
    /**
     * Generates a text input field using TextBuilder.
     *
     * @param string $name       The name attribute for the input field.
     * @param string $value      [optional] The value of the input field. Default is an empty string.
     * @param int    $size       [optional] The size of the input field. Default is 25.
     * @param array  $attributes [optional] Additional attributes for the input field. Default is an empty array.
     * @param int    $pad        [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml     [optional] If true, content is treated as pre-escaped HTML. Default is false.
     *
     * @return string The generated HTML for the text input field.
     */
    public function getTextInput(
        string $name,
        string $value = '',
        int $size = 25,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->textBuilder->getTextInput(
            $name,
            $value,
            $size,
            $attributes,
            $pad,
            $isHtml
        );
    }

    /**
     * Generates a textarea element using TextBuilder.
     *
     * @param string $name       The name attribute for the textarea.
     * @param string $content    [optional] The content inside the textarea. Default is an empty string.
     * @param int    $rows       [optional] The number of rows for the textarea. Default is 5.
     * @param int    $cols       [optional] The number of columns for the textarea. Default is 40.
     * @param array  $attributes [optional] Additional attributes for the textarea. Default is an empty array.
     * @param int    $pad        [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml     [optional] If true, content is treated as pre-escaped HTML. Default is false.
     *
     * @return string The generated HTML for the textarea.
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
        return $this->textBuilder->getTextarea(
            $name,
            $content,
            $rows,
            $cols,
            $attributes,
            $pad,
            $isHtml
        );
    }

    /**
     * Generates a password input field using TextBuilder.
     *
     * @param string $name       The name attribute for the password field.
     * @param int    $size       [optional] The size of the password field. Default is 25.
     * @param array  $attributes [optional] Additional attributes for the password field. Default is an empty array.
     * @param int    $pad        [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml     [optional] If true, content is treated as pre-escaped HTML. Default is false.
     *
     * @return string The generated HTML for the password field.
     */
    public function getPasswordInput(
        string $name,
        int $size = 25,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->textBuilder->getPasswordInput(
            $name,
            $size,
            $attributes,
            $pad,
            $isHtml
        );
    }

    /**
     * Generates an email input field using TextBuilder.
     *
     * @param string $name       The name attribute for the email field.
     * @param string $value      [optional] The value of the email field. Default is an empty string.
     * @param int    $size       [optional] The size of the email field. Default is 25.
     * @param array  $attributes [optional] Additional attributes for the email field. Default is an empty array.
     * @param int    $pad        [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml     [optional] If true, content is treated as pre-escaped HTML. Default is false.
     *
     * @return string The generated HTML for the email field.
     */
    public function getEmailInput(
        string $name,
        string $value = '',
        int $size = 25,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->textBuilder->getEmailInput(
            $name,
            $value,
            $size,
            $attributes,
            $pad,
            $isHtml
        );
    }

    /**
     * Generates a number input field using TextBuilder.
     *
     * @param string $name       The name attribute for the number field.
     * @param string $value      [optional] The value of the number field. Default is an empty string.
     * @param int    $min        [optional] The minimum value allowed. Default is 0.
     * @param int    $max        [optional] The maximum value allowed. Default is 100.
     * @param array  $attributes [optional] Additional attributes for the number field. Default is an empty array.
     * @param int    $pad        [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml     [optional] If true, content is treated as pre-escaped HTML. Default is false.
     *
     * @return string The generated HTML for the number input field.
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
        return $this->textBuilder->getNumberInput(
            $name,
            $value,
            $min,
            $max,
            $attributes,
            $pad,
            $isHtml
        );
    }

    /**
     * Generates a unix timestamp input field using TextBuilder.
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
        return $this->textBuilder->getUnixTimestampInput(
            $name,
            $value,
            $attributes,
            $pad,
            $isHtml
        );
    }

    /**
     * Generates a URL input field using TextBuilder.
     *
     * @param string $name       The name attribute for the URL field.
     * @param string $value      [optional] The value of the URL field. Default is an empty string.
     * @param array  $attributes [optional] Additional attributes for the URL field. Default is an empty array.
     * @param int    $pad        [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml     [optional] If true, content is treated as pre-escaped HTML. Default is false.
     *
     * @return string The generated HTML for the URL field.
     */
    public function getUrlInput(
        string $name,
        string $value = '',
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->textBuilder->getUrlInput(
            $name,
            $value,
            $attributes,
            $pad,
            $isHtml
        );
    }

    /**
     * Generates a hidden input field using TextBuilder.
     *
     * @param string $name       The name attribute for the hidden field.
     * @param string $value      [optional] The value of the hidden field. Default is an empty string.
     * @param array  $attributes [optional] Additional attributes for the hidden field. Default is an empty array.
     * @param int    $pad        [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml     [optional] If true, content is treated as pre-escaped HTML. Default is false.
     *
     * @return string The generated HTML for the hidden input field.
     */
    public function getHiddenInput(
        string $name,
        string $value = '',
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->textBuilder->getHiddenInput(
            $name,
            $value,
            $attributes,
            $pad,
            $isHtml
        );
    }

    /**
     * Generates a search input field using TextBuilder.
     *
     * @param string $name       The name attribute for the search field.
     * @param string $value      [optional] The value of the search field. Default is an empty string.
     * @param array  $attributes [optional] Additional attributes for the search field. Default is an empty array.
     * @param int    $pad        [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml     [optional] If true, content is treated as pre-escaped HTML. Default is false.
     *
     * @return string The generated HTML for the search field.
     */
    public function getSearchInput(
        string $name,
        string $value = '',
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->textBuilder->getSearchInput(
            $name,
            $value,
            $attributes,
            $pad,
            $isHtml
        );
    }

    /**
     * Generates a telephone input field using TextBuilder.
     *
     * @param string $name       The name attribute for the telephone field.
     * @param string $value      [optional] The value of the telephone field. Default is an empty string.
     * @param array  $attributes [optional] Additional attributes for the telephone field. Default is an empty array.
     * @param int    $pad        [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml     [optional] If true, content is treated as pre-escaped HTML. Default is false.
     *
     * @return string The generated HTML for the telephone field.
     */
    public function getTelInput(
        string $name,
        string $value = '',
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->textBuilder->getTelInput(
            $name,
            $value,
            $attributes,
            $pad,
            $isHtml
        );
    }
}
