<?php

declare(strict_types=1);

namespace App\validators\common\core;

use App\validators\common\FloatsBaseUtility;

/**
 * ValidationCoreFloatsBaseTrait
 *
 * Provides wrapper methods for FloatsBaseUtility functionality.
 * Supports float validation and optional range enforcement.
 *
 * @category Validation
 * @package  IRTF
 * @version  1.0.0
 */
trait FloatsBaseTrait
{
    /**
     * Validates that a value is a proper float.
     *
     * @param ValidationResult  $result    The ValidationResult instance to update.
     * @param mixed             $value     The value to validate.
     * @param string            $fieldKey  The field key associated with the value.
     *
     * @return ValidationResult Updated ValidationResult containing either validation errors or the validated float value.
     */
    public function validateFloat(
        ValidationResult $result,
        $value,
        string $fieldKey
    ): ValidationResult {
        return FloatsBaseUtility::validateFloat(
            $result,
            $value,
            $fieldKey
        );
    }

    /**
     * Validates that a float is within an optional minimum and/or maximum range.
     *
     * @param ValidationResult  $result    The ValidationResult instance to update.
     * @param float             $value     The float value to validate.
     * @param string            $fieldKey  The field key associated with the value.
     * @param float|null        $minValue  Optional minimum allowed value.
     * @param float|null        $maxValue  Optional maximum allowed value.
     *
     * @return ValidationResult Updated ValidationResult containing either validation errors or the validated value.
     */
    public function validateFloatRange(
        ValidationResult $result,
        float $value,
        string $fieldKey,
        ?float $minValue = null,
        ?float $maxValue = null
    ): ValidationResult {
        return FloatsBaseUtility::validateFloatRange(
            $result,
            $value,
            $fieldKey,
            $minValue,
            $maxValue
        );
    }
}
