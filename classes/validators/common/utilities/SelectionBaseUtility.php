<?php

declare(strict_types=1);

namespace App\validators\common\utilities;

use App\validators\common\ValidationResult;

/**
 * SelectionBaseUtility
 *
 * Provides basic validation for a set of user-selected options,
 * supporting both key-based and value-based validation against
 * an allowed options list.
 *
 * @category Validators
 * @package  IRTF
 * @version  1.0.0
 */
class SelectionBaseUtility
{
    /**
     * Validates that a set of submitted options match allowed values.
     *
     * @param ValidationResult  $result         The ValidationResult instance to update.
     * @param array             $values         The submitted option(s) to validate.
     * @param string            $fieldKey       The field key associated with these values.
     * @param array             $allowedValues  The full allowed options array.
     * @param bool              $validateByKey  Whether to validate by allowed array keys (true) or values (false).
     *
     * @return ValidationResult Updated ValidationResult containing any errors or validated selections.
     */
    public static function validateSelection(
        ValidationResult $result,
        array $values,
        string $fieldKey,
        array $allowedValues,
        bool $validateByKey = true
    ): ValidationResult {
        // Determine allowed set (keys or values)
        $allowedSet = $validateByKey
            ? array_keys($allowedValues)
            : array_values($allowedValues);

        // Validate individual options
        $validatedOptions = [];
        foreach ($values as $value) {
            if (!in_array($value, $allowedSet, true)) {
                $result = $result->addFieldError(
                    $fieldKey,
                    sprintf("Invalid option: '%s'.", $value)
                );
            } else {
                $validatedOptions[] = (string) $value;
            }
        }

        // Store values if validation passed
        if (!$result->hasFieldErrors($fieldKey)) {
            $result = $result->setFieldValue($fieldKey, $validatedOptions);
        }

        // Return the full result
        return $result;
    }
}
