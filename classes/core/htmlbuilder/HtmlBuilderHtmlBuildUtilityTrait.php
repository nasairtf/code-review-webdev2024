<?php

declare(strict_types=1);

namespace App\core\htmlbuilder;

/**
 * Wrapper for shared HTML formatting utilities.
 *
 * Offers static formatting tools for line breaks, padding,
 * part concatenation, and HTML escaping via HtmlBuildUtility.
 *
 * Used internally to ensure all builders produce well-structured,
 * optionally formatted HTML output.
 *
 * @see HtmlBuildUtility
 */
trait HtmlBuilderHtmlBuildUtilityTrait
{
    /**
     * Formats an individual string with optional left padding based on the format flag.
     *
     * @param string $content        The HTML content to format.
     * @param bool   $formatOutput   Whether to format with padding and newlines.
     * @param bool   $prependNewline Whether to prepend a newline.
     * @param int    $pad            Number of spaces for left padding (only applies if $format is true).
     *
     * @return string The formatted string.
     */
    public static function formatOutput(
        string $content,
        bool $formatOutput = false,
        bool $prependNewline = false,
        int $pad = 0
    ): string {
        return HtmlBuildUtility::formatOutput(
            $content,
            $formatOutput,
            $prependNewline,
            $pad
        );
    }

    /**
     * Formats an array of HTML parts into a single string, with optional newlines between parts.
     *
     * @param array $parts        The array of HTML strings to join together.
     * @param bool  $formatOutput Whether to format with newlines between parts.
     *
     * @return string The concatenated HTML string.
     */
    public static function formatParts(
        array $parts,
        bool $formatOutput = false
    ): string {
        return HtmlBuildUtility::formatParts($parts, $formatOutput);
    }

    /**
     * Escapes a string for HTML output.
     *
     * @param string $string The string to escape.
     * @param bool   $isHtml Whether to skip escaping if the content is already HTML.
     *
     * @return string The escaped or raw string.
     */
    public static function escape(
        string $string,
        bool $isHtml = false
    ): string {
        return HtmlBuildUtility::escape($string, $isHtml);
    }
}
