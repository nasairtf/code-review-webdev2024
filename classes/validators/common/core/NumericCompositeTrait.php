<?php

declare(strict_types=1);

namespace App\validators\common\core;

use App\validators\common\NumericCompositeUtility;

/**
 * ValidationCoreNumericCompositeTrait
 *
 * Provides wrapper methods for NumericCompositeUtility functionality.
 * Encapsulates domain-specific numeric validations (e.g., program number ranges).
 *
 * @category Validation
 * @package  IRTF
 * @version  1.0.0
 */
trait NumericCompositeTrait
{
    /**
     * Validates a short program number field (integer between 1 and 999).
     *
     * @param ValidationResult  $result     The ValidationResult instance to update.
     * @param mixed             $value      The program number to validate.
     * @param string            $fieldKey   The field key associated with the program number.
     * @param int|null          $minNumber  Optional minimum allowed program number (default: 1).
     * @param int|null          $maxNumber  Optional maximum allowed program number (default: 999).
     *
     * @return ValidationResult Updated ValidationResult containing either validation errors or the validated number.
     */
    public function validateShortProgramNumberField(
        ValidationResult $result,
        $value,
        string $fieldKey,
        ?int $minNumber = null,
        ?int $maxNumber = null
    ): ValidationResult {
        return NumericCompositeUtility::validateShortProgramNumberField(
            $result,
            $value,
            $fieldKey,
            $minNumber,
            $maxNumber
        );
    }
}
