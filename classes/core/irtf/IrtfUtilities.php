<?php

declare(strict_types=1);

namespace App\core\irtf;

/**
 * This class provides static utility methods to handle IRTF-specific tasks.
 *
 * These tasks include date calculations, string sanitization for HTML output,
 * and semester determination based on the IRTF's semester system.
 *
 * @category Utilities
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class IrtfUtilities
{
    // Dates

    /**
     * Calculates the Unix timestamp for a given date at midnight.
     *
     * This method sets the time to 00:00:00 and computes the Unix timestamp
     * for the specified month, day, and year.
     *
     * Example:
     * ```php
     * $timestamp = IrtfUtilities::returnUnixDate(10, 25, 2024); // Unix timestamp for "2024-10-25 00:00:00"
     * ```
     *
     * @param int $month The month (1-12).
     * @param int $day   The day (1-31).
     * @param int $year  The year, e.g., 2024.
     *
     * @return int The Unix timestamp for the specified date.
     */
    public static function returnUnixDate(
        int $month,
        int $day,
        int $year
    ): int {
        return mktime(0, 0, 0, $month, $day, $year);
    }

    /**
     * Formats a Unix timestamp as a human-readable date string.
     *
     * Converts a Unix timestamp into a string representation using the specified
     * format. The default format is "M d, Y" (e.g., "Oct 25, 2024").
     *
     * Example:
     * ```php
     * $formattedDate = IrtfUtilities::returnTextDate(1727846400); // "Oct 25, 2024"
     * ```
     *
     * @param int    $timestamp The Unix timestamp to format.
     * @param string $format    [optional] The date format. Default is 'M d, Y'.
     *
     * @return string The formatted date string.
     */
    public static function returnTextDate(
        int $timestamp,
        string $format = 'M d, Y'
    ): string {
        return date($format, $timestamp);
    }

    // Strings

    /**
     * Escapes a string for safe HTML output.
     *
     * This method converts special characters to HTML entities to prevent
     * XSS attacks and ensure safe rendering in HTML.
     *
     * @param string $string The string to escape.
     *
     * @return string The escaped string, safe for HTML output.
     */
    public static function escape(
        string $string
    ): string {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }

    // Semesters

    /**
     * Determines the semester based on the given date.
     *
     * The year is divided into two semesters:
     * - "A" (February 1 to July 31)
     * - "B" (August 1 to January 31 of the following year)
     *
     * For dates before February 1, the method assigns the "B" semester of the previous year.
     * This logic ensures seamless fall-through of conditional checks.
     *
     * Example:
     * ```php
     * $semester = IrtfUtilities::returnSemester(3, 15, 2024); // "2024A"
     * $semester = IrtfUtilities::returnSemester(1, 15, 2024); // "2023B"
     * ```
     *
     * @param int $month The month of the date.
     * @param int $day   The day of the date.
     * @param int $year  The year of the date.
     *
     * @return string The semester code in the format "YYYYA" or "YYYYB".
     */
    public static function returnSemester(
        int $month,
        int $day,
        int $year
    ): string {
        $dateTimestamp = self::returnUnixDate($month, $day, $year);
        $startOfSemesterA = self::returnUnixDate(2, 1, $year);   // Feb 1 of the year
        $endOfSemesterA = self::returnUnixDate(8, 1, $year);     // Aug 1 of the year
        $startOfSemesterB = $endOfSemesterA;                     // Aug 1 of the year
        $endOfSemesterB = self::returnUnixDate(1, 1, $year + 1); // Jan 1 of the following year
        if ($dateTimestamp >= $startOfSemesterA && $dateTimestamp < $endOfSemesterA) {
            return $year . 'A';
        } elseif ($dateTimestamp >= $startOfSemesterB && $dateTimestamp < $endOfSemesterB) {
            return $year . 'B';
        }
        return ($year - 1) . 'B'; // If date is before Feb 1, assign the previous year's "B" semester.
    }
}
