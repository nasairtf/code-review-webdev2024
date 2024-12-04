<?php

namespace App\validators\forms\proposals;

use Exception;

use App\exceptions\ValidationException;

use App\core\common\Debug;
use App\core\irtf\IrtfUtilities;

use App\validators\forms\BaseFormValidator as BaseValidator;

/**
 * Validator for handling the schedule upload logic.
 *
 * @category Validators
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class UploadScheduleFileValidator extends BaseValidator
{
    // Constructor: Initializes the controller, view, and model, and sets up debugging
    public function __construct(Debug $debug = null)
    {
        // Use parent class' constructor
        parent::__construct($debug);
        $debugHeading = $this->debug->debugHeading("Validator", "__construct");
        $this->debug->debug($debugHeading);
        $this->debug->log("{$debugHeading} -- Parent class is successfully constructed.");
    }

    /**
     * Validates form data.
     *
     * @param array $form The form input data to validate.
     *
     * @return array An associative array with 'db' and 'email' keys, each containing validated data.
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
     * Validates form data.
     *
     * @param array $form Form input data to validate.
     *
     * @return array Validated data for database storage.
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

        // Validate load type
        $valid['loadtype'] = $this->validateSelection(
            [$form['loadtype'] ?? ''],
            ['partial', 'full'],
            'loadtype',
            true,
            "Invalid load type.",
            false
        )[0];

        // Validate access
        $valid['access'] = $this->validateSelection(
            [$form['access'] ?? ''],
            ['public', 'private'],
            'access',
            true,
            "Invalid access type.",
            false
        )[0];

        // Validate file upload
        //$allowedMimeTypes = ['text/csv', 'text/plain', 'application/octet-stream'];
        $allowedMimeTypes = ['text/csv'];
        $valid['file'] = $this->validateUploadedFile(
            $form['file'],
            $form['path'],
            $allowedMimeTypes,
            'file',
            true
        );

        // After validating, check if errors exist and throw if necessary
        if (!empty($this->errors)) {
            throw new ValidationException("Validation errors occurred.", $this->errors);
        }
        return $valid;
    }
}
