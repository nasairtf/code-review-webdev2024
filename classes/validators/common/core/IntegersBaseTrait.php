<?php

declare(strict_types=1);

namespace App\validators\common\core;

use App\validators\common\IntegersBaseUtility;

/**
 * ValidationCoreIntegersBaseTrait
 *
 * Provides wrapper methods for IntegersBaseUtility functionality.
 * Supports integer validation and optional range enforcement.
 *
 * @category Validation
 * @package  IRTF
 * @version  1.0.0
 */
trait IntegersBaseTrait
{
    /**
     * Validates that a value is a proper integer.
     *
     * @param ValidationResult  $result    The ValidationResult instance to update.
     * @param mixed             $value     The value to validate.
     * @param string            $fieldKey  The field key associated with the value.
     *
     * @return ValidationResult Updated ValidationResult containing either validation errors or the validated integer value.
     */
    public function validateInteger(
        ValidationResult $result,
        $value,
        string $fieldKey
    ): ValidationResult {
        return IntegersBaseUtility::validateInteger(
            $result,
            $value,
            $fieldKey
        );
    }

    /**
     * Validates that an integer is within an optional minimum and/or maximum range.
     *
     * @param ValidationResult  $result    The ValidationResult instance to update.
     * @param int               $value     The integer value to validate.
     * @param string            $fieldKey  The field key associated with the value.
     * @param int|null          $minValue  Optional minimum allowed value.
     * @param int|null          $maxValue  Optional maximum allowed value.
     *
     * @return ValidationResult Updated ValidationResult containing either validation errors or the validated value.
     */
    public function validateIntegerRange(
        ValidationResult $result,
        int $value,
        string $fieldKey,
        ?int $minValue = null,
        ?int $maxValue = null
    ): ValidationResult {
        return IntegersBaseUtility::validateIntegerRange(
            $result,
            $value,
            $fieldKey,
            $minValue,
            $maxValue
        );
    }
}
