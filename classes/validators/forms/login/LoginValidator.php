<?php

declare(strict_types=1);

namespace App\validators\forms\login;

use Exception;
use App\exceptions\ValidationException;
use App\core\common\AbstractDebug as Debug;
use App\validators\BaseValidator;

/**
 * Validator for login form input (refactored to use ValidationCore architecture).
 *
 * This class handles validation for login form fields using a declarative
 * validation plan executed by BaseValidator and ValidationCore. It validates
 * program numbers and session codes, returning controller-safe output or
 * throwing a structured ValidationException when needed.
 *
 * Responsibilities:
 * - Define validation steps via a normalized plan.
 * - Delegate execution to the core validation framework.
 * - Format output and errors for controller use.
 *
 * @category Validators
 * @package  IRTF
 * @version  2.0.0
 */
class LoginValidator extends BaseValidator
{
    /**
     * Constructor for LoginValidator.
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

    // Abstract methods: getValidationPlan(), formatValidData(), formatErrors()

    /**
     * Defines the validation plan for the login form.
     *
     * Validates:
     * - 'program': program number string (e.g., 2025B001) with min/max year checks
     * - 'session': session code string (10-character guest or ENG session format)
     *
     * @param array $data    Input data to validate.
     * @param array $context Contextual info (currently unused).
     *
     * @return array Normalized validation plan for BaseValidator.
     */
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

    /**
     * Formats validated data for controller use.
     *
     * Uses the normalized plan to extract and return clean values for:
     * - 'program'
     * - 'session'
     *
     * @param array $normalizedPlan The plan used for this validation run.
     *
     * @return array Cleaned validated values ready for controller or model use.
     */
    protected function formatValidData(array $normalizedPlan): array
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Validator", "formatValidData");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($normalizedPlan, "{$debugHeading} -- normalizedPlan");

        return $this->formatStdValidData($normalizedPlan);
    }

    /**
     * Formats validation errors for controller use.
     *
     * Reduces multi-error arrays to a single user-facing message per field.
     *
     * @param array $normalizedPlan The plan used for this validation run.
     *
     * @return array Associative array of field => message.
     */
    protected function formatErrors(array $normalizedPlan): array
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Validator", "formatErrors");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($normalizedPlan, "{$debugHeading} -- normalizedPlan");

        return $this->formatStdErrors($normalizedPlan);
    }
}
