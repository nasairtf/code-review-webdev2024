<?php

declare(strict_types=1);

namespace App\validators\common\core;

use App\validators\common\ValidationResult;
use App\validators\common\utilities\SelectionCompositeUtility;

/**
 * SelectionCompositeTrait
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

    /**
     * Validates that at least one valid instrument is selected from the
     * facility and/or visitor instrument inputs.
     *
     * Filters out 'none' from visitor inputs, consolidates selected values,
     * and verifies all submitted instruments are allowed.
     * If required and no valid selections are found, an error is recorded.
     *
     * @param ValidationResult $result               ValidationResult instance to record results in.
     * @param array            $facilityValues       Submitted values from facility instrument checkboxes.
     * @param array            $visitorValues        Submitted values from visitor instrument pulldown.
     * @param string           $fieldKey             Logical field name to associate errors and values with.
     * @param array            $allowedFacilityValues Allowed facility instrument keys.
     * @param array            $allowedVisitorValues  Allowed visitor instrument keys (may include 'none').
     * @param bool             $isRequired           Whether at least one valid selection is required.
     *
     * @return ValidationResult Updated result object with validation outcome.
     */
    public function validateInstruments(
        ValidationResult $result,
        array $facilityValues,
        array $visitorValues,
        string $fieldKey,
        array $allowedFacilityValues,
        array $allowedVisitorValues,
        bool $isRequired = false
    ): ValidationResult {
        return SelectionCompositeUtility::validateInstruments(
            $result,
            $facilityValues,
            $visitorValues,
            $fieldKey,
            $allowedFacilityValues,
            $allowedVisitorValues,
            $isRequired
        );
    }
}
