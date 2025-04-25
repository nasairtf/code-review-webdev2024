<?php

declare(strict_types=1);

namespace App\validators\forms\addguest;

use Exception;
use App\exceptions\ValidationException;
use App\core\irtf\IrtfUtilities;
use App\core\common\AbstractDebug          as Debug;
use App\validators\forms\BaseFormValidator as BaseValidator;

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

class GuestAcctsValidator extends BaseValidator
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
        switch ($form['command']) {
            case 'clearguest':
                // handle clearguest command;
                $validData = $this->validateClearguestData($form);
                break;
            case 'createguest':
                // handle createguest command;
                $validData = $this->validateCreateguestData($form);
                break;
            case 'extendguest':
                // handle extendguest command;
                $validData = $this->validateExtendguestData($form);
                break;
            case 'removeguest':
                // handle removeguest command;
                $validData = $this->validateRemoveguestData($form);
                break;
            case 'addguest':
            default:
                // handle addguest command;
                $validData = $this->validateAddguestData($form);
                break;
        }

        // Store `command` field
        $validData['command'] = $form['command'];

        // Return array
        return $validData;
    }

    private function validateAddguestData(
        array $form
    ): array {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Validator", "validateAddguestData");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($form, "{$debugHeading} -- form");

        // Build the validated data array for database
        $valid = [];

        // Validate `username` field
        $valid['username'] = $this->validateUsername(
            $form['username'] ?? '',
            'username',
            true
        );

        // Validate `acctname` field
        $valid['acctname'] = $this->validateName(
            $form['acctname'] ?? '',
            'acctname',
            true,
            50,
            'Acct label'
        );

        // Validate `uid` field
        $valid['uid'] = $this->validateNumberInRange(
            $form['uid'],
            'uid',
            true,
            1,
            20000,
            'Invalid uid.'
        );

        // Validate `gid` field
        $valid['gid'] = $this->validateNumberInRange(
            $form['gid'],
            'gid',
            true,
            1,
            20000,
            'Invalid gid.'
        );

        // Validate `shell` field
        $valid['shell'] = $this->validateShell(
            $form['shell'] ?? '',
            'shell',
            true
        );

        // Validate `passwd` field
        $valid['passwd'] = $this->validateName(
            $form['passwd'] ?? '',
            'passwd',
            true,
            50,
            'Passwd'
        );

        // Validate `accttype` field
        $valid['accttype'] = $this->validateSelection(
            [(int) $form['accttype']] ?? [],
            range(0, 10),
            'accttype',
            true,
            'Invalid account type.'
        )[0];

        // Validate `expiredays` field
        $valid['expiredays'] = $this->validateNumberInRange(
            0,
            'expiredays',
            false,
            -1,
            null
        );

        // Check for validation errors and throw an exception if any are found
        if (!empty($this->errors)) {
            throw new ValidationException("Validation errors occurred.", $this->errors);
        }

        // Return the validated data
        return $valid;
    }

    private function validateClearguestData(
        array $form
    ): array {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Validator", "validateClearguestData");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($form, "{$debugHeading} -- form");

        // This is a placeholder method in case clearguest validation is needed in future

        // Return the form data, nothing to validate for clearguest
        return $form;
    }

    private function validateCreateguestData(
        array $form
    ): array {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Validator", "validateCreateguestData");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($form, "{$debugHeading} -- form");

        // This is a placeholder method in case createguest validation is needed in future

        // Return the form data, nothing to validate for createguest
        return $form;
    }

    private function validateExtendguestData(
        array $form
    ): array {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Validator", "validateExtendguestData");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($form, "{$debugHeading} -- form");

        // Build the validated data array for database
        $valid = [];

        // Validate `username` field
        $valid['username'] = $this->validateUsername(
            $form['username'] ?? '',
            'username',
            true
        );

        // Validate `expiredays` field
        $valid['expiredays'] = $this->validateTimestamp(
            $form['expiredays'],
            'expiredays',
            true,
            null,
            null,
            'Invalid expiration.'
        );

        // Check for validation errors and throw an exception if any are found
        if (!empty($this->errors)) {
            throw new ValidationException("Validation errors occurred.", $this->errors);
        }

        // Return the validated data
        return $valid;
    }

    private function validateRemoveguestData(
        array $form
    ): array {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Validator", "validateRemoveguestData");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($form, "{$debugHeading} -- form");

        // Build the validated data array for database
        $valid = [];

        // Validate `username` field
        $valid['username'] = $this->validateUsername(
            $form['username'] ?? '',
            'username',
            true
        );

        // Validate `uid` field
        $valid['uid'] = $this->validateNumberInRange(
            $form['uid'],
            'uid',
            true,
            0,
            20000,
            'Invalid uid.'
        );

        // Validate `accttype` field
        $valid['accttype'] = $this->validateSelection(
            [(int) $form['accttype']] ?? [],
            range(0, 10),
            'accttype',
            true,
            'Invalid account type.'
        )[0];

        // Check for validation errors and throw an exception if any are found
        if (!empty($this->errors)) {
            throw new ValidationException("Validation errors occurred.", $this->errors);
        }

        // Return the validated data
        return $valid;
    }
}
