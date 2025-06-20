<?php

declare(strict_types=1);

namespace App\validators\common\utilities;

use App\validators\common\ValidationResult;

/**
 * NumericCompositeUtility
 *
 * Provides composite validations for numeric fields that require
 * specific real-world constraints beyond basic integer/float checks.
 *
 * @category Validators
 * @package  IRTF
 * @version  1.0.0
 */
class NumericCompositeUtility
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
    public static function validateShortProgramNumberField(
        ValidationResult $result,
        $value,
        string $fieldKey,
        ?int $minNumber = null,
        ?int $maxNumber = null
    ): ValidationResult {
        // Validate value is an integer
        $res = IntegersBaseUtility::validateInteger(
            $result,
            $value,
            $fieldKey
        );

        // Short-circuit and return if integer validation failed
        if ($res->hasErrors()) {
            return $res;
        }

        // Prepare value for validation
        $value = (int) $value;

        // Clamp the min/max to a sane range just in case
        $minNumber = max(1, $minNumber ?? 1);     // Ensure minimum is at least  1
        $maxNumber = min(999, $maxNumber ?? 999); // Ensure maximum is at most 999

        // Validate value is within the necessary bounds
        return IntegersBaseUtility::validateIntegerRange(
            $res,
            $value,
            $fieldKey,
            $minNumber,
            $maxNumber
        );
    }
}
