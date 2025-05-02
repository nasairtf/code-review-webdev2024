<?php

declare(strict_types=1);

namespace App\validators\common;

/**
 * FloatsBaseUtility
 *
 * Provides methods for basic float (decimal number) validations:
 * - Float type checking
 * - Float range checking
 *
 * @category Validators
 * @package  IRTF
 * @version  1.0.0
 */
class FloatsBaseUtility
{
    /**
     * Validates that a value is a proper float.
     *
     * @param ValidationResult  $result    The ValidationResult instance to update.
     * @param mixed             $value     The value to validate.
     * @param string            $fieldKey  The field key associated with the value.
     *
     * @return ValidationResult Updated ValidationResult containing either validation errors or the validated float value.
     */
    public static function validateFloat(
        ValidationResult $result,
        $value,
        string $fieldKey
    ): ValidationResult {
        // Ensure value is numeric
        if (!is_numeric($value)) {
            return $result->addFieldError(
                $fieldKey,
                "Value must be a valid number."
            );
        }

        // Store the validated value
        return $result->setFieldValue($fieldKey, (float) $value);
    }

    /**
     * Validates that a float is within an optional minimum and/or maximum range.
     *
     * @param ValidationResult  $result    The ValidationResult instance to update.
     * @param float             $value     The float value to validate.
     * @param string            $fieldKey  The field key associated with the value.
     * @param float|null        $minValue  Optional minimum allowed value.
     * @param float|null        $maxValue  Optional maximum allowed value.
     *
     * @return ValidationResult Updated ValidationResult containing either validation errors or the validated value.
     */
    public static function validateFloatRange(
        ValidationResult $result,
        float $value,
        string $fieldKey,
        ?float $minValue = null,
        ?float $maxValue = null
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
