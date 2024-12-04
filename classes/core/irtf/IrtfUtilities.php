<?php

namespace App\core\irtf;

/**
 * /home/webdev2024/classes/core/irtf/IrtfUtilities.php
 *
 * This class provides static methods to handle IRTF-specific tasks.
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
     * Calculates the Unix timestamp from a given date.
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
     * Converts a Unix timestamp to a string format, e.g., "Oct 25, 2024".
     *
     * @param int $timestamp The Unix timestamp.
     *
     * @return string The formatted date string.
     */
    public static function returnTextDate(
        int $timestamp,
        string $format = 'M d, Y'
    ): string {
        return date($format, $timestamp);
        //return date("M d, Y", $timestamp);
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
     * The year is divided into two semesters: "A" (February 1 to July 31) and
     * "B" (August 1 to January 31 of the following year). Note that the
     * date values are constructed to ensure appropriate fall-through of the
     * conditionals. Yes, Jan 1 is NOT a typo.
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
