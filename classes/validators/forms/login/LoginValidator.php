<?php

declare(strict_types=1);

namespace App\validators\forms\login;

use Exception;
use App\exceptions\ValidationException;
use App\core\irtf\IrtfUtilities;
use App\core\common\CustomDebug            as Debug;
use App\validators\forms\BaseFormValidator as BaseValidator;

/**
 * Validator for login form input.
 *
 * This class handles validation for login-related fields, including
 * program number and session code, using specific format requirements.
 * Any failed validation results in an exception and a debug log.
 *
 * @category Validators
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class LoginValidator extends BaseValidator
{
    /**
     * Constructor to initialize the LoginValidator.
     *
     * If no debug instance is provided, it defaults to a basic debug setup.
     *
     * @param Debug|null $debug Optional debugging utility instance.
     */
    public function __construct(?Debug $debug = null)
    {
        // Use parent class' constructor
        parent::__construct($debug);
        $debugHeading = $this->debug->debugHeading("Validator", "__construct");
        $this->debug->debug($debugHeading);
        $this->debug->debug("{$debugHeading} -- Parent class is successfully constructed.");
    }

    public function validateFormData(
        array $form
    ): array {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Validator", "validateFormData");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($form, "form");
        // Validate the form data and return the array for database verification
        $validData = $this->validateDataForDatabase($form);
        // Return both arrays
        return $validData;
    }

    private function validateDataForDatabase(
        array $form
    ): array {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Validator", "validateDataForDatabase");
        $this->debug->debug($debugHeading);
        // Build the validated data array for database
        $valid = [];

        // Basic info
        $valid['program'] = $this->validateProgramNumber(
            $form['program'] ?? '',
            'program',
            true
        );
        $valid['session'] = $this->validateProgramSession(
            $form['session'] ?? '',
            'session',
            true
        );
        // After validating, check if errors exist and throw if necessary
        if (!empty($this->errors)) {
            throw new ValidationException("Validation errors occurred.", $this->errors);
        }
        return $valid;
    }
}
