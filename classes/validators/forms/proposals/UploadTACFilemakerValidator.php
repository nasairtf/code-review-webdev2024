<?php

declare(strict_types=1);

namespace App\validators\forms\proposals;

use Exception;
use App\exceptions\ValidationException;
use App\core\irtf\IrtfUtilities;
use App\core\common\AbstractDebug as Debug;
use App\validators\BaseValidator;

/**
 * Validator for handling the TAC scores upload logic.
 *
 * This class is responsible for validating form data submitted during TAC score uploads.
 * It ensures the correctness of fields such as load type, access type, and uploaded file integrity.
 * The validation adheres to specific constraints and throws exceptions for invalid input.
 *
 * @category Validators
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 *
 * @property Debug $debug Debugging utility for logging and error tracing.
 */

class UploadTACFilemakerValidator extends BaseValidator
{
    /**
     * Initializes the validator and sets up debugging.
     *
     * If no debug instance is provided, it defaults to a basic debug setup.
     *
     * @param Debug|null $debug Debug instance for logging and debugging.
     */
    public function __construct(?Debug $debug = null)
    {
        // Use parent class' constructor
        parent::__construct($debug);
        $debugHeading = $this->debug->debugHeading("Validator", "__construct");
        $this->debug->debug($debugHeading);
        $this->debug->debug("{$debugHeading} -- Parent class is successfully constructed.");
    }

    // Abstract methods: getValidationPlan(), formatValidData(), formatErrors()

    protected function getValidationPlan(array $data, array $context = []): array
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Validator", "getValidationPlan");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($data, "{$debugHeading} -- data");
        $this->debug->debugVariable($context, "{$debugHeading} -- context");

        $minYear = 2000;
        $maxYear = date('Y') + 1;
        return [
            [
                'field'         => 'program',
                'method'        => 'validateProgramNumberField',
                'args'          => [$minYear, $maxYear],
                'required'      => true,
                'required_msg'  => 'This field is required',
            ],
            [
                'field'         => 'session',
                'method'        => 'validateSessionCodeField',
                'args'          => [],
                'required'      => true,
                'required_msg'  => 'This field is required',
            ],
        ];
    }

    protected function formatValidData(array $normalizedPlan): array
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Validator", "formatValidData");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($normalizedPlan, "{$debugHeading} -- normalizedPlan");

        return $this->formatStdValidData($normalizedPlan);
    }

    protected function formatErrors(array $normalizedPlan): array
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Validator", "formatErrors");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($normalizedPlan, "{$debugHeading} -- normalizedPlan");

        return $this->formatStdErrors($normalizedPlan);
    }

    /**
     * Validates form data submitted for schedule upload.
     *
     * This method validates all fields in the form data and ensures compliance
     * with the required formats, constraints, and allowed values.
     *
     * @param array $form The form input data to validate.
     *
     * @return array An associative array containing validated data.
     * @throws ValidationException If validation errors occur.
     */
    public function validateFormData(
        array $form
    ): array {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Validator", "validateFormData");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($form, "{$debugHeading} -- form");

        // Validate the form data and return the array for database processing
        $validData = $this->validateData($form);

        // Return array
        return $validData;
    }

    /**
     * Validates the form data fields and returns the processed results.
     *
     * This method performs specific validation checks on the following fields:
     * - `loadtype`: Must be either 'partial' or 'full'.
     * - `access`: Must be either 'public' or 'private'.
     * - `file`: Validates the uploaded file against allowed MIME types and ensures proper file handling.
     *
     * @param array $form The raw form input data to validate.
     *
     * @return array An associative array containing validated data.
     * @throws ValidationException If validation errors occur.
     */
    private function validateData(
        array $form
    ): array {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Validator", "validateData");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($form, "{$debugHeading} -- form");

        // Build the validated data array for database
        $valid = [];

        // Validate `y` field
        $valid['year'] = $this->validateYear(
            $form['y'] ?? '',
            'year',
            true,
            1000,
            9999,
            'The year provided is invalid.'
        );

        // Validate `s` field
        $valid['semester'] = $this->validateSemester(
            $form['s'] ?? '',
            'semester',
            true,
            'The semester provided is invalid.'
        );

        // Validate uploaded `filess`
        //$allowedMimeTypes = ['text/csv', 'text/plain', 'application/octet-stream'];
        $allowedMimeTypes = ['text/csv']; // Allowed MIME types for uploaded files
        $valid['file'] = $this->validateUploadedFile(
            $form['file'],
            $form['path'],
            $allowedMimeTypes,
            'file',
            true
        );

        // Check for validation errors and throw an exception if any are found
        if (!empty($this->errors)) {
            throw new ValidationException("Validation errors occurred.", $this->errors);
        }

        // Return the validated data
        return $valid;
    }
}
