<?php

declare(strict_types=1);

namespace App\validators\common\core;

use App\validators\common\ValidationResult;
use App\validators\common\utilities\SelectionCompositeUtility;

/**
 * ValidationCoreSelectionCompositeTrait
 *
 * Provides wrapper methods for SelectionCompositeUtility functionality.
 * Supports binary options and scaled ratings used across multiple validation contexts.
 *
 * @category Validation
 * @package  IRTF
 * @version  1.0.0
 */
trait SelectionCompositeTrait
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
    public function validateRating(
        ValidationResult $result,
        $value,
        string $fieldKey,
        bool $addNA = false
    ): ValidationResult {
        return SelectionCompositeUtility::validateRating(
            $result,
            $value,
            $fieldKey,
            $addNA
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
    public function validateBinaryOption(
        ValidationResult $result,
        $value,
        string $fieldKey
    ): ValidationResult {
        return SelectionCompositeUtility::validateBinaryOption(
            $result,
            $value,
            $fieldKey
        );
    }
}
