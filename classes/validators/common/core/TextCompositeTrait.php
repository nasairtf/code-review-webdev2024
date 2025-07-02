<?php

declare(strict_types=1);

namespace App\validators\common\core;

use App\validators\common\ValidationResult;
use App\validators\common\utilities\TextCompositeUtility;

/**
 * TextCompositeTrait
 *
 * Provides wrapper methods for TextCompositeUtility functionality.
 * Handles multi-step validations on user-facing text fields (e.g., usernames, emails, session codes).
 *
 * @category Validation
 * @package  IRTF
 * @version  1.0.0
 */
trait TextCompositeTrait
{
    /**
     * Validates that a text field does not exceed the specified maximum length.
     *
     * @param ValidationResult  $result    The ValidationResult instance to update.
     * @param mixed             $value     The text value to validate.
     * @param string            $fieldKey  The field key associated with the value.
     * @param int               $maxLength Maximum allowed string length.
     *
     * @return ValidationResult Updated ValidationResult containing validation results.
     */
    public function validateTextField(
        ValidationResult $result,
        $value,
        string $fieldKey,
        int $maxLength
    ): ValidationResult {
        return TextCompositeUtility::validateTextField(
            $result,
            $value,
            $fieldKey,
            $maxLength
        );
    }

    /**
     * Validates a Unix username field (alphanumeric, max 12 characters).
     *
     * @param ValidationResult  $result    The ValidationResult instance to update.
     * @param mixed             $value     The username value to validate.
     * @param string            $fieldKey  The field key associated with the value.
     * @param int               $maxLength Optional custom maximum length (default: 12).
     *
     * @return ValidationResult Updated ValidationResult containing validation results.
     */
    public function validateUnixUsernameField(
        ValidationResult $result,
        $value,
        string $fieldKey,
        int $maxLength = 12
    ): ValidationResult {
        return TextCompositeUtility::validateUnixUsernameField(
            $result,
            $value,
            $fieldKey,
            $maxLength
        );
    }

    /**
     * Validates a shell path field against a list of allowed shells.
     *
     * @param ValidationResult  $result        The ValidationResult instance to update.
     * @param mixed             $value         The shell path to validate.
     * @param string            $fieldKey      The field key associated with the value.
     * @param array|null        $allowedValues Optional list of allowed shell paths.
     *
     * @return ValidationResult Updated ValidationResult containing validation results.
     */
    public function validateShellField(
        ValidationResult $result,
        $value,
        string $fieldKey,
        ?array $allowedValues = null
    ): ValidationResult {
        return TextCompositeUtility::validateShellField(
            $result,
            $value,
            $fieldKey,
            $allowedValues
        );
    }

    /**
     * Validates an email address format, with optional max length and optional alphanumeric-only constraint.
     *
     * @param ValidationResult  $result         The ValidationResult instance to update.
     * @param mixed             $value          The email value to validate.
     * @param string            $fieldKey       The field key associated with the value.
     * @param int|null          $maxLength      Optional maximum allowed length.
     * @param bool|null         $isAlphanumeric Optional alphanumeric-only constraint.
     *
     * @return ValidationResult Updated ValidationResult containing validation results.
     */
    public function validateEmailField(
        ValidationResult $result,
        $value,
        string $fieldKey,
        ?int $maxLength = null,
        ?bool $isAlphanumeric = null
    ): ValidationResult {
        return TextCompositeUtility::validateEmailField(
            $result,
            $value,
            $fieldKey,
            $maxLength,
            $isAlphanumeric
        );
    }

    /**
     * Validates a semester tag field (must be 'A' or 'B').
     *
     * @param ValidationResult  $result   The ValidationResult instance to update.
     * @param mixed             $value    The semester tag to validate.
     * @param string            $fieldKey The field key associated with the value.
     *
     * @return ValidationResult Updated ValidationResult containing validation results.
     */
    public function validateSemesterTagField(
        ValidationResult $result,
        $value,
        string $fieldKey
    ): ValidationResult {
        return TextCompositeUtility::validateSemesterTagField(
            $result,
            $value,
            $fieldKey
        );
    }

    /**
     * Validates a semester code field (e.g., '2024A').
     *
     * Validates year portion and tag portion separately.
     *
     * @param ValidationResult  $result   The ValidationResult instance to update.
     * @param mixed             $value    The semester code to validate.
     * @param string            $fieldKey The field key associated with the value.
     * @param int               $minYear  Minimum acceptable year (default: 2000).
     * @param int|null          $maxYear  Optional maximum acceptable year (default: current year + 5).
     *
     * @return ValidationResult Updated ValidationResult containing validation results.
     */
    public function validateSemesterField(
        ValidationResult $result,
        $value,
        string $fieldKey,
        int $minYear = 2000,
        ?int $maxYear = null
    ): ValidationResult {
        return TextCompositeUtility::validateSemesterField(
            $result,
            $value,
            $fieldKey,
            $minYear,
            $maxYear
        );
    }

    /**
     * Validates a full program number field (format: YYYYSNNN).
     *
     * Program number consists of:
     * - Year (4 digits, validated as a year)
     * - Semester tag (1 character: 'A' or 'B')
     * - Program number (3 digits, 1–999)
     *
     * Ensures the entire string is exactly 8 characters long and each component is validated separately.
     *
     * @param ValidationResult  $result   The ValidationResult instance to update.
     * @param mixed             $value    The program number value to validate.
     * @param string            $fieldKey The field key associated with the program number.
     * @param int               $minYear  Minimum allowed year for the program number (default: 2000).
     * @param int|null          $maxYear  Optional maximum allowed year (default: current year + 5).
     *
     * @return ValidationResult Updated ValidationResult containing either validation errors or the validated full program number.
     */
    public function validateProgramNumberField(
        ValidationResult $result,
        $value,
        string $fieldKey,
        int $minYear = 2000,
        ?int $maxYear = null
    ): ValidationResult {
        return TextCompositeUtility::validateProgramNumberField(
            $result,
            $value,
            $fieldKey,
            $minYear,
            $maxYear
        );
    }

    /**
     * Validates a session code field (either engineering code or 10-char alphanumeric guest code).
     *
     * @param ValidationResult  $result   The ValidationResult instance to update.
     * @param mixed             $value    The session code to validate.
     * @param string            $fieldKey The field key associated with the value.
     *
     * @return ValidationResult Updated ValidationResult containing validation results.
     */
    public function validateSessionCodeField(
        ValidationResult $result,
        $value,
        string $fieldKey
    ): ValidationResult {
        return TextCompositeUtility::validateSessionCodeField(
            $result,
            $value,
            $fieldKey
        );
    }
}
