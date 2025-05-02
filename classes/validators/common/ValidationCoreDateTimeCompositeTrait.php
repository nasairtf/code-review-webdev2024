<?php

declare(strict_types=1);

namespace App\validators\common;

use App\validators\common\DateTimeCompositeUtility;

/**
 * ValidationCoreDateTimeCompositeTrait
 *
 * Provides wrapper methods for DateTimeCompositeUtility functionality.
 * Enables validation of full date ranges and semester/date consistency.
 *
 * @category Validation
 * @package  IRTF
 * @version  1.0.0
 */
trait ValidationCoreDateTimeCompositeTrait
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
    public function validateDateRange(
        ValidationResult $result,
        $startYear,
        $startMonth,
        $startDay,
        $endYear,
        $endMonth,
        $endDay,
        string $fieldKey
    ): ValidationResult {
        return DateTimeCompositeUtility::validateDateRange(
            $result,
            $startYear,
            $startMonth,
            $startDay,
            $endYear,
            $endMonth,
            $endDay,
            $fieldKey
        );
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
    public function validateDateSemester(
        ValidationResult $result,
        $yearValue,
        $monthValue,
        $dayValue,
        $semesterValue,
        string $fieldKey
    ): ValidationResult {
        return DateTimeCompositeUtility::validateDateSemester(
            $result,
            $yearValue,
            $monthValue,
            $dayValue,
            $semesterValue,
            $fieldKey
        );
    }
}
