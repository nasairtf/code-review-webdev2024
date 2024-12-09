<?php

declare(strict_types=1);

namespace App\validators\forms;

use Exception;
use App\exceptions\ValidationException;
use App\core\common\Debug;
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
        $debugHeading = $this->debug->debugHeading("Validator", "__construct");
        $this->debug->debug($debugHeading);
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
        if ($required && empty($options)) {
            $this->errors[$fieldKey] = "Please make a selection for this field.";
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
                $validatedOptions[] = IrtfUtilities::escape($option);
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
        bool $required = false
    ): ?string {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Validator", "validateName");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($name, "{$debugHeading} -- name");
        $this->debug->debugVariable($fieldKey, "{$debugHeading} -- fieldKey");
        $this->debug->debugVariable($required, "{$debugHeading} -- required");
        // Validate fields
        if ($required && empty($name)) {
            $this->errors[$fieldKey] = "Name is required.";
            return null;
        } elseif (strlen($name) > 70) {
            $this->errors[$fieldKey] = "Invalid name. Must be 1-70 characters.";
            return null;
        }
        return IrtfUtilities::escape($name);
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
        // Validate fields
        if ($required && empty($email)) {
            $this->errors[$fieldKey] = "Email is required.";
            return null;
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$fieldKey] = "Invalid email format.";
            return null;
        }
        return $email;
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
        // Validate fields
        if ($required && empty($text)) {
            $this->errors[$fieldKey] = "Content is required.";
            return null;
        } elseif (strlen($text) > $textLength) {
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
        return $this->validateSelection([$rating], $allowed, $fieldKey, $required, $errorMsg)[0];
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
        return $this->validateSelection([$location], $allowed, $fieldKey, $required, $errorMsg)[0];
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
            $this->errors['file'] = "File upload error code: {$fileData['error']}.";
            return null;
        }
        // Validate MIME type
        if (!empty($mimeTypes) && !in_array($fileData['type'], $mimeTypes, true)) {
            $this->errors['file'] = "Invalid file type: {$fileData['type']}.";
            return null;
        }
        // Move uploaded file
        $targetPath = rtrim($uploadPath, '/') . '/' . basename($fileData['name']);
        if (!move_uploaded_file($fileData['tmp_name'], $targetPath)) {
            $this->errors['file'] = "Failed to move uploaded file to {$targetPath}.";
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
}
