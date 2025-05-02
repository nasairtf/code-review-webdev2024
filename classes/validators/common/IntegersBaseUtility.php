<?php

declare(strict_types=1);

namespace App\validators\common;

/**
 * IntegersBaseUtility
 *
 * Provides methods for basic integer validations:
 * - Integer type checking
 * - Integer range checking
 *
 * @category Validators
 * @package  IRTF
 * @version  1.0.0
 */
class IntegersBaseUtility
{
    /**
     * Validates that a value is a proper integer.
     *
     * @param ValidationResult  $result    The ValidationResult instance to update.
     * @param mixed             $value     The value to validate.
     * @param string            $fieldKey  The field key associated with the value.
     *
     * @return ValidationResult Updated ValidationResult containing either validation errors or the validated integer value.
     */
    public static function validateInteger(
        ValidationResult $result,
        $value,
        string $fieldKey
    ): ValidationResult {
        // Ensure value is numeric and an integer (ignoring scientific notation unless it's fractional)
        if (!is_numeric($value) || (int) $value != $value) {
            return $result->addFieldError(
                $fieldKey,
                "Value must be a valid integer."
            );
        }

        // Store the validated value
        return $result->setFieldValue($fieldKey, (int) $value);
    }

    /**
     * Validates that an integer is within an optional minimum and/or maximum range.
     *
     * @param ValidationResult  $result    The ValidationResult instance to update.
     * @param int               $value     The integer value to validate.
     * @param string            $fieldKey  The field key associated with the value.
     * @param int|null          $minValue  Optional minimum allowed value.
     * @param int|null          $maxValue  Optional maximum allowed value.
     *
     * @return ValidationResult Updated ValidationResult containing either validation errors or the validated value.
     */
    public static function validateIntegerRange(
        ValidationResult $result,
        int $value,
        string $fieldKey,
        ?int $minValue = null,
        ?int $maxValue = null
    ): ValidationResult {
        // If both minValue and maxValue are set, validate against full range
        if ($minValue !== null && $maxValue !== null) {
            if ($value < $minValue || $value > $maxValue) {
                return $result->addFieldError(
                    $fieldKey,
                    "Invalid value: Must be between {$minValue} and {$maxValue}."
                );
            }
        } else {
            // If only minValue is set, enforce lower bound
            if ($minValue !== null && $value < $minValue) {
                return $result->addFieldError(
                    $fieldKey,
                    "Value must be at least {$minValue}."
                );
            }

            // If only maxValue is set, enforce upper bound
            if ($maxValue !== null && $value > $maxValue) {
                return $result->addFieldError(
                    $fieldKey,
                    "Value must not exceed {$maxValue}."
                );
            }
        }

        // Store the validated value
        return $result->setFieldValue($fieldKey, $value);
    }
}
