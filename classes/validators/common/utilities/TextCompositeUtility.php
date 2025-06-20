<?php

declare(strict_types=1);

namespace App\validators\common\utilities;

use App\validators\common\ValidationResult;

/**
 * TextCompositeUtility
 *
 * Provides composite validation methods for common text fields, combining
 * atomic string validations into higher-order rules for:
 * - Names
 * - Usernames
 * - Shell paths
 * - Email addresses
 * - Semester tags and codes
 * - Session codes
 * - Freeform short and long text fields
 *
 * This class validates structure and content without applying any transformations
 * (e.g., escaping, formatting). All transformations must occur at higher layers.
 *
 * @category Validators
 * @package  IRTF
 * @version  1.0.0
 */
class TextCompositeUtility
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
    public static function validateTextField(
        ValidationResult $result,
        $value,
        string $fieldKey,
        int $maxLength
    ): ValidationResult {
        // Validate string length
        return StringsBaseUtility::validateStringLength(
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
    public static function validateUnixUsernameField(
        ValidationResult $result,
        $value,
        string $fieldKey,
        int $maxLength = 12
    ): ValidationResult {
        // Validate string length
        $res = self::validateTextField(
            $result,
            $value,
            $fieldKey,
            $maxLength
        );

        // Validate string contents
        return StringsBaseUtility::validateAlphanumeric(
            $res,
            $value,
            $fieldKey
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
    public static function validateShellField(
        ValidationResult $result,
        $value,
        string $fieldKey,
        ?array $allowedValues = null
    ): ValidationResult {
        // Prepare shells list
        $validShells = $allowedValues ?? [
            '/bin/bash',
            '/bin/csh',
            '/bin/sh',
            '/bin/tcsh',
            '/bin/zsh',
        ];

        // Validate known shells
        return StringsBaseUtility::validateStringInSet(
            $result,
            $value,
            $fieldKey,
            $validShells
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
    public static function validateEmailField(
        ValidationResult $result,
        $value,
        string $fieldKey,
        ?int $maxLength = null,
        ?bool $isAlphanumeric = null
    ): ValidationResult {
        // Validate email format
        $res = StringsBaseUtility::validateEmailFormat(
            $result,
            $value,
            $fieldKey
        );

        // Short-circuit and return if format validation failed
        if ($res->hasErrors()) {
            return $res;
        }

        // Validate optional length constraint
        if  ($maxLength !== null) {
            $res = self::validateTextField(
                $res,
                $value,
                $fieldKey,
                $maxLength
            );

            // Short-circuit and return if length validation failed
            if ($res->hasErrors()) {
                return $res;
            }
        }

        // Validate optional alphanumeric constraint
        if  ($isAlphanumeric === true) {
            $res = StringsBaseUtility::validateAlphanumeric(
                $res,
                $value,
                $fieldKey
            );

            // Skip short-circuit here since next statement returns the result regardless
        }

        // Return validation result
        return $res;
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
    public static function validateSemesterTagField(
        ValidationResult $result,
        $value,
        string $fieldKey
    ): ValidationResult {
        // Validate tag length (must be exactly 1 character)
        $res = self::validateTextField(
            $result,
            $value,
            $fieldKey,
            1
        );

        // Short-circuit and return if length validation failed
        if ($res->hasErrors()) {
            return $res;
        }

        // Prepare value for validation
        $value = strtoupper((string) $value);

        // Prepare list for validation
        $validTags = [
            'A',
            'B',
        ];

        // Validate tag
        return StringsBaseUtility::validateStringInSet(
            $res,
            $value,
            $fieldKey,
            $validTags
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
    public static function validateSemesterField(
        ValidationResult $result,
        $value,
        string $fieldKey,
        int $minYear = 2000,
        ?int $maxYear = null
    ): ValidationResult {
        // Validate field length (must be exactly 5 characters)
        $res = self::validateTextField(
            $result,
            $value,
            $fieldKey,
            5
        );

        // Short-circuit and return if length validation failed
        if ($res->hasErrors()) {
            return $res;
        }

        // Prepare value for validation
        $value = strtoupper((string) $value);

        // Prepare year and tag for validation
        $year = substr($value, 0, 4);
        $tag  = substr($value, 4);

        // Validate year portion of semester string
        $res = DateTimeBaseUtility::validateYear(
            $res,
            $year,
            "{$fieldKey}_year",
            $minYear,
            $maxYear
        );

        // Short-circuit and return if year validation failed
        if ($res->hasErrors()) {
            return $res;
        }

        // Validate tag portion of semester string
        $res = self::validateSemesterTagField(
            $res,
            $tag,
            "{$fieldKey}_tag"
        );

        // Short-circuit and return if tag validation failed
        if ($res->hasErrors()) {
            return $res;
        }

        // Store validated full value
        return $res->setFieldValue($fieldKey, $value);
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
    public static function validateProgramNumberField(
        ValidationResult $result,
        $value,
        string $fieldKey,
        int $minYear = 2000,
        ?int $maxYear = null
    ): ValidationResult {
        // Validate field length (must be exactly 8 characters)
        $res = self::validateTextField(
            $result,
            $value,
            $fieldKey,
            8
        );

        // Short-circuit and return if length validation failed
        if ($res->hasErrors()) {
            return $res;
        }

        // Prepare value for validation
        $value = strtoupper((string) $value);

        // Prepare year, tag, number for validation
        $year = substr($value, 0, 4);
        $tag  = substr($value, 4, 1);
        $num  = substr($value, 5, 3);

        // Validate year portion of program number string
        $res = DateTimeBaseUtility::validateYear(
            $res,
            $year,
            "{$fieldKey}_year",
            $minYear,
            $maxYear
        );

        // Short-circuit and return if year validation failed
        if ($res->hasErrors()) {
            return $res;
        }

        // Validate tag portion of program number string
        $res = self::validateSemesterTagField(
            $res,
            $tag,
            "{$fieldKey}_tag"
        );

        // Short-circuit and return if tag validation failed
        if ($res->hasErrors()) {
            return $res;
        }

        // Validate number portion of program number string
        $res = NumericCompositeUtility::validateShortProgramNumberField(
            $res,
            $num,
            "{$fieldKey}_number"
        );

        // Short-circuit and return if number validation failed
        if ($res->hasErrors()) {
            return $res;
        }

        // Store validated full value
        return $res->setFieldValue($fieldKey, $value);
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
    public static function validateSessionCodeField(
        ValidationResult $result,
        $value,
        string $fieldKey
    ): ValidationResult {
        // Prepare value for validation
        $value = (string) $value;

        // Engineering/project accounts
        // Check known engineering codes first
        if (self::isEngineeringCode($value)) {
            return $result->setFieldValue($fieldKey, $value);
        }

        // Guest program accounts
        // Otherwise, must be 10-char alphanumeric

        // Validate string length
        $res = self::validateTextField(
            $result,
            $value,
            $fieldKey,
            10
        );

        // Short-circuit and return if length validation failed
        if ($res->hasErrors()) {
            return $res;
        }

        // Validate string contents
        return StringsBaseUtility::validateAlphanumeric(
            $res,
            $value,
            $fieldKey
        );
    }

    /**
     * Checks if a value is a known engineering code.
     *
     * @param mixed        $value          The value to check.
     * @param array|null   $allowedValues  Optional override list of engineering codes.
     *
     * @return bool True if the value matches an engineering code, false otherwise.
     */
    private static function isEngineeringCode(
        $value,
        ?array $allowedValues = null
    ): bool {
        // Prepare value for validation
        $value = (string) $value;

        // Prepare codes for verification
        $engineeringCodes = $allowedValues ?? [
            'tisanpwd',
            'wbtcorar'
        ];

        // Verify value against allowed codes
        return in_array($value, $engineeringCodes, true);
    }
}
