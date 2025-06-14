<?php

declare(strict_types=1);

namespace App\validators\common\utilities;

/**
 * RequiredFieldUtility
 *
 * Provides a method for checking whether a field is required and non-empty.
 *
 * @category Validators
 * @package  IRTF
 * @version  1.0.0
 */
class RequiredFieldUtility
{
    /**
     * Validates that a required field is set and contains meaningful content.
     *
     * Handles special cases for strings, arrays, and non-numeric empty values.
     * If $required is true and the value is empty/null, an error is recorded under $fieldKey.
     * Otherwise, the original $value is stored in the ValidationResult, even if empty/null.
     *
     * @param ValidationResult  $result       The ValidationResult instance to update.
     * @param mixed             $value        The field value to check.
     * @param bool              $required     Whether this field must be non-empty.
     * @param string            $fieldKey     The field key associated with the value.
     * @param string            $errorMessage The error message if validation fails.
     *
     * @return ValidationResult Updated ValidationResult containing either validation errors or the stored original value.
     */
    public static function validateRequired(
        ValidationResult $result,
        $value,
        bool $required,
        string $fieldKey,
        string $errorMessage
    ): ValidationResult {
        // Perform required check
        if ($required && self::isEmpty($value)) {
            // Record the error in the ValidationResult
            return $result->addFieldError($fieldKey, $errorMessage);
        }

        // If not required or value is not empty, store the original value (including null for optional fields).
        return $result->setFieldValue($fieldKey, $value);
    }

    /**
     * Determines whether a value should be considered empty.
     *
     * Special cases:
     * - Null values
     * - Strings that are whitespace-only
     * - Empty arrays
     * - Non-numeric empty values
     *
     * @param mixed $value The value to evaluate.
     *
     * @return bool True if the value is considered empty, false otherwise.
     */
    private static function isEmpty($value): bool
    {
        return (
            $value === null
            || (is_string($value) && trim($value) === '')
            || (is_array($value) && empty($value))
            || (!is_numeric($value) && empty($value))
        );
    }
}
