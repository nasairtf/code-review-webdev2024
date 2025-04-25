<?php

declare(strict_types=1);

namespace App\validators\forms\proposals;

use Exception;
use App\exceptions\ValidationException;
use App\core\common\AbstractDebug          as Debug;
use App\validators\forms\BaseFormValidator as BaseValidator;

/**
 * Validator for handling the Queue Observer Data Restoration logic.
 *
 * @category Validators
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 *
 * @property Debug $debug Debugging utility for logging and error tracing.
 */

class QueueDataRestoreValidator extends BaseValidator
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
        $validData = $this->validateRestoreData($form);

        // Return array
        return $validData;
    }

    private function validateRestoreData(
        array $form
    ): array {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Validator", "validateRestoreData");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($form, "{$debugHeading} -- form");

        // Build the validated data array for database
        $valid = [];

        // Validate `usersrc` field [Source Program Username]
        $valid['usersrc'] = $this->validateProgramNumber(
            $form['usersrc'] ?? '',
            'usersrc',
            true
        );

        // Validate `userdst` field [Destination Program Username]
        $valid['userdst'] = $this->validateProgramNumber(
            $form['userdst'] ?? '',
            'userdst',
            true
        );

        // Validate `codesrc` field [Source Program Code]
        $valid['codesrc'] = $this->validateProgramSession(
            $form['codesrc'] ?? '',
            'codesrc',
            true
        );
        //$valid['codesrc'] = $form['codesrc'] ?? '';

        // Validate `codedst` field [Destination Program Code]
        $valid['codedst'] = $this->validateProgramSession(
            $form['codedst'] ?? '',
            'codedst',
            true
        );
        //$valid['codedst'] = $form['codedst'] ?? '';

        // Validate `test` field [Restore data in test mode]
        $valid['test'] = $this->validateOnOffRadio(
            (int) $form['test'],
            'test',
            true
        );

        // Validate `delete` field [Restore data in delete mode]
        $valid['delete'] = $this->validateOnOffRadio(
            (int) $form['delete'],
            'delete',
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
