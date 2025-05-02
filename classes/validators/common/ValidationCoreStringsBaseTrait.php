<?php

declare(strict_types=1);

namespace App\validators\common;

use App\validators\common\StringsBaseUtility;

/**
 * ValidationCoreStringsBaseTrait
 *
 * Provides wrapper methods for StringsBaseUtility functionality.
 * Covers string length, formatting, and character set validation.
 *
 * @category Validation
 * @package  IRTF
 * @version  1.0.0
 */
trait ValidationCoreStringsBaseTrait
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
    public function validateStringLength(
        ValidationResult $result,
        $value,
        string $fieldKey,
        int $maxLength
    ): ValidationResult {
        return StringsBaseUtility::validateStringLength(
            $result,
            $value,
            $fieldKey,
            $maxLength
        );
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
    public function validateEmailFormat(
        ValidationResult $result,
        $value,
        string $fieldKey
    ): ValidationResult {
        return StringsBaseUtility::validateEmailFormat(
            $result,
            $value,
            $fieldKey
        );
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
    public function validateStringInSet(
        ValidationResult $result,
        $value,
        string $fieldKey,
        array $allowedValues
    ): ValidationResult {
        return StringsBaseUtility::validateStringInSet(
            $result,
            $value,
            $fieldKey,
            $allowedValues
        );
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
    public function validateAlphanumeric(
        ValidationResult $result,
        $value,
        string $fieldKey
    ): ValidationResult {
        return StringsBaseUtility::validateAlphanumeric(
            $result,
            $value,
            $fieldKey
        );
    }
}
