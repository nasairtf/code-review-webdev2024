<?php

declare(strict_types=1);

namespace App\validators\common\utilities;

/**
 * StringsBaseUtility
 *
 * Provides methods for basic string validations:
 * - Length checking
 * - Email format checking
 * - Allowed value checking
 * - Alphanumeric content checking
 *
 * @category Validators
 * @package  IRTF
 * @version  1.0.0
 */
class StringsBaseUtility
{
    /**
     * Validates that a string does not exceed the specified maximum length.
     *
     * @param ValidationResult  $result     The ValidationResult instance to update.
     * @param mixed             $value      The value to validate.
     * @param string            $fieldKey   The field key associated with the value.
     * @param int               $maxLength  The maximum allowed string length.
     *
     * @return ValidationResult Updated ValidationResult containing either validation errors or the validated value.
     */
    public static function validateStringLength(
        ValidationResult $result,
        $value,
        string $fieldKey,
        int $maxLength
    ): ValidationResult {
        // Explicitly cast value to string type
        $value = (string) $value;

        // Validate string length
        if (strlen($value) > $maxLength) {
            return $result->addFieldError(
                $fieldKey,
                "Invalid value. Must be 1-{$maxLength} characters."
            );
        }
        // Store validated value
        return $result->setFieldValue($fieldKey, $value);
    }

    /**
     * Validates that a string is a properly formatted email address.
     *
     * @param ValidationResult  $result     The ValidationResult instance to update.
     * @param mixed             $value      The email address value to validate.
     * @param string            $fieldKey   The field key associated with the value.
     *
     * @return ValidationResult Updated ValidationResult containing either validation errors or the validated email address.
     */
    public static function validateEmailFormat(
        ValidationResult $result,
        $value,
        string $fieldKey
    ): ValidationResult {
        // Explicitly cast value to string type
        $value = (string) $value;

        // Validate email format
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return $result->addFieldError(
                $fieldKey,
                "Invalid email format."
            );
        }
        // Store validated value
        return $result->setFieldValue($fieldKey, $value);
    }

    /**
     * Validates that a string matches one of the allowed values.
     *
     * @param ValidationResult  $result         The ValidationResult instance to update.
     * @param mixed             $value          The value to validate.
     * @param string            $fieldKey       The field key associated with the value.
     * @param array             $allowedValues  The list of allowed string values.
     *
     * @return ValidationResult Updated ValidationResult containing either validation errors or the validated value.
     */
    public static function validateStringInSet(
        ValidationResult $result,
        $value,
        string $fieldKey,
        array $allowedValues
    ): ValidationResult {
        // Explicitly cast value to string type
        $value = (string) $value;

        // Validate existence of value in the allowed set
        if (!in_array($value, $allowedValues, true)) {
            return $result->addFieldError(
                $fieldKey,
                "Invalid value."
            );
        }
        // Store validated value
        return $result->setFieldValue($fieldKey, $value);
    }

    /**
     * Validates that a string contains only alphanumeric characters.
     *
     * @param ValidationResult  $result     The ValidationResult instance to update.
     * @param mixed             $value      The value to validate.
     * @param string            $fieldKey   The field key associated with the value.
     *
     * @return ValidationResult Updated ValidationResult containing either validation errors or the validated value.
     */
    public static function validateAlphanumeric(
        ValidationResult $result,
        $value,
        string $fieldKey
    ): ValidationResult {
        // Explicitly cast value to string type
        $value = (string) $value;

        // Validate value contents is alphanumeric
        if (!ctype_alnum($value)) {
            return $result->addFieldError(
                $fieldKey,
                "Invalid value. Must be alphanumeric."
            );
        }
        // Store validated value
        return $result->setFieldValue($fieldKey, $value);
    }
}
