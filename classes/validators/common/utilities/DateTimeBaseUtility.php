<?php

declare(strict_types=1);

namespace App\validators\common\utilities;

/**
 * DateTimeBaseUtility
 *
 * Provides atomic validation methods for date and time components:
 * - Year, month, day, hour, minute, second
 * - Full calendar dates
 * - Full calendar dates with time (datetime)
 * - Unix timestamps
 *
 * Each validation method enforces type correctness, valid value ranges,
 * and protects against invalid real-world calendar dates.
 *
 * This class assumes inputs have already been pre-sanitized and focuses
 * purely on validation, not transformation or formatting.
 *
 * @category Validators
 * @package  IRTF
 * @version  1.0.0
 */
class DateTimeBaseUtility
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
    public static function validateYear(
        ValidationResult $result,
        $value,
        string $fieldKey,
        int $minYear = 2000,
        ?int $maxYear = null
    ): ValidationResult {
        // Validate value is an integer
        $res = IntegersBaseUtility::validateInteger(
            $result,
            $value,
            $fieldKey
        );

        // Short-circuit and return if integer validation failed
        if ($res->hasErrors()) {
            return $res;
        }

        // Prepare value for validation
        $value = (int) $value;

        // Clamp the min/max to a semi-sane year range just in case
        $minYear = max(1900, $minYear);  // Just in case — no dates before 1900.
        $maxYear = $maxYear ?? intval(date('Y')) + 5; // Five years in the future if no max given

        // Validate value is within the necessary bounds
        return IntegersBaseUtility::validateIntegerRange(
            $res,
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
    public static function validateMonth(
        ValidationResult $result,
        $value,
        string $fieldKey,
        int $minMonth = 1,
        int $maxMonth = 12
    ): ValidationResult {
        // Validate value is an integer
        $res = IntegersBaseUtility::validateInteger(
            $result,
            $value,
            $fieldKey
        );

        // Short-circuit and return if integer validation failed
        if ($res->hasErrors()) {
            return $res;
        }

        // Prepare value for validation
        $value = (int) $value;

        // Clamp the min/max to valid month range just in case
        $minMonth = max(1, $minMonth);  // Ensure minimum is at least 1
        $maxMonth = min(12, $maxMonth); // Ensure maximum is at most 12

        // Validate value is within the necessary bounds
        return IntegersBaseUtility::validateIntegerRange(
            $res,
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
    public static function validateDay(
        ValidationResult $result,
        $value,
        string $fieldKey,
        int $minDay = 1,
        int $maxDay = 31
    ): ValidationResult {
        // Validate value is an integer
        $res = IntegersBaseUtility::validateInteger(
            $result,
            $value,
            $fieldKey
        );

        // Short-circuit and return if integer validation failed
        if ($res->hasErrors()) {
            return $res;
        }

        // Prepare value for validation
        $value = (int) $value;

        // Clamp the min/max to valid day range just in case
        $minDay = max(1, $minDay);  // Ensure minimum is at least 1
        $maxDay = min(31, $maxDay); // Ensure maximum is at most 31

        // Validate value is within the necessary bounds
        return IntegersBaseUtility::validateIntegerRange(
            $res,
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
    public static function validateHour(
        ValidationResult $result,
        $value,
        string $fieldKey,
        int $minHour = 0,
        int $maxHour = 23
    ): ValidationResult {
        // Validate value is an integer
        $res = IntegersBaseUtility::validateInteger(
            $result,
            $value,
            $fieldKey
        );

        // Short-circuit and return if integer validation failed
        if ($res->hasErrors()) {
            return $res;
        }

        // Prepare value for validation
        $value = (int) $value;

        // Clamp the min/max to valid hour range just in case
        $minHour = max(0, $minHour);  // Ensure minimum is at least 0
        $maxHour = min(23, $maxHour); // Ensure maximum is at most 23

        // Validate value is within the necessary bounds
        return IntegersBaseUtility::validateIntegerRange(
            $res,
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
    public static function validateMinute(
        ValidationResult $result,
        $value,
        string $fieldKey,
        int $minMinute = 0,
        int $maxMinute = 59
    ): ValidationResult {
        // Validate value is an integer
        $res = IntegersBaseUtility::validateInteger(
            $result,
            $value,
            $fieldKey
        );

        // Short-circuit and return if integer validation failed
        if ($res->hasErrors()) {
            return $res;
        }

        // Prepare value for validation
        $value = (int) $value;

        // Clamp the min/max to valid minute range just in case
        $minMinute = max(0, $minMinute);  // Ensure minimum is at least 0
        $maxMinute = min(59, $maxMinute); // Ensure maximum is at most 59

        // Validate value is within the necessary bounds
        return IntegersBaseUtility::validateIntegerRange(
            $res,
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
    public static function validateSecond(
        ValidationResult $result,
        $value,
        string $fieldKey,
        int $minSecond = 0,
        int $maxSecond = 59
    ): ValidationResult {
        // Validate value is an integer
        $res = IntegersBaseUtility::validateInteger(
            $result,
            $value,
            $fieldKey
        );

        // Short-circuit and return if integer validation failed
        if ($res->hasErrors()) {
            return $res;
        }

        // Prepare value for validation
        $value = (int) $value;

        // Clamp the min/max to valid second range just in case
        $minSecond = max(0, $minSecond);  // Ensure minimum is at least 0
        $maxSecond = min(59, $maxSecond); // Ensure maximum is at most 59

        // Validate value is within the necessary bounds
        return IntegersBaseUtility::validateIntegerRange(
            $res,
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
    public static function validateFullDate(
        ValidationResult $result,
        $yearValue,
        $monthValue,
        $dayValue,
        string $fieldKey
    ): ValidationResult {
        // Validate component values
        $res = self::validateYear(
            $result,
            $yearValue,
            "{$fieldKey}_year"
        );
        $res = self::validateMonth(
            $res,
            $monthValue,
            "{$fieldKey}_month"
        );
        $res = self::validateDay(
            $res,
            $dayValue,
            "{$fieldKey}_day"
        );

        // Short-circuit and return if component validations failed
        if ($res->hasErrors()) {
            return $res;
        }

        // Explicitly cast components after validation passed
        $validatedYear  = (int) $yearValue;
        $validatedMonth = (int) $monthValue;
        $validatedDay   = (int) $dayValue;

        // Validate full date
        if (!checkdate($validatedMonth, $validatedDay, $validatedYear)) {
            return $res->addFieldError(
                $fieldKey,
                "Invalid date: {$validatedYear}-{$validatedMonth}-{$validatedDay}."
            );
        }

        // Build final validated value
        $timestamp = mktime(0, 0, 0, $validatedMonth, $validatedDay, $validatedYear);

        // Store validated values in the composite date structure
        $dateData = [
            'timestamp' => $timestamp,
            'components' => [
                'year'  => $validatedYear,
                'month' => $validatedMonth,
                'day'   => $validatedDay,
            ],
        ];

        // Store composite validated structure
        return $res->setFieldValue($fieldKey, $dateData)
            ->setFieldValue("{$fieldKey}_timestamp", $dateData['timestamp'])
            ->setFieldValue("{$fieldKey}_components", $dateData['components']);
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
    public static function validateFullDateTime(
        ValidationResult $result,
        $yearValue,
        $monthValue,
        $dayValue,
        $hourValue,
        $minuteValue,
        $secondValue,
        string $fieldKey
    ): ValidationResult {
        // Validate component values
        $res = self::validateYear(
            $result,
            $yearValue,
            "{$fieldKey}_year"
        );
        $res = self::validateMonth(
            $res,
            $monthValue,
            "{$fieldKey}_month"
        );
        $res = self::validateDay(
            $res,
            $dayValue,
            "{$fieldKey}_day"
        );
        $res = self::validateHour(
            $res,
            $hourValue,
            "{$fieldKey}_hour"
        );
        $res = self::validateMinute(
            $res,
            $minuteValue,
            "{$fieldKey}_minute"
        );
        $res = self::validateSecond(
            $res,
            $secondValue,
            "{$fieldKey}_second"
        );

        // Short-circuit and return if component validations failed
        if ($res->hasErrors()) {
            return $res;
        }

        // Explicitly cast components after validation passed
        $validatedYear   = (int) $yearValue;
        $validatedMonth  = (int) $monthValue;
        $validatedDay    = (int) $dayValue;
        $validatedHour   = (int) $hourValue;
        $validatedMinute = (int) $minuteValue;
        $validatedSecond = (int) $secondValue;

        // Validate full date
        if (!checkdate($validatedMonth, $validatedDay, $validatedYear)) {
            return $res->addFieldError(
                $fieldKey,
                "Invalid date: {$validatedYear}-{$validatedMonth}-{$validatedDay}."
            );
        }

        // Build final validated value
        $timestamp = mktime(
            $validatedHour,
            $validatedMinute,
            $validatedSecond,
            $validatedMonth,
            $validatedDay,
            $validatedYear
        );

        // Store validated values in the composite datetime structure
        $dateTimeData = [
            'timestamp'  => $timestamp,
            'components' => [
                'year'   => $validatedYear,
                'month'  => $validatedMonth,
                'day'    => $validatedDay,
                'hour'   => $validatedHour,
                'minute' => $validatedMinute,
                'second' => $validatedSecond,
            ],
        ];

        // Store composite validated structure
        return $res->setFieldValue($fieldKey, $dateTimeData)
            ->setFieldValue("{$fieldKey}_timestamp", $dateTimeData['timestamp'])
            ->setFieldValue("{$fieldKey}_components", $dateTimeData['components']);
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
    public static function validateUnixTimestamp(
        ValidationResult $result,
        $value,
        string $fieldKey,
        int $minTimestamp = 0,
        int $maxTimestamp = 253402300799
    ): ValidationResult {
        // Validate value is an integer
        $res = IntegersBaseUtility::validateInteger(
            $result,
            $value,
            $fieldKey
        );

        // Short-circuit and return if integer validation failed
        if ($res->hasErrors()) {
            return $res;
        }

        // Prepare value for validation
        $value = (int) $value;

        // Clamp the min/max to valid unix timestamp range just in case
        $minTimestamp = max(0, $minTimestamp);
        $maxTimestamp = min(253402300799, $maxTimestamp);

        // Validate value is within the necessary bounds
        return IntegersBaseUtility::validateIntegerRange(
            $res,
            $value,
            $fieldKey,
            $minTimestamp,
            $maxTimestamp
        );
    }
}
