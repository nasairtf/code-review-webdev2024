<?php

declare(strict_types=1);

namespace App\validators\common;

use App\validators\common\RequiredFieldUtility;

/**
 * ValidationCoreRequiredFieldTrait
 *
 * Provides wrapper methods for RequiredFieldUtility functionality.
 * Enables consistent handling of presence and non-empty validation across all forms.
 *
 * @category Validation
 * @package  IRTF
 * @version  1.0.0
 */
trait ValidationCoreRequiredFieldTrait
{
    /**
     * Validates that a required field is set and contains meaningful content.
     *
     * Handles special cases for strings, arrays, and non-numeric empty values.
     * If $required is true and the value is empty/null, an error is recorded under $fieldKey.
     * Otherwise, the original $value is stored in the ValidationResult.
     *
     * @param ValidationResult  $result       The ValidationResult instance to update.
     * @param mixed             $value        The field value to check.
     * @param bool              $required     Whether this field must be non-empty.
     * @param string            $fieldKey     The field key associated with the value.
     * @param string            $errorMessage The error message if validation fails.
     *
     * @return ValidationResult Updated ValidationResult containing either validation errors or the stored original value.
     */
    public function validateRequiredField(
        ValidationResult $result,
        $value,
        bool $required,
        string $fieldKey,
        string $errorMessage
    ): ValidationResult {
        return RequiredFieldUtility::validateRequired(
            $result,
            $value,
            $required,
            $fieldKey,
            $errorMessage
        );
    }
}
