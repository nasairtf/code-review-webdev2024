<?php

declare(strict_types=1);

namespace App\validators\common\utilities;

use App\validators\common\ValidationResult;

/**
 * SelectionCompositeUtility
 *
 * Provides composite validation methods for fields representing
 * selections, ratings, binary choices, and similar simple selection types.
 *
 * Methods here orchestrate low-level validation into field-specific flows.
 *
 * @category Validators
 * @package  IRTF
 * @version  1.0.0
 */
class SelectionCompositeUtility
{
    /**
     * Validates a rating field (0-5 or 1-5) depending on configuration.
     *
     * @param ValidationResult  $result   The ValidationResult instance to update.
     * @param mixed             $value    The submitted rating value.
     * @param string            $fieldKey Field key associated with the rating.
     * @param bool              $addNA    Whether to allow 'N/A' (rating 0) as a valid choice.
     *
     * @return ValidationResult Updated ValidationResult containing either validation errors or the validated rating.
     */
    public static function validateRating(
        ValidationResult $result,
        $value,
        string $fieldKey,
        bool $addNA = false
    ): ValidationResult {
        // Validate value is an integer
        $res = IntegersBaseUtility::validateInteger(
            $result,
            $value,
            $fieldKey
        );

        // Short-circuit and return if integer validation failed
        if ($res->hasFieldErrors($fieldKey)) {
            return $res;
        }

        // Prepare value for validation
        $preparedValue = [(int) $value];

        // Prepare ratings list for validation
        $allowedOptions = $addNA
            ? [0 => 0, 1, 2, 3, 4, 5]
            : [1 => 1, 2, 3, 4, 5];

        // Validate value is within the necessary bounds
        return SelectionBaseUtility::validateSelection(
            $res,
            $preparedValue,
            $fieldKey,
            $allowedOptions,
            true
        );
    }

    /**
     * Validates a general binary (0/1) choice field.
     *
     * Shared by fields that expect only a yes/no or true/false selection.
     *
     * @param ValidationResult  $result   The ValidationResult instance to update.
     * @param mixed             $value    The submitted binary choice value.
     * @param string            $fieldKey The field key associated with the value.
     *
     * @return ValidationResult Updated ValidationResult containing either validation errors or the validated selection.
     */
    public static function validateBinaryOption(
        ValidationResult $result,
        $value,
        string $fieldKey
    ): ValidationResult {
        // Validate value is an integer
        $res = IntegersBaseUtility::validateInteger(
            $result,
            $value,
            $fieldKey
        );

        // Short-circuit and return if integer validation failed
        if ($res->hasFieldErrors($fieldKey)) {
            return $res;
        }

        // Prepare value for validation
        $preparedValue = [(int) $value];

        // Prepare binary options list for validation
        $allowedOptions = [0, 1];

        // Validate value is within the necessary bounds
        return SelectionBaseUtility::validateSelection(
            $res,
            $preparedValue,
            $fieldKey,
            $allowedOptions,
            true
        );
    }
}
