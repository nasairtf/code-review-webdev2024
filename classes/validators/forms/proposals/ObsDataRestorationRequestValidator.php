<?php

declare(strict_types=1);

namespace App\validators\forms\proposals;

use Exception;
use App\exceptions\ValidationException;
use App\core\common\CustomDebug            as Debug;
use App\validators\forms\BaseFormValidator as BaseValidator;

/**
 * Validator for handling the Observer Data Restoration Request logic.
 *
 * @category Validators
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class ObsDataRestorationRequestValidator extends BaseValidator
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

        // Validate the form data and return the validated data array
        $validData = $this->validateRequestData($form);

        // Return array
        return $validData;
    }

    private function validateRequestData(
        array $form
    ): array {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Validator", "validateRequestData");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($form, "{$debugHeading} -- form");

        // Build the validated data array
        $valid = [];

        // Validate `reqname` field [Requestor name]
        $valid['reqname'] = $this->validateName(
            $form['reqname'] ?? '',
            'reqname',
            true,
            50,
            'Requestor name'
        );

        // Validate `reqemail` field [Requestor email]
        $valid['reqemail'] = $this->validateEmail(
            $form['reqemail'] ?? '',
            'reqemail',
            true
        );

        // Validate `y` field [The semester year the data were taken]
        $valid['y'] = $this->validateYear(
            $form['y'] ?? '',
            'y',
            true,
            2016,
            intval(date('Y')) + 1
        );

        // Validate `s` field [The semester tag the data were taken]
        $valid['s'] = $this->validateSemester(
            $form['s'] ?? '',
            's',
            true
        );

        // Validate `srcprogram` field [Program the data were taken under]
        $valid['srcprogram'] = $this->validateProgramNumber(
            $form['srcprogram'] ?? '',
            'srcprogram',
            true
        );

        // Validate `piprogram` field [PI of the program]
        $valid['piprogram'] = $this->validateName(
            $form['piprogram'] ?? '',
            'piprogram',
            true,
            50,
            'Program PI'
        );

        // Validate `obsinstr` field [Instruments used to take the data]
        $valid['obsinstr'] = $this->validateName(
            $form['obsinstr'] ?? '',
            'obsinstr',
            true,
            50,
            'Instruments'
        );

        // Validate `reldetails` field [Any other details that might be relevant or helpful
        $valid['reldetails'] = $this->validateLongTextField(
            $form['reldetails'] ?? '',
            500,
            'reldetails',
            false
        );

        // Check for validation errors and throw an exception if any are found
        if (!empty($this->errors)) {
            throw new ValidationException("Validation errors occurred.", $this->errors);
        }

        // Return the validated data
        return $valid;
    }
}
