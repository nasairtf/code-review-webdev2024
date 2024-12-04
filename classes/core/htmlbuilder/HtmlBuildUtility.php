<?php

namespace App\core\htmlbuilder;

/**
 * /home/webdev2024/classes/core/htmlbuilder/HtmlBuildUtility.php
 *
 * A utility class that provides common methods for building HTML elements.
 *
 * @category Utilities
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.1
 */

class HtmlBuildUtility
{
    /**
     * Formats an individual string with optional left padding based on the format flag.
     *
     * @param string $content       The HTML content to format.
     * @param bool   $formatOutput  Whether to format with padding and newlines.
     * @param int    $pad           Number of spaces for left padding (only applies if $format is true).
     *
     * @return string The formatted string.
     */
    public static function formatOutput(
        string $content,
        bool $formatOutput = false,
        bool $prependNewline = false,
        int $pad = 0
    ): string {
        $formattedContent = $formatOutput ? self::padLeftString($content, $pad) : $content;
        return $prependNewline ? PHP_EOL . $formattedContent : $formattedContent;
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
        return $formatOutput ? implode(PHP_EOL, $parts) : implode('', $parts);
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
        return $isHtml ? $string : htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Builds a string of HTML attributes from an associative array.
     *
     * @param array $attributes  An associative array of attributes (e.g., 'class' => 'button').
     *
     * @return string The concatenated string of attributes.
     */
    public static function buildAttributes(
        array $attributes
    ): string {
        $attrString = '';
        foreach ($attributes as $key => $value) {
            $attrString .= sprintf(' %s="%s"', htmlspecialchars($key), htmlspecialchars($value));
        }
        return $attrString;
    }

    /**
     * Pads the given value with zeros on the left until the string reaches the specified length.
     *
     * @param int|string $value  The value to pad with zeros.
     * @param int        $length The total length the padded string should have.
     *
     * @return string The number or string padded with zeros on the left.
     */
    public static function padLeftZero(
        $value,
        int $length
    ): string {
        return strlen((string) $value) >= $length
            ? (string) $value
            : str_pad((string) $value, $length, '0', STR_PAD_LEFT);
    }

    /**
     * Pads the given string with spaces on the left to format class-generated HTML for readability.
     *
     * @param string $string     The string to pad.
     * @param int    $pad        The number of spaces to pad on the left.
     *
     * @return string The string padded with spaces on the left.
     */
    public static function padLeftString(
        string $string,
        int $pad = 0
    ): string {
        return str_pad($string, strlen($string) + $pad, ' ', STR_PAD_LEFT);
    }

    /**
     * Pads the given string with spaces on the right.
     *
     * @param string $string     The string to pad.
     * @param int    $pad        The number of spaces to pad on the right.
     *
     * @return string The string padded with spaces on the right.
     */
    public static function padRightString(
        string $string,
        int $pad = 0
    ): string {
        return str_pad($string, strlen($string) + $pad, ' ', STR_PAD_RIGHT);
    }

    /**
     * Returns a color based on status (e.g., success, error, warning).
     *
     * @param string $status The status (e.g., 'success', 'error', 'warning').
     *
     * @return string The associated color.
     */
    public static function getStatusColor(
        string $status
    ): string {
        $statusColors = [
            'success' => '#00FF00', // Green
            'error' => '#FF0000',   // Red
            'warning' => '#FFFF00', // Yellow
        ];
        return $statusColors[$status] ?? '#FFFFFF'; // Default: White
    }

    /**
     * Cycles through the sequence of provided colors.
     *
     * @param string $currentColor The current color.
     * @param array  $colors       The array of colors to cycle through.
     *
     * @return string The next color in the sequence.
     */
    public static function getCycledColor(
        string $currentColor,
        array $colors = ['#CCCCCC', '#FFFFFF']
    ): string {
        $currentIndex = array_search($currentColor, $colors);
        if ($currentIndex === false || $currentIndex === count($colors) - 1) {
            return $colors[0];
        }
        return $colors[$currentIndex + 1];
    }

    /**
     * Alternates between two shades of gray.
     *
     * @param string $currentColor The current gray.
     *
     * @return string The next shade of gray.
     */
    public static function getAlternatingGrays(
        string $currentColor
    ): string {
        return self::getCycledColor($currentColor, ['#C0C0C0', '#CCCCCC']);
    }

    /**
     * Alternates between two shades of blue.
     *
     * @param string $currentColor The current blue.
     *
     * @return string The next shade of blue.
     */
    public static function getAlternatingBlues(
        string $currentColor
    ): string {
        return self::getCycledColor($currentColor, ['#DDEEFF', '#99CCFF']);
    }

    /**
     * Alternates between two shades of green.
     *
     * @param string $currentColor The current green.
     *
     * @return string The next shade of green.
     */
    public static function getAlternatingGreens(
        string $currentColor
    ): string {
        return self::getCycledColor($currentColor, ['#E0FFE0', '#A0FFA0']);
    }
}
