<?php

declare(strict_types=1);

namespace App\validators\common\core;

use App\validators\common\ValidationResult;
use App\validators\common\utilities\DateTimeBaseUtility;

/**
 * DateTimeBaseTrait
 *
 * Provides wrapper methods for DateTimeBaseUtility functionality.
 * Handles atomic validation of individual date/time components and full timestamps.
 *
 * @category Validation
 * @package  IRTF
 * @version  1.0.0
 */
trait DateTimeBaseTrait
{
    /**
     * Validates that a year value is within an acceptable range.
     *
     * Default range is 2000 to (current year + 5).
     * Custom min/max years can be specified. Minimum year will be clamped to 1900.
     *
     * @param ValidationResult $result   The ValidationResult instance to update.
     * @param mixed             $value    The year value to validate.
     * @param string            $fieldKey The field key associated with the value.
     * @param int               $minYear  Minimum allowed year (default: 2000).
     * @param int|null          $maxYear  Maximum allowed year (default: current year + 5).
     *
     * @return ValidationResult Updated ValidationResult containing either validation errors or the validated value.
     */
    public function validateYear(
        ValidationResult $result,
        $value,
        string $fieldKey,
        int $minYear = 2000,
        ?int $maxYear = null
    ): ValidationResult {
        return DateTimeBaseUtility::validateYear(
            $result,
            $value,
            $fieldKey,
            $minYear,
            $maxYear
        );
    }

    /**
     * Validates that a month value is an integer within a specified range.
     *
     * Default range is 1–12. If custom min/max are provided, they will be clamped to 1 and 12 respectively.
     *
     * @param ValidationResult  $result   The ValidationResult instance to update.
     * @param mixed             $value    The month value to validate.
     * @param string            $fieldKey The field key associated with the value.
     * @param int               $minMonth Minimum valid month (default: 1).
     * @param int               $maxMonth Maximum valid month (default: 12).
     *
     * @return ValidationResult Updated ValidationResult containing either validation errors or the validated value.
     */
    public function validateMonth(
        ValidationResult $result,
        $value,
        string $fieldKey,
        int $minMonth = 1,
        int $maxMonth = 12
    ): ValidationResult {
        return DateTimeBaseUtility::validateMonth(
            $result,
            $value,
            $fieldKey,
            $minMonth,
            $maxMonth
        );
    }

    /**
     * Validates that a day value is an integer within a specified range.
     *
     * Default range is 1–31. If custom min/max are provided, they will be clamped to 1 and 31 respectively.
     *
     * @param ValidationResult  $result   The ValidationResult instance to update.
     * @param mixed             $value    The day value to validate.
     * @param string            $fieldKey The field key associated with the value.
     * @param int               $minDay   Minimum valid day (default: 1).
     * @param int               $maxDay   Maximum valid day (default: 31).
     *
     * @return ValidationResult Updated ValidationResult containing either validation errors or the validated value.
     */
    public function validateDay(
        ValidationResult $result,
        $value,
        string $fieldKey,
        int $minDay = 1,
        int $maxDay = 31
    ): ValidationResult {
        return DateTimeBaseUtility::validateDay(
            $result,
            $value,
            $fieldKey,
            $minDay,
            $maxDay
        );
    }

    /**
     * Validates that an hour value is an integer within a specified range.
     *
     * Default range is 0–23. If custom min/max are provided, they will be clamped to 0 and 23 respectively.
     *
     * @param ValidationResult  $result   The ValidationResult instance to update.
     * @param mixed             $value    The hour value to validate.
     * @param string            $fieldKey The field key associated with the value.
     * @param int               $minHour  Minimum valid hour (default: 0).
     * @param int               $maxHour  Maximum valid hour (default: 23).
     *
     * @return ValidationResult Updated ValidationResult containing either validation errors or the validated value.
     */
    public function validateHour(
        ValidationResult $result,
        $value,
        string $fieldKey,
        int $minHour = 0,
        int $maxHour = 23
    ): ValidationResult {
        return DateTimeBaseUtility::validateHour(
            $result,
            $value,
            $fieldKey,
            $minHour,
            $maxHour
        );
    }

    /**
     * Validates that a minute value is an integer within a specified range.
     *
     * Default range is 0–59. If custom min/max are provided, they will be clamped to 0 and 59 respectively.
     *
     * @param ValidationResult  $result    The ValidationResult instance to update.
     * @param mixed             $value     The minute value to validate.
     * @param string            $fieldKey  The field key associated with the value.
     * @param int               $minMinute Minimum valid minute (default: 0).
     * @param int               $maxMinute Maximum valid minute (default: 59).
     *
     * @return ValidationResult Updated ValidationResult containing either validation errors or the validated value.
     */
    public function validateMinute(
        ValidationResult $result,
        $value,
        string $fieldKey,
        int $minMinute = 0,
        int $maxMinute = 59
    ): ValidationResult {
        return DateTimeBaseUtility::validateMinute(
            $result,
            $value,
            $fieldKey,
            $minMinute,
            $maxMinute
        );
    }

    /**
     * Validates that a second value is an integer within a specified range.
     *
     * Default range is 0–59. If custom min/max are provided, they will be clamped to 0 and 59 respectively.
     *
     * @param ValidationResult  $result    The ValidationResult instance to update.
     * @param mixed             $value     The second value to validate.
     * @param string            $fieldKey  The field key associated with the value.
     * @param int               $minSecond Minimum valid second (default: 0).
     * @param int               $maxSecond Maximum valid second (default: 59).
     *
     * @return ValidationResult Updated ValidationResult containing either validation errors or the validated value.
     */
    public function validateSecond(
        ValidationResult $result,
        $value,
        string $fieldKey,
        int $minSecond = 0,
        int $maxSecond = 59
    ): ValidationResult {
        return DateTimeBaseUtility::validateSecond(
            $result,
            $value,
            $fieldKey,
            $minSecond,
            $maxSecond
        );
    }

    /**
     * Validates a full date (year, month, day) and ensures it forms a real calendar date.
     *
     * Stores the validated timestamp and date components into the ValidationResult.
     *
     * @param ValidationResult  $result       The ValidationResult instance to update.
     * @param mixed             $yearValue    The year value to validate.
     * @param mixed             $monthValue   The month value to validate.
     * @param mixed             $dayValue     The day value to validate.
     * @param string            $fieldKey     The field key associated with the date.
     *
     * @return ValidationResult Updated ValidationResult containing either validation errors or the validated date data.
     */
    public function validateFullDate(
        ValidationResult $result,
        $yearValue,
        $monthValue,
        $dayValue,
        string $fieldKey
    ): ValidationResult {
        return DateTimeBaseUtility::validateFullDate(
            $result,
            $yearValue,
            $monthValue,
            $dayValue,
            $fieldKey
        );
    }

    /**
     * Validates a full date (year, month, day) and time (hour, minute, second)
     * and ensures it forms a valid date time.
     *
     * Stores the validated timestamp and date/time components into the ValidationResult.
     *
     * @param ValidationResult  $result       The ValidationResult instance to update.
     * @param mixed             $yearValue    The year value to validate.
     * @param mixed             $monthValue   The month value to validate.
     * @param mixed             $dayValue     The day value to validate.
     * @param mixed             $hourValue    The hour value to validate.
     * @param mixed             $minuteValue  The minute value to validate.
     * @param mixed             $secondValue  The second value to validate.
     * @param string            $fieldKey     The field key associated with the date.
     *
     * @return ValidationResult Updated ValidationResult containing either validation errors or the validated date data.
     */
    public function validateFullDateTime(
        ValidationResult $result,
        $yearValue,
        $monthValue,
        $dayValue,
        $hourValue,
        $minuteValue,
        $secondValue,
        string $fieldKey
    ): ValidationResult {
        return DateTimeBaseUtility::validateFullDateTime(
            $result,
            $yearValue,
            $monthValue,
            $dayValue,
            $hourValue,
            $minuteValue,
            $secondValue,
            $fieldKey
        );
    }

    /**
     * Validates that a value is a valid Unix timestamp.
     *
     * Default range is 0 (Unix epoch) to 253402300799 (December 31, 9999, 23:59:59 UTC).
     * If custom min/max are provided, they will be clamped to this standard range.
     *
     * @param ValidationResult  $result        The ValidationResult instance to update.
     * @param mixed             $value         The timestamp value to validate.
     * @param string            $fieldKey      The field key associated with the value.
     * @param int               $minTimestamp  Minimum valid Unix timestamp (default: 0).
     * @param int               $maxTimestamp  Maximum valid Unix timestamp (default: 253402300799).
     *
     * @return ValidationResult Updated ValidationResult containing either validation errors or the validated timestamp.
     */
    public function validateUnixTimestamp(
        ValidationResult $result,
        $value,
        string $fieldKey,
        int $minTimestamp = 0,
        int $maxTimestamp = 253402300799
    ): ValidationResult {
        return DateTimeBaseUtility::validateUnixTimestamp(
            $result,
            $value,
            $fieldKey,
            $minTimestamp,
            $maxTimestamp
        );
    }
}
