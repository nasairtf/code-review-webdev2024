<?php

declare(strict_types=1);

namespace App\validators\forms;

use Exception;
use App\exceptions\ValidationException;
use App\core\common\CustomDebug as Debug;
use App\core\irtf\IrtfUtilities;

/**
 * Base validator class for handling common form validation logic.
 *
 * This class provides core validation methods for validating input data
 * across multiple forms. It includes utilities for validating selections,
 * dates, names, emails, ratings, and text fields, as well as helper methods
 * for transforming and formatting data.
 *
 * @category Validators
 * @package  IRTF
 * @version  1.0.0
 */

class BaseFormValidator
{
    /**
     * @var Debug Instance of Debug for logging and debugging purposes.
     */
    protected $debug;

    /**
     * @var array Array of validation errors, keyed by field name.
     */
    protected $errors = [];

    /**
     * Constructor to initialize the BaseFormValidator with a Debug instance.
     *
     * @param Debug|null $debug Optional. An instance of Debug for logging; defaults to null.
     */
    public function __construct(
        ?Debug $debug = null
    ) {
        // Debug output
        $this->debug = $debug ?? new Debug('default', false, 0);
        $debugHeading = $this->debug->debugHeading("BaseFormValidator", "__construct");
        $this->debug->debug($debugHeading);

        // Constructor completed
        $this->debug->debug("{$debugHeading} -- Parent Validator initialisation complete.");
    }

    // Core Validation Methods

    /**
     * Validates a selection array against allowed options.
     *
     * @param array  $options      Selected options provided by the user.
     * @param array  $allowed      Allowed options for validation.
     * @param string $fieldKey     Key to associate errors with this field.
     * @param bool   $required     Whether the field is required.
     * @param string $errorMessage Error message for invalid selections.
     *
     * @return array|null Validated and escaped options, or null if validation fails.
     *
     * @throws ValidationException If the selection is invalid and the field is required.
     */
    protected function validateSelection(
        array $options,
        array $allowed,
        string $fieldKey = 'selection',
        bool $required = false,
        string $errorMessage = "Invalid selection.",
        bool $validateByKey = true
    ): ?array {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Validator", "validateSelection");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($options, "{$debugHeading} -- options");
        $this->debug->debugVariable($allowed, "{$debugHeading} -- allowed");
        $this->debug->debugVariable($fieldKey, "{$debugHeading} -- fieldKey");
        $this->debug->debugVariable($required, "{$debugHeading} -- required");
        $this->debug->debugVariable($errorMessage, "{$debugHeading} -- errorMessage");
        $this->debug->debugVariable($validateByKey, "{$debugHeading} -- validateByKey");

        // Check if selection is required and no options were selected
        $options = $this->validateRequiredField(
            $options,
            $required,
            $fieldKey,
            "Please make a selection for this field."
        );
        if ($options === null) {
            return null;
        }

        // Determine allowed set (keys or values)
        $allowedSet = $validateByKey ? array_keys($allowed) : array_values($allowed);

        // Validate individual options
        $validatedOptions = [];
        foreach ($options as $option) {
            if (!in_array($option, $allowedSet, true)) {
                $this->errors[$fieldKey][$option] = "{$errorMessage} Value: {$option}";
            } else {
                $validatedOptions[] = IrtfUtilities::escape((string) $option);
            }
        }
        return !empty($validatedOptions) ? $validatedOptions : null;
    }

    /**
     * Validates a name field for length and content.
     *
     * @param string $name     The name input to validate.
     * @param string $fieldKey Key to associate errors with this field.
     * @param bool   $required Whether the field is required.
     *
     * @return string|null Validated and escaped name, or null if validation fails.
     *
     * @throws ValidationException If the name is invalid and the field is required.
     */
    protected function validateName(
        string $name,
        string $fieldKey = 'name',
        bool $required = false,
        ?int $length = null,
        ?string $errorField = null
    ): ?string {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Validator", "validateName");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($name, "{$debugHeading} -- name");
        $this->debug->debugVariable($fieldKey, "{$debugHeading} -- fieldKey");
        $this->debug->debugVariable($required, "{$debugHeading} -- required");
        $this->debug->debugVariable($length, "{$debugHeading} -- length");
        $this->debug->debugVariable($errorField, "{$debugHeading} -- errorField");

        $field = $errorField ?? 'Name';

        // Validate required field
        $name = $this->validateRequiredField(
            $name,
            $required,
            $fieldKey,
            "{$field} is required."
        );
        if ($name === null) {
            return null;
        }

        // Validate fields
        $maxName = $length ?? 70;
        if (strlen($name) > $maxName) {
            $field = strtolower($field);
            $this->errors[$fieldKey] = "Invalid {$field}. Must be 1-{$maxName} characters.";
            return null;
        }
        return IrtfUtilities::escape($name);
    }

    /**
     * Validates a username field for length and content.
     *
     * @param string $username The username input to validate.
     * @param string $fieldKey Key to associate errors with this field.
     * @param bool   $required Whether the field is required.
     *
     * @return string|null Validated and escaped username, or null if validation fails.
     *
     * @throws ValidationException If the username is invalid and the field is required.
     */
    protected function validateUsername(
        string $username,
        string $fieldKey = 'username',
        bool $required = false
    ): ?string {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Validator", "validateUsername");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($username, "{$debugHeading} -- username");
        $this->debug->debugVariable($fieldKey, "{$debugHeading} -- fieldKey");
        $this->debug->debugVariable($required, "{$debugHeading} -- required");

        // Validate field
        return $this->validateName(
            $username,
            $fieldKey,
            $required,
            8,
            "Username"
        );
    }

    protected function validateShell(
        string $shell,
        string $fieldKey = 'shell',
        bool $required = false,
        ?string $errorMessage = null
    ): ?string {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Validator", "validateShell");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($shell, "{$debugHeading} -- shell");
        $this->debug->debugVariable($fieldKey, "{$debugHeading} -- fieldKey");
        $this->debug->debugVariable($required, "{$debugHeading} -- required");
        $this->debug->debugVariable($errorMessage, "{$debugHeading} -- errorMessage");

        // Validate required field
        $shell = $this->validateRequiredField(
            $shell,
            $required,
            $fieldKey,
            "Shell is required."
        );
        if ($shell === null) {
            return null;
        }

        // Validate fields
        $valid_shells = [
            '/bin/bash',
            '/bin/csh',
            '/bin/sh',
            '/bin/tcsh',
            '/bin/zsh',
        ];
        if (!in_array(strtolower($shell), $valid_shells, true)) {
            $this->errors[$fieldKey] = "Invalid shell.";
            return null;
        }
        return strtolower($shell);
    }

    /**
     * Validates the format of an email address.
     *
     * @param string $email     The email address to validate.
     * @param string $fieldKey  Key to associate errors with this field.
     * @param bool   $required  Whether the field is required.
     *
     * @return string|null Validated email address, or null if validation fails.
     *
     * @throws ValidationException If the email is invalid and the field is required.
     */
    protected function validateEmail(
        string $email,
        string $fieldKey = 'email',
        bool $required = false
    ): ?string {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Validator", "validateEmail");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($email, "{$debugHeading} -- email");
        $this->debug->debugVariable($fieldKey, "{$debugHeading} -- fieldKey");
        $this->debug->debugVariable($required, "{$debugHeading} -- required");

        // Validate required field
        $email = $this->validateRequiredField(
            $email,
            $required,
            $fieldKey,
            "Email is required."
        );
        if ($email === null) {
            return null;
        }

        // Validate fields
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$fieldKey] = "Invalid email format.";
            return null;
        }
        return $email;
    }

    /**
     * Validates a semester value.
     *
     * This method ensures the semester is a valid value ('A' or 'B') and handles
     * case insensitivity by converting the value to uppercase. It also checks if the
     * field is required and handles empty input appropriately.
     *
     * @param string      $semester      The semester value to validate.
     * @param string      $fieldKey      Key to associate errors with this field (default: 'semester').
     * @param bool        $required      Whether the field is required (default: false).
     * @param string|null $errorMessage  Optional custom error message to override the default.
     *
     * @return string|null The validated semester as an uppercase string, or null if validation fails.
     *
     * @throws ValidationException If the semester is invalid and the field is required.
     */
    protected function validateSemester(
        string $semester,
        string $fieldKey = 'semester',
        bool $required = false,
        ?string $errorMessage = null
    ): ?string {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Validator", "validateSemester");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($semester, "{$debugHeading} -- semester");
        $this->debug->debugVariable($fieldKey, "{$debugHeading} -- fieldKey");
        $this->debug->debugVariable($required, "{$debugHeading} -- required");
        $this->debug->debugVariable($errorMessage, "{$debugHeading} -- errorMessage");

        // Validate required field
        $semester = $this->validateRequiredField(
            $semester,
            $required,
            $fieldKey,
            "Semester is required."
        );
        if ($semester === null) {
            return null;
        }

        // Validate fields
        if (!in_array(strtoupper($semester), ['A', 'B'], true)) {
            $this->errors[$fieldKey] = "Invalid semester: Must be 'A' or 'B'.";
            return null;
        }
        return strtoupper($semester);
    }

    /**
     * Validates a year value against a specified range.
     *
     * This method ensures the year is an integer within an acceptable range,
     * with optional minimum and maximum year overrides. It also checks if the field
     * is required and handles null or empty input appropriately.
     *
     * By default, the valid year range is 2000 to the next calendar year.
     *
     * @param mixed       $year         The year value to validate. Can be a string, int, or null.
     * @param string      $fieldKey     Key to associate errors with this field (default: 'year').
     * @param bool        $required     Whether the field is required (default: false).
     * @param int|null    $minYear      The minimum valid year for validation (default: 2000).
     * @param int|null    $maxYear      The maximum valid year for validation (default: next calendar year).
     * @param string|null $errorMessage Optional custom error message to override the default.
     *
     * @return int|null The validated year as an integer, or null if validation fails.
     *
     * @throws ValidationException If the year is invalid and the field is required.
     */
    protected function validateYear(
        $year,
        string $fieldKey = 'year',
        bool $required = false,
        ?int $minYear = null,
        ?int $maxYear = null,
        ?string $errorMessage = null
    ): ?int {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Validator", "validateYear");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($year, "{$debugHeading} -- year");
        $this->debug->debugVariable($fieldKey, "{$debugHeading} -- fieldKey");
        $this->debug->debugVariable($required, "{$debugHeading} -- required");
        $this->debug->debugVariable($minYear, "{$debugHeading} -- minYear");
        $this->debug->debugVariable($maxYear, "{$debugHeading} -- maxYear");
        $this->debug->debugVariable($errorMessage, "{$debugHeading} -- errorMessage");

        // Validate required field
        $year = $this->validateRequiredField(
            $year,
            $required,
            $fieldKey,
            "Year is required."
        );
        if ($year === null) {
            return null;
        }

        // Use provided range or default to 2000 to next calendar year
        $minYear = $minYear ?? 2000;
        $maxYear = $maxYear ?? intval(date('Y')) + 1;
        $options = [
            'options' => [
                'min_range' => $minYear,
                'max_range' => $maxYear,
            ],
        ];

        // Validate field
        if (!filter_var($year, FILTER_VALIDATE_INT, $options)) {
            $this->errors[$fieldKey] = $errorMessage
                ?? "Invalid year: Must be between {$minYear} and {$maxYear}.";
            return null;
        }
        return (int) $year;
    }

    /**
     * Validates a timestamp value against a specified range.
     *
     * This method ensures the timestamp is an integer within an acceptable range.
     * It also checks if the field is required and handles null or empty input appropriately.
     *
     * @param mixed       $timestamp     The timestamp value to validate. Can be a string, int, or null.
     * @param string      $fieldKey      Key to associate errors with this field (default: 'timestamp').
     * @param bool        $required      Whether the field is required (default: false).
     * @param int|null    $minTimestamp  The minimum valid timestamp for validation (default: UNIX epoch).
     * @param int|null    $maxTimestamp  The maximum valid timestamp for validation (default: December 31, 9999).
     * @param string|null $errorMessage  Optional custom error message to override the default.
     *
     * @return int|null The validated timestamp as an integer, or null if validation fails.
     */
    protected function validateTimestamp(
        $timestamp,
        string $fieldKey = 'timestamp',
        bool $required = false,
        ?int $minTimestamp = null,
        ?int $maxTimestamp = null,
        ?string $errorMessage = null
    ): ?int {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Validator", "validateTimestamp");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($timestamp, "{$debugHeading} -- timestamp");
        $this->debug->debugVariable($fieldKey, "{$debugHeading} -- fieldKey");
        $this->debug->debugVariable($required, "{$debugHeading} -- required");
        $this->debug->debugVariable($minTimestamp, "{$debugHeading} -- minTimestamp");
        $this->debug->debugVariable($maxTimestamp, "{$debugHeading} -- maxTimestamp");
        $this->debug->debugVariable($errorMessage, "{$debugHeading} -- errorMessage");

        // Validate required field
        $timestamp = $this->validateRequiredField(
            $timestamp,
            $required,
            $fieldKey,
            "Timestamp is required."
        );
        if ($timestamp === null) {
            return null;
        }

        // Use provided range or default to UNIX epoch to December 31, 9999
        $minTimestamp = $minTimestamp ?? 0; // 1970-01-01 00:00:00 UTC
        $maxTimestamp = $maxTimestamp ?? 253402300799; // December 31, 9999
        $options = [
            'options' => [
                'min_range' => $minTimestamp,
                'max_range' => $maxTimestamp,
            ],
        ];

        // Validate field
        if (!filter_var($timestamp, FILTER_VALIDATE_INT, $options)) {
            $this->errors[$fieldKey] = $errorMessage
                ?? "Invalid timestamp: Must be between {$minTimestamp} and {$maxTimestamp}.";
            return null;
        }
        return (int) $timestamp;
    }

    protected function validateObsAppID(
        $obsapp_id,
        string $fieldKey = 'obsapp_id',
        bool $required = false
    ): ?int {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Validator", "validateObsAppID");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($obsapp_id, "{$debugHeading} -- obsapp_id");
        $this->debug->debugVariable($fieldKey, "{$debugHeading} -- fieldKey");
        $this->debug->debugVariable($required, "{$debugHeading} -- required");

        // Validate required field
        $obsapp_id = $this->validateRequiredField(
            $obsapp_id,
            $required,
            $fieldKey,
            "ObsAppID is required."
        );
        if ($obsapp_id === null) {
            return null;
        }

        // Validate fields
        if (is_null($obsapp_id) || !filter_var($obsapp_id, FILTER_VALIDATE_INT)) {
            $this->debug->fail("The obsapp_id provided is invalid.");
        }
        return (int) $obsapp_id;
    }

    protected function validateNumberInRange(
        $number,
        string $fieldKey = 'number',
        bool $required = false,
        ?int $minNumber = null,
        ?int $maxNumber = null,
        ?string $errorMessage = null
    ): ?int {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Validator", "validateNumberInRange");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($number, "{$debugHeading} -- number");
        $this->debug->debugVariable($fieldKey, "{$debugHeading} -- fieldKey");
        $this->debug->debugVariable($required, "{$debugHeading} -- required");
        $this->debug->debugVariable($minNumber, "{$debugHeading} -- minNumber");
        $this->debug->debugVariable($maxNumber, "{$debugHeading} -- maxNumber");
        $this->debug->debugVariable($errorMessage, "{$debugHeading} -- errorMessage");

        // Validate required field
        $number = $this->validateRequiredField(
            $number,
            $required,
            $fieldKey,
            "Number is required."
        );
        if ($number === null) {
            return null;
        }

        // Use provided range or default to 1 to 9999
        $minNumber = $minNumber ?? 1;
        $maxNumber = $maxNumber ?? 9999;
        $options = [
            'options' => [
                'min_range' => $minNumber,
                'max_range' => $maxNumber,
            ],
        ];

        // Validate program number
        if (!filter_var($number, FILTER_VALIDATE_INT, $options)) {
            $this->errors[$fieldKey] = $errorMessage
                ?? "Invalid number: Must be between {$minNumber} and {$maxNumber}.";
            return null;
        }
        return (int) $number;
    }

    /**
     * Validates a short program number.
     *
     * This method ensures the program number is an integer within the range of 1 to 999.
     * It also checks if the field is required and handles null or empty input appropriately.
     *
     * @param mixed       $program      The program number to validate. Can be a string, int, or null.
     * @param string      $fieldKey     Key to associate errors with this field (default: 'program_number').
     * @param bool        $required     Whether the field is required (default: false).
     * @param int|null    $minNumber    The minimum valid program number (default: 1).
     * @param int|null    $maxNumber    The maximum valid program number (default: 999).
     * @param string|null $errorMessage Optional custom error message to override the default.
     *
     * @return int|null The validated program number as an integer, or null if validation fails.
     */
    protected function validateShortProgramNumber(
        $program,
        string $fieldKey = 'program',
        bool $required = false,
        ?int $minNumber = null,
        ?int $maxNumber = null,
        ?string $errorMessage = null
    ): ?int {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Validator", "validateShortProgramNumber");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($programNumber, "{$debugHeading} -- program");
        $this->debug->debugVariable($fieldKey, "{$debugHeading} -- fieldKey");
        $this->debug->debugVariable($required, "{$debugHeading} -- required");
        $this->debug->debugVariable($minNumber, "{$debugHeading} -- minNumber");
        $this->debug->debugVariable($maxNumber, "{$debugHeading} -- maxNumber");
        $this->debug->debugVariable($errorMessage, "{$debugHeading} -- errorMessage");

        // Validate required field
        $program = $this->validateRequiredField(
            $program,
            $required,
            $fieldKey,
            "Program number is required."
        );
        if ($program === null) {
            return null;
        }

        // Use provided range or default to 1 to 999
        $minNumber = $minNumber ?? 1;
        $maxNumber = $maxNumber ?? 999;
        $options = [
            'options' => [
                'min_range' => $minNumber,
                'max_range' => $maxNumber,
            ],
        ];

        // Validate program number
        if (!filter_var($program, FILTER_VALIDATE_INT, $options)) {
            $this->errors[$fieldKey] = $errorMessage
                ?? "Invalid program number: Must be between {$minNumber} and {$maxNumber}.";
            return null;
        }
        return (int) $program;
    }

    /**
     * Validates the program number format.
     *
     * The program number must follow the YYYY[A|B]NNN format, where:
     * - YYYY is a year between 2000 and the next calendar year.
     * - A or B represents the semester.
     * - NNN is a zero-padded number from 001 to 999.
     *
     * @param string $program   Program number to validate.
     * @param string $fieldKey  Key to associate errors with this field.
     * @param bool   $required  Whether the field is required.
     *
     * @return string|null Validated program number, or null if validation fails.
     *
     * @throws ValidationException If the program number is invalid and the field is required.
     */
    protected function validateProgramNumber(
        string $program,
        string $fieldKey = 'program',
        bool $required = false
    ): ?string {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Validator", "validateProgramNumber");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($program, "{$debugHeading} -- program");
        $this->debug->debugVariable($fieldKey, "{$debugHeading} -- fieldKey");
        $this->debug->debugVariable($required, "{$debugHeading} -- required");

        // Validate required field
        $program = $this->validateRequiredField(
            $program,
            $required,
            $fieldKey,
            "Program number is required."
        );
        if ($program === null) {
            return null;
        }

        // Validate fields

        $pattern = '/^\d{4}[AB]\d{3}$/'; // Matches YYYY[A|B]NNN format
        if (!preg_match($pattern, $program)) {
            $this->errors[$fieldKey] = "Invalid program format: '{$program}'. "
                . "Expected format: YYYY[A|B]NNN.";
            return null;
        }

        $year = intval(substr($program, 0, 4));
        $semester = $program[4];
        $number = intval(substr($program, 5, 3));

        $year = $this->validateYear(
            $year,
            'program',
            $required,
            2000,
            intval(date('Y')) + 1,
            "Invalid year in '{$program}'. Year must be between 2000 and next calendar year."
        );
        if ($year === null) {
            return null;
        }

        $semester = $this->validateSemester(
            $semester,
            'program',
            $required,
            "Invalid semester in '{$program}'. Semester must be 'A' or 'B'."
        );
        if ($semester === null) {
            return null;
        }

        $number = $this->validateShortProgramNumber(
            $number,
            'program',
            $required,
            1,
            999,
            "Invalid program number in '{$program}'. Number must be between 001 and 999."
        );
        if ($number === null) {
            return null;
        }

        return IrtfUtilities::escape($program);
    }

    /**
     * Validates the session code format.
     *
     * The session code must be exactly 10 characters in length and
     * contain only alphanumeric characters.
     *
     * @param string $session Session code to validate.
     * @param string $fieldKey  Key to associate errors with this field.
     * @param bool   $required  Whether the field is required.
     *
     * @return string|null Validated session code, or null if validation fails.
     *
     * @throws ValidationException If the session code is invalid and the field is required.
     */
    protected function validateProgramSession(
        string $session,
        string $fieldKey = 'session',
        bool $required = false
    ): ?string {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Validator", "validateProgramSession");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($session, "{$debugHeading} -- session");
        $this->debug->debugVariable($fieldKey, "{$debugHeading} -- fieldKey");
        $this->debug->debugVariable($required, "{$debugHeading} -- required");

        // Validate required field
        $session = $this->validateRequiredField(
            $session,
            $required,
            $fieldKey,
            "Session is required."
        );
        if ($session === null) {
            return null;
        }

        // Validate fields

        // Engineering/project accounts
        $engineeringCodes = $this->getEngineeringCodes();
        if (in_array($session, $engineeringCodes, true)) {
            return IrtfUtilities::escape($session);
        }

        // Guest program accounts
        if (strlen($session) !== 10 || !ctype_alnum($session)) {
            $this->errors[$fieldKey] = "Invalid session code '{$session}'. Must be a 10-character alphanumeric code.";
            return null;
        }
        return IrtfUtilities::escape($session);
    }

    protected function getEngineeringCodes(): array
    {
        return [
            'tisanpwd',
            'wbtcorar'
        ];
    }

    /**
     * Validates and checks the chronological order of start and end dates.
     *
     * @param int    $startMonth Start month.
     * @param int    $startDay   Start day.
     * @param int    $startYear  Start year.
     * @param int    $endMonth   End month.
     * @param int    $endDay     End day.
     * @param int    $endYear    End year.
     * @param string $semester   Optional semester for additional validation.
     * @param string $fieldKey   Key to associate errors with this field.
     * @param bool   $required   Whether the field is required.
     *
     * @return bool|null True if dates are valid, or null if validation fails.
     *
     * @throws ValidationException If the dates are invalid or out of order.
     */
    protected function validateDates(
        int $startMonth,
        int $startDay,
        int $startYear,
        int $endMonth,
        int $endDay,
        int $endYear,
        string $semester = '',
        string $fieldKey = 'dates',
        bool $required = false
    ): ?bool {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Validator", "validateDates");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($startMonth, "{$debugHeading} -- startMonth");
        $this->debug->debugVariable($startDay, "{$debugHeading} -- startDay");
        $this->debug->debugVariable($startYear, "{$debugHeading} -- startYear");
        $this->debug->debugVariable($endMonth, "{$debugHeading} -- endMonth");
        $this->debug->debugVariable($endDay, "{$debugHeading} -- endDay");
        $this->debug->debugVariable($endYear, "{$debugHeading} -- endYear");
        $this->debug->debugVariable($semester, "{$debugHeading} -- semester");
        $this->debug->debugVariable($fieldKey, "{$debugHeading} -- fieldKey");
        $this->debug->debugVariable($required, "{$debugHeading} -- required");

        // Prepare values for validation
        $startDate = sprintf('%04d-%02d-%02d', $startYear, $startMonth, $startDay);
        $endDate = sprintf('%04d-%02d-%02d', $endYear, $endMonth, $endDay);
        $startSemester = IrtfUtilities::returnSemester($startMonth, $startDay, $startYear);
        $endSemester = IrtfUtilities::returnSemester($endMonth, $endDay, $endYear);
        $this->debug->debugVariable($startDate, "{$debugHeading} -- startDate");
        $this->debug->debugVariable($endDate, "{$debugHeading} -- endDate");
        $this->debug->debugVariable($startSemester, "{$debugHeading} -- startSemester");
        $this->debug->debugVariable($endSemester, "{$debugHeading} -- endSemester");

        // Validate fields
        if (!checkdate($startMonth, $startDay, $startYear) || !checkdate($endMonth, $endDay, $endYear)) {
            $this->errors[$fieldKey] = "Invalid start or end date.";
            return null;
        }
        if ($endDate < $startDate) {
            $this->errors[$fieldKey] = "End date cannot be before start date.";
            return null;
        }
        if ($semester !== '' && ($startSemester !== $semester || $endSemester !== $semester)) {
            $this->errors[$fieldKey] = "Selected dates must fall within the {$semester} semester.";
            return null;
        }
        return true;
    }

    /**
     * Validates long text fields for content length and formatting.
     *
     * @param string $text      The text content to validate.
     * @param int    $textLength Maximum allowed length for the text.
     * @param string $fieldKey  Key to associate errors with this field.
     * @param bool   $required  Whether the field is required.
     *
     * @return string|null Validated and escaped text content, or null if validation fails.
     *
     * @throws ValidationException If the text is invalid and the field is required.
     */
    protected function validateLongTextField(
        string $text,
        int $textLength = 500,
        string $fieldKey = 'text',
        bool $required = false
    ): ?string {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Validator", "validateLongTextField");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($text, "{$debugHeading} -- text");
        $this->debug->debugVariable($textLength, "{$debugHeading} -- textLength");
        $this->debug->debugVariable($fieldKey, "{$debugHeading} -- fieldKey");
        $this->debug->debugVariable($required, "{$debugHeading} -- required");

        // Validate required field
        $text = $this->validateRequiredField(
            $text,
            $required,
            $fieldKey,
            "Content is required."
        );
        if ($text === null) {
            return null;
        }

        // Validate fields
        if (strlen($text) > $textLength) {
            $this->errors[$fieldKey] = "Text content too long. Must be under {$textLength} characters.";
            return null;
        }
        return IrtfUtilities::escape($text);
    }

    /**
     * Validates the rating value for experience or other metrics.
     *
     * @param int    $rating     The rating to validate.
     * @param bool   $addNA      Whether to include "N/A" as a valid option.
     * @param string $fieldKey   Key to associate errors with this field.
     * @param bool   $required   Whether the field is required.
     *
     * @return int|null Validated rating, or null if validation fails.
     *
     * @throws ValidationException If the rating is invalid and the field is required.
     */
    protected function validateRating(
        int $rating,
        bool $addNA = false,
        string $fieldKey = 'rating',
        bool $required = false
    ): ?int {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Validator", "validateRating");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($rating, "{$debugHeading} -- rating");
        $this->debug->debugVariable($addNA, "{$debugHeading} -- addNA");
        $this->debug->debugVariable($fieldKey, "{$debugHeading} -- fieldKey");
        $this->debug->debugVariable($required, "{$debugHeading} -- required");

        // Validate fields
        $allowed = $addNA ? [0 => 0, 1, 2, 3, 4, 5] : [1 => 1, 2, 3, 4, 5];
        $starter = $addNA ? 0 : 1;
        $errorMsg = "Invalid rating. Must be between {$starter} and 5.";
        $validatedResult = $this->validateSelection([$rating], $allowed, $fieldKey, $required, $errorMsg)[0];
        return (int) $validatedResult ?? null;
    }

    /**
     * Validates the location selection for remote or onsite observing.
     *
     * @param int    $location   Location value to validate (0 for remote, 1 for onsite).
     * @param string $fieldKey   Key to associate errors with this field.
     * @param bool   $required   Whether the field is required.
     *
     * @return int|null Validated location, or null if validation fails.
     *
     * @throws ValidationException If the location is invalid and the field is required.
     */
    protected function validateLocation(
        int $location,
        string $fieldKey = 'location',
        bool $required = false
    ): ?int {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Validator", "validateLocation");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($location, "{$debugHeading} -- location");
        $this->debug->debugVariable($fieldKey, "{$debugHeading} -- fieldKey");
        $this->debug->debugVariable($required, "{$debugHeading} -- required");

        // Validate fields
        $allowed = [0, 1];
        $errorMsg = "Invalid location. Must be 0 or 1.";
        $validatedResult = $this->validateSelection([$location], $allowed, $fieldKey, $required, $errorMsg)[0];
        return (int) $validatedResult ?? null;
    }

    /**
     * Validates the email type selection for sending real or dummy emails.
     *
     *
     * @throws ValidationException If the location is invalid and the field is required.
     */
    protected function validateEmailsSendType(
        int $emailType,
        string $fieldKey = 'emails',
        bool $required = false
    ): ?int {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Validator", "validateEmailsSendType");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($emailType, "{$debugHeading} -- emailType");
        $this->debug->debugVariable($fieldKey, "{$debugHeading} -- fieldKey");
        $this->debug->debugVariable($required, "{$debugHeading} -- required");

        // Validate fields
        $allowed = [0, 1];
        $errorMsg = "Invalid email send type. Must be 0 or 1.";
        $validatedResult = $this->validateSelection([$emailType], $allowed, $fieldKey, $required, $errorMsg)[0];
        return (int) $validatedResult ?? null;
    }

    /**
     * Validates the email type selection for sending real or dummy emails.
     *
     *
     * @throws ValidationException If the location is invalid and the field is required.
     */
    protected function validateIntervalUnitType(
        int $unitType,
        string $fieldKey = 'units',
        bool $required = false
    ): ?int {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Validator", "validateIntervalUnitType");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($unitType, "{$debugHeading} -- unitType");
        $this->debug->debugVariable($fieldKey, "{$debugHeading} -- fieldKey");
        $this->debug->debugVariable($required, "{$debugHeading} -- required");

        // Validate fields
        $allowed = [0, 1];
        $errorMsg = "Invalid interval unit type. Must be 0 or 1.";
        $validatedResult = $this->validateSelection([$unitType], $allowed, $fieldKey, $required, $errorMsg)[0];
        return (int) $validatedResult ?? null;
    }

    /**
     * Validates the visitor instrument selection.
     *
     * @param string $instrument The visitor instrument selected.
     * @param array  $allowed    Allowed visitor instrument options.
     * @param string $fieldKey   Key to associate errors with this field.
     * @param bool   $required   Whether the field is required.
     *
     * @return array|null Validated instrument, or null if validation fails.
     *
     * @throws ValidationException If the visitor instrument is invalid and the field is required.
     */
    protected function validateVisitorInstrument(
        string $instrument,
        array $allowed,
        string $fieldKey = 'visitor_instrument',
        bool $required = false
    ): ?array {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Validator", "validateVisitorInstrument");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($instrument, "{$debugHeading} -- instrument");
        $this->debug->debugVariable($allowed, "{$debugHeading} -- allowed");
        $this->debug->debugVariable($fieldKey, "{$debugHeading} -- fieldKey");
        $this->debug->debugVariable($required, "{$debugHeading} -- required");

        // Validate fields
        $errorMsg = "Invalid visitor instrument selected.";
        return $this->validateSelection([$instrument], $allowed, $fieldKey, $required, $errorMsg);
    }

    /**
     * Validates and moves an uploaded file to the target directory.
     *
     * This method checks for upload errors, validates the file's MIME type
     * (if specified), and moves the uploaded file to the given directory.
     *
     * @param array  $fileData   The uploaded file data (e.g., $_FILES element).
     * @param string $uploadPath The target upload directory.
     * @param array  $mimeTypes  Allowed MIME types for the file (optional).
     * @param string $fieldKey   Key to associate errors with this field.
     * @param bool   $required   Whether the field is required.
     *
     * @return string|null Validated and moved file path, or null if validation fails.
     *
     * @throws ValidationException If validation fails due to errors or invalid file types.
     */
    protected function validateUploadedFile(
        array $fileData,
        string $uploadPath,
        array $mimeTypes = [],
        string $fieldKey = 'file',
        bool $required = false
    ): ?string {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Validator", "validateUploadedFile");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($fileData, "{$debugHeading} -- fileData");
        $this->debug->debugVariable($uploadPath, "{$debugHeading} -- uploadPath");
        $this->debug->debugVariable($mimeTypes, "{$debugHeading} -- mimeTypes");
        $this->debug->debugVariable($fieldKey, "{$debugHeading} -- fieldKey");
        $this->debug->debugVariable($required, "{$debugHeading} -- required");

        // Check for upload errors
        if ($fileData['error'] !== UPLOAD_ERR_OK) {
            $this->errors[$fieldKey] = "File upload error code: {$fileData['error']}.";
            return null;
        }

        // Validate MIME type
        if (!empty($mimeTypes) && !in_array($fileData['type'], $mimeTypes, true)) {
            $this->errors[$fieldKey] = "Invalid file type: {$fileData['type']}.";
            return null;
        }

        // Move uploaded file
        $targetPath = rtrim($uploadPath, '/') . '/' . basename($fileData['name']);
        if (!move_uploaded_file($fileData['tmp_name'], $targetPath)) {
            $this->errors[$fieldKey] = "Failed to move uploaded file to {$targetPath}.";
            return null;
        }
        return $targetPath;
    }

    // Protected helper methods

    /**
     * Converts a numeric rating to its descriptive text equivalent.
     *
     * @param int $rating The numeric rating (0-5).
     *
     * @return string The descriptive rating text, e.g., "Excellent".
     */
    protected function returnRatingText(
        int $rating
    ): string {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Validator", "returnRatingText");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($rating, "{$debugHeading} -- rating");
        // Method text
        $ratingText = [
            'N/A',
            'Poor',
            'Fair',
            'Good',
            'Very Good',
            'Excellent',
        ];
        return $ratingText[$rating];
    }

    /**
     * Converts a numeric location code to a descriptive text equivalent.
     *
     * @param int $location The location code (0 for "Remote", 1 for "Onsite").
     *
     * @return string The location description, either "Remote" or "Onsite".
     */
    protected function returnLocationText(
        int $location
    ): string {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Validator", "returnLocationText");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($location, "{$debugHeading} -- location");
        // Method text
        $locationText = [
            'Remote',
            'Onsite',
        ];
        return $locationText[$location];
    }

    /**
     * Converts a numeric location code to a descriptive text equivalent.
     *
     * @param int $location The location code (0 for "Remote", 1 for "Onsite").
     *
     * @return string The location description, either "Remote" or "Onsite".
     */
    protected function returnEmailsSendTypeText(
        int $emailType
    ): string {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Validator", "returnEmailsSendTypeText");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($emailType, "{$debugHeading} -- emailType");
        // Method text
        $emailTypeText = [
            'Yes (send real emails)',
            'No (send dummy emails)',
        ];
        return $emailTypeText[$emailType];
    }

    /**
     * Converts a numeric location code to a descriptive text equivalent.
     *
     * @param int $location The location code (0 for "Remote", 1 for "Onsite").
     *
     * @return string The location description, either "Remote" or "Onsite".
     */
    protected function returnIntervalUnitTypeText(
        int $unitType
    ): string {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Validator", "returnIntervalUnitTypeText");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($unitType, "{$debugHeading} -- unitType");
        // Method text
        $unitTypeText = [
            'Days',
            'Weeks',
        ];
        return $unitTypeText[$unitType];
    }

    /**
     * Retrieves the descriptive names for selected items and returns them as a comma-separated string.
     *
     * Maps each key in the options array to its corresponding value in the allowed
     * array, then concatenates them into a single comma-separated string.
     *
     * @param array $options Selected option keys.
     * @param array $allowed Associative array of allowed options with keys and names.
     *
     * @return string A comma-separated list of names for the selected options.
     */
    protected function returnSelectionText(
        array $options,
        array $allowed
    ): string {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Validator", "returnSelectionText");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($options, "{$debugHeading} -- options");
        $this->debug->debugVariable($allowed, "{$debugHeading} -- allowed");
        // Method text
        return implode(
            ', ',
            array_map(
                [IrtfUtilities::class, 'escape'],
                array_intersect_key($allowed, array_flip($options))
            )
        );
    }

    /**
     * Combines instrument selections from user input and allowed options.
     *
     * @param array  $options   Selected instruments from the form.
     * @param string $visitor   Visitor instrument selected.
     * @param array  $allowed   Allowed facility instrument options.
     * @param array  $visitDb   Allowed visitor instrument options.
     *
     * @return array An array containing 'form' (validated form inputs) and 'db' (database options).
     */
    protected function transformInstruments(
        array $options,
        string $visitor,
        array $allowed,
        array $visitDb
    ): array {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Validator", "transformInstruments");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($options, "{$debugHeading} -- options");
        $this->debug->debugVariable($visitor, "{$debugHeading} -- visitor");
        $this->debug->debugVariable($allowed, "{$debugHeading} -- allowed");
        $this->debug->debugVariable($visitDb, "{$debugHeading} -- visitDb");
        // Method transformation
        $formIns = array_merge(
            $options ?? [],
            array_filter(
                [$visitor] ?? [],
                function ($value) {
                    return $value !== 'none';
                }
            )
        );
        $dbIns = array_merge(
            $allowed,
            array_filter(
                $visitDb ?? [],
                function ($key) {
                    return $key !== 'none';
                },
                ARRAY_FILTER_USE_KEY
            )
        );
        return ['form' => $formIns, 'db' => $dbIns];
    }

    /**
     * Validates that a required field is not empty or null.
     *
     * This method handles various types of inputs, including strings, numbers, arrays, and objects.
     * It treats `0` (numeric or string) and empty arrays differently to ensure appropriate validation.
     *
     * @param mixed  $value        The value to validate.
     * @param bool   $required     Whether the field is required.
     * @param string $fieldKey     Key to associate errors with this field.
     * @param string $errorMessage Custom error message for empty or missing values.
     *
     * @return mixed|null The original value if validation passes, or null if validation fails.
     */
    protected function validateRequiredField(
        $value,
        bool $required,
        string $fieldKey,
        string $errorMessage
    ) {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Validator", "validateRequiredField");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($value, "{$debugHeading} -- value");
        $this->debug->debugVariable($required, "{$debugHeading} -- required");
        $this->debug->debugVariable($fieldKey, "{$debugHeading} -- fieldKey");
        $this->debug->debugVariable($errorMessage, "{$debugHeading} -- errorMessage");

        // Perform required check
        if ($required) {
            // Handle special cases for numeric and array values
            if (
                $value === null ||
                (is_string($value) && trim($value) === '') ||
                (is_array($value) && empty($value)) ||
                (!is_numeric($value) && empty($value))
            ) {
                $this->errors[$fieldKey] = $errorMessage;
                return null;
            }
        }

        return $value;
    }
}
