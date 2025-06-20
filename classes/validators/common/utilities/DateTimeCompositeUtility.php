<?php

declare(strict_types=1);

namespace App\validators\common\utilities;

use App\validators\common\ValidationResult;

/**
 * DateTimeCompositeUtility
 *
 * Provides composite validation routines for date-related fields that require
 * coordination of multiple date components or logic (e.g., comparing two dates,
 * checking semester alignment).
 *
 * This layer assumes atomic validation of individual fields has been delegated
 * to DateTimeBaseUtility and focuses on orchestration of related data units.
 *
 * @category Validators
 * @package  IRTF
 * @version  1.0.0
 */
class DateTimeCompositeUtility
{
    /**
     * Validates a date range to ensure the end date is not before the start date.
     *
     * Both start and end dates are validated using FullDate logic, including structure
     * and validity (e.g., 2025-02-30 would fail). If both are valid, the resulting
     * Unix timestamps are compared to ensure chronological order.
     *
     * @param ValidationResult  $result       The ValidationResult instance to update.
     * @param mixed             $startYear    Start year component.
     * @param mixed             $startMonth   Start month component.
     * @param mixed             $startDay     Start day component.
     * @param mixed             $endYear      End year component.
     * @param mixed             $endMonth     End month component.
     * @param mixed             $endDay       End day component.
     * @param string            $fieldKey     The field key representing the date range group.
     *
     * @return ValidationResult Updated ValidationResult with any errors or stored values.
     */
    public static function validateDateRange(
        ValidationResult $result,
        $startYear,
        $startMonth,
        $startDay,
        $endYear,
        $endMonth,
        $endDay,
        string $fieldKey
    ): ValidationResult {
        // Validate component values
        $res = DateTimeBaseUtility::validateFullDate(
            $result,
            $startYear,
            $startMonth,
            $startDay,
            "{$fieldKey}_start"
        );
        $res = DateTimeBaseUtility::validateFullDate(
            $res,
            $endYear,
            $endMonth,
            $endDay,
            "{$fieldKey}_end"
        );

        // Short-circuit and return if component validations failed
        if ($res->hasErrors()) {
            return $res;
        }

        // Retrieve unix timestamps after validation passed
        $startTimestamp = (int) $res->getFieldValue("{$fieldKey}_start_timestamp");
        $endTimestamp   = (int) $res->getFieldValue("{$fieldKey}_end_timestamp");

        // Validate timestamp order
        if ($endTimestamp < $startTimestamp) {
            return $res->addFieldError(
                $fieldKey,
                "End date cannot be before start date."
            );
        }

        // Return validation result
        return $res;
    }

    /**
     * Validates that a date falls within a specified semester.
     *
     * Ensures the provided year/month/day corresponds to the same semester
     * as the provided semester code (e.g., '2025A'). If the two values do
     * not align, an error is added.
     *
     * @param ValidationResult  $result         The ValidationResult instance to update.
     * @param mixed             $yearValue      The year component of the date.
     * @param mixed             $monthValue     The month component of the date.
     * @param mixed             $dayValue       The day component of the date.
     * @param mixed             $semesterValue  The semester code to compare against (e.g., '2025A').
     * @param string            $fieldKey       The field key associated with this validation.
     *
     * @return ValidationResult Updated ValidationResult with either validation errors or validated fields.
     */
    public static function validateDateSemester(
        ValidationResult $result,
        $yearValue,
        $monthValue,
        $dayValue,
        $semesterValue,
        string $fieldKey
    ): ValidationResult {
        // Validate component values
        $res = DateTimeBaseUtility::validateFullDate(
            $result,
            $yearValue,
            $monthValue,
            $dayValue,
            "{$fieldKey}_date"
        );
        $res = TextCompositeUtility::validateSemesterField(
            $res,
            $semesterValue,
            "{$fieldKey}_semester"
        );

        // Short-circuit and return if component validations failed
        if ($res->hasErrors()) {
            return $res;
        }

        // Retrieve and calculate semesters after validation passed
        $semesterValue = (string) $semesterValue;
        $dateSemester = IrtfUtilities::returnSemester(
            (int) $monthValue,
            (int) $dayValue,
            (int) $yearValue
        );

        // Validate whether the semester values match
        if ($semesterValue !== $dateSemester) {
            return $res->addFieldError(
                $fieldKey,
                sprintf("Date must fall within %s semester.", $semesterValue)
            );
        }

        // Return validation result
        return $res;
    }
}
