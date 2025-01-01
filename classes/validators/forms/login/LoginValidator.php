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
 * This class handles the validation of login form fields, including program numbers
 * and session codes. It ensures that the input conforms to the expected format and
 * constraints. Any invalid input results in a `ValidationException`, and the debug utility
 * logs detailed messages for troubleshooting.
 *
 * Responsibilities:
 * - Validate program numbers and session codes for syntax and logical correctness.
 * - Generate arrays suitable for database interactions or further processing.
 * - Manage and throw detailed exceptions for validation errors.
 *
 * @category Validators
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 *
 * @property Debug $debug Debugging utility for logging validation processes.
 */

class LoginValidator extends BaseValidator
{
    /**
     * Constructor to initialize the LoginValidator.
     *
     * Creates a new instance of the validator, initializing the debug utility
     * to log the validation process. The debug utility is either provided as
     * a parameter or defaults to a new instance.
     *
     * @param Debug|null $debug Optional debugging utility instance for logging.
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
     * Validates and transforms form data for database verification.
     *
     * This method ensures that the provided form data meets all required criteria.
     * It validates each field, returning a structured array with validated data ready
     * for database processing or further use in the application.
     *
     * @param array $form The form input data to validate.
     *
     * @return array An associative array containing validated form data:
     *               - 'program': The validated program number.
     *               - 'session': The validated session code.
     *
     * @throws ValidationException If validation errors occur.
     */
    public function validateFormData(
        array $form
    ): array {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Validator", "validateFormData");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($form, "form");

        // Validate the form data and return the array for database verification
        return $this->validateDataForDatabase($form);
    }

    /**
     * Validates form data for database verification.
     *
     * Performs field-specific validation for the login form, ensuring that
     * the provided program number and session code are in the correct format.
     * Validated data is returned as an associative array.
     *
     * @param array $form The form input data to validate.
     *
     * @return array An array containing validated form data:
     *               - 'program': The validated program number.
     *               - 'session': The validated session code.
     *
     * @throws ValidationException If any validation errors are detected.
     */
    private function validateDataForDatabase(
        array $form
    ): array {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Validator", "validateDataForDatabase");
        $this->debug->debug($debugHeading);

        // Build the validated data array for database
        $valid = [];

        // Validate program number
        $valid['program'] = $this->validateProgramNumber(
            $form['program'] ?? '',
            'program',
            true
        );

        // Validate session code
        $valid['session'] = $this->validateProgramSession(
            $form['session'] ?? '',
            'session',
            true
        );

        // After validating, check if errors exist and throw if necessary
        if (!empty($this->errors)) {
            throw new ValidationException("Validation errors occurred.", $this->errors);
        }

        // Return the valid data for database verification
        return $valid;
    }
}
