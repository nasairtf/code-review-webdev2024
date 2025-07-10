<?php

declare(strict_types=1);

namespace App\validators\common\core;

use App\validators\common\ValidationResult;
use App\validators\common\utilities\SelectionBaseUtility;

/**
 * SelectionBaseTrait
 *
 * Provides wrapper methods for SelectionBaseUtility functionality.
 * Enables option validation by value or key against allowed selections.
 *
 * @category Validation
 * @package  IRTF
 * @version  1.0.0
 */
trait SelectionBaseTrait
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
    public function validateSelection(
        ValidationResult $result,
        array $values,
        string $fieldKey,
        array $allowedValues,
        bool $validateByKey = true
    ): ValidationResult {
        return SelectionBaseUtility::validateSelection(
            $result,
            $values,
            $fieldKey,
            $allowedValues,
            $validateByKey
        );
    }
}
