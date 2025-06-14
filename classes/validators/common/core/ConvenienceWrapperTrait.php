<?php

declare(strict_types=1);

namespace App\validators\common\core;

use App\validators\common\IntegersBaseUtility;
use App\validators\common\TextCompositeUtility;
use App\validators\common\SelectionCompositeUtility;

/**
 * ValidationCoreConvenienceWrapperTrait
 *
 * Provides convenience wrappers for application-specific validation tasks
 * that do not map directly to a single utility class. Designed to reduce
 * boilerplate in common form and script validation cases.
 *
 * @category Validation
 * @package  IRTF
 * @version  1.0.0
 */
trait ConvenienceWrapperTrait
{
    /**
     * Validates an ObsAppID field as a basic integer.
     *
     * @param ValidationResult  $result    The ValidationResult instance to update.
     * @param mixed             $value     The ObsAppID to validate.
     * @param string            $fieldKey  The field key associated with the ObsAppID.
     *
     * @return ValidationResult Updated ValidationResult containing either validation errors or the validated ObsAppID.
     */
    public function validateObsAppIDField(
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
     * Validates a short text field (default max 70 characters).
     *
     * @param ValidationResult  $result   The ValidationResult instance to update.
     * @param mixed             $value    The text value to validate.
     * @param string            $fieldKey The field key associated with the value.
     *
     * @return ValidationResult Updated ValidationResult containing validation results.
     */
    public function validateShortTextField(
        ValidationResult $result,
        $value,
        string $fieldKey
    ): ValidationResult {
        // Validate string length
        return TextCompositeUtility::validateTextField(
            $result,
            $value,
            $fieldKey,
            70
        );
    }

    /**
     * Validates a long text field (default max 500 characters).
     *
     * @param ValidationResult  $result   The ValidationResult instance to update.
     * @param mixed             $value    The text value to validate.
     * @param string            $fieldKey The field key associated with the value.
     *
     * @return ValidationResult Updated ValidationResult containing validation results.
     */
    public function validateLongTextField(
        ValidationResult $result,
        $value,
        string $fieldKey
    ): ValidationResult {
        // Validate string length
        return TextCompositeUtility::validateTextField(
            $result,
            $value,
            $fieldKey,
            500
        );
    }

    /**
     * Validates a personal name field (default max 70 characters).
     *
     * @param ValidationResult  $result    The ValidationResult instance to update.
     * @param mixed             $value     The name value to validate.
     * @param string            $fieldKey  The field key associated with the value.
     * @param int               $maxLength Optional custom maximum length (default: 70).
     *
     * @return ValidationResult Updated ValidationResult containing validation results.
     */
    public function validateNameField(
        ValidationResult $result,
        $value,
        string $fieldKey,
        int $maxLength = 70
    ): ValidationResult {
        // Validate string length
        return TextCompositeUtility::validateTextField(
            $result,
            $value,
            $fieldKey,
            $maxLength
        );
    }

    /**
     * Validates the location field (Remote or Onsite selection).
     *
     * @param ValidationResult  $result   The ValidationResult instance to update.
     * @param mixed             $value    The submitted location value.
     * @param string            $fieldKey The field key associated with the value.
     *
     * @return ValidationResult Updated ValidationResult containing either validation errors or the validated location.
     */
    public function validateLocation(
        ValidationResult $result,
        $value,
        string $fieldKey
    ): ValidationResult {
        // Validate value is within the necessary bounds
        return SelectionCompositeUtility::validateBinaryOption(
            $result,
            $value,
            $fieldKey
        );
    }

    /**
     * Validates the email send type field (Real vs Dummy emails).
     *
     * @param ValidationResult  $result   The ValidationResult instance to update.
     * @param mixed             $value    The submitted email send type value.
     * @param string            $fieldKey The field key associated with the value.
     *
     * @return ValidationResult Updated ValidationResult containing either validation errors or the validated email send type.
     */
    public function validateEmailsSendType(
        ValidationResult $result,
        $value,
        string $fieldKey
    ): ValidationResult {
        // Validate value is within the necessary bounds
        return SelectionCompositeUtility::validateBinaryOption(
            $result,
            $value,
            $fieldKey
        );
    }

    /**
     * Validates the interval unit type field (Days or Weeks).
     *
     * @param ValidationResult  $result   The ValidationResult instance to update.
     * @param mixed             $value    The submitted unit type value.
     * @param string            $fieldKey The field key associated with the value.
     *
     * @return ValidationResult Updated ValidationResult containing either validation errors or the validated unit type.
     */
    public function validateIntervalUnitType(
        ValidationResult $result,
        $value,
        string $fieldKey
    ): ValidationResult {
        // Validate value is within the necessary bounds
        return SelectionCompositeUtility::validateBinaryOption(
            $result,
            $value,
            $fieldKey
        );
    }

    /**
     * Validates an on/off radio field (typically used for switches or simple toggles).
     *
     * @param ValidationResult  $result   The ValidationResult instance to update.
     * @param mixed             $value    The submitted on/off value.
     * @param string            $fieldKey The field key associated with the value.
     *
     * @return ValidationResult Updated ValidationResult containing either validation errors or the validated radio value.
     */
    public function validateOnOffRadio(
        ValidationResult $result,
        $value,
        string $fieldKey
    ): ValidationResult {
        // Validate value is within the necessary bounds
        return SelectionCompositeUtility::validateBinaryOption(
            $result,
            $value,
            $fieldKey
        );
    }
}
