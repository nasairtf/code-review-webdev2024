<?php

declare(strict_types=1);

namespace App\validators\forms\feedback;

use Exception;
use App\exceptions\ValidationException;
use App\core\common\AbstractDebug as Debug;
use App\validators\BaseValidator;

/**
 * FeedbackValidator handles validation for the user feedback submission form.
 *
 * It defines the validation plan, interprets validated results, and formats
 * validation errors for frontend consumption. It supports both scalar and composite
 * inputs (e.g., dates, instrument selections) and integrates program integrity checks.
 *
 * @category Validators
 * @package  IRTF
 * @version  1.0.0
 */
class FeedbackValidator extends BaseValidator
{
    /**
     * Constructor for FeedbackValidator.
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

    /**
     * Returns the validation plan defining each field’s validation strategy.
     *
     * Each plan step defines the field name, associated input keys, validation method,
     * optional arguments, and requirement policy. Composite fields are explicitly
     * identified using the `fields` array.
     *
     * @param array $data    Submitted input data.
     * @param array $context Supplemental context such as allowed selections.
     *
     * @return array Structured validation plan.
     */
    protected function getValidationPlan(array $data, array $context = []): array
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Validator", "getValidationPlan");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($data, "{$debugHeading} -- data");
        $this->debug->debugVariable($context, "{$debugHeading} -- context");

        return [
            [
                'field'         => 'respondent',
                'fields'        => ['respondent'],
                'method'        => 'validateNameField',
                'args'          => [70],
                'required'      => true,
                'required_msg'  => 'This field is required',
            ],
            [
                'field'         => 'email',
                'fields'        => ['email'],
                'method'        => 'validateEmailField',
                'args'          => [70],
                'required'      => true,
                'required_msg'  => 'This field is required',
            ],
            [
                'field'         => 'dates',
                'fields'        => [
                    'startyear',
                    'startmonth',
                    'startday',
                    'endyear',
                    'endmonth',
                    'endday',
                ],
                'method'        => 'validateDateRange',
                'args'          => [],
                'required'      => true,
                'required_msg'  => 'These fields are required',
            ],
            [
                'field'         => 'support_staff',
                'fields'        => ['support_staff'],
                'method'        => 'validateSelection',
                'args'          => [$context['support']],
                'required'      => true,
                'required_msg'  => 'This field is required',
            ],
            [
                'field'         => 'operator_staff',
                'fields'        => ['operator_staff'],
                'method'        => 'validateSelection',
                'args'          => [$context['operator']],
                'required'      => true,
                'required_msg'  => 'This field is required',
            ],
            [
                'field'         => 'instruments',
                'fields'        => ['instruments'],
                'method'        => 'validateSelection',
                'args'          => [$context['instruments']],
                'required'      => true,
                'required_msg'  => 'At least one instrument must be selected',
            ],
            [
                'field'         => 'location',
                'fields'        => ['location'],
                'method'        => 'validateLocation',
                'args'          => [],
                'required'      => true,
                'required_msg'  => 'This field is required',
            ],
            [
                'field'         => 'experience',
                'fields'        => ['experience'],
                'method'        => 'validateRating',
                'args'          => [false],
                'required'      => true,
                'required_msg'  => 'This field is required',
            ],
            [
                'field'         => 'technical',
                'fields'        => ['technical'],
                'method'        => 'validateLongTextField',
                'args'          => [500],
                'required'      => true,
                'required_msg'  => 'This field is required',
            ],
            [
                'field'         => 'scientificstaff',
                'fields'        => ['scientificstaff'],
                'method'        => 'validateRating',
                'args'          => [true],
                'required'      => true,
                'required_msg'  => 'This field is required',
            ],
            [
                'field'         => 'operators',
                'fields'        => ['operators'],
                'method'        => 'validateRating',
                'args'          => [true],
                'required'      => true,
                'required_msg'  => 'This field is required',
            ],
            [
                'field'         => 'daycrew',
                'fields'        => ['daycrew'],
                'method'        => 'validateRating',
                'args'          => [true],
                'required'      => true,
                'required_msg'  => 'This field is required',
            ],
            [
                'field'         => 'personnel',
                'fields'        => ['personnel'],
                'method'        => 'validateLongTextField',
                'args'          => [500],
                'required'      => false,
                'required_msg'  => '',
            ],
            [
                'field'         => 'scientific',
                'fields'        => ['scientific'],
                'method'        => 'validateLongTextField',
                'args'          => [500],
                'required'      => false,
                'required_msg'  => '',
            ],
            [
                'field'         => 'comments',
                'fields'        => ['comments'],
                'method'        => 'validateLongTextField',
                'args'          => [500],
                'required'      => false,
                'required_msg'  => '',
            ],
        ];
    }

    /**
     * Converts validated values into the canonical format expected by the controller.
     *
     * Handles renaming of composite fields (e.g., dates → start_date, end_date) and
     * passes scalar fields through untouched. Used after all validation has passed.
     *
     * @param array $normalizedPlan Normalized validation plan steps.
     *
     * @return array Formatted validated data.
     */
    protected function formatValidData(array $normalizedPlan): array
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Validator", "formatValidData");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($normalizedPlan, "{$debugHeading} -- normalizedPlan");

        $formatted = [];
        foreach ($normalizedPlan as $step) {
            // Extract the field's name
            $field = $step['field'];
            // Store the formatted and validated field data
            switch ($field) {
                case 'dates':
                    // The dates branch data is stored with element names different from inputs AND fields
                    $formatted['start_date'] = $this->result->getFieldValue('dates_start_timestamp');
                    $formatted['end_date'] = $this->result->getFieldValue('dates_end_timestamp');
                    $this->debug->debugVariable($formatted['start_date'], "{$debugHeading} -- formatted[start_date]");
                    $this->debug->debugVariable($formatted['end_date'], "{$debugHeading} -- formatted[end_date]");
                    break;
                case 'instruments':
                    // This branch might be able to fold into the default branch....
                    $formatted['instruments'] = $this->result->getFieldValue('instruments');
                    $this->debug->debugVariable($formatted['instruments'], "{$debugHeading} -- formatted[instruments]");
                    break;
                default:
                    // Retrieve the field's value(s) stored in the result object
                    $formatted[$field] = $this->result->getFieldValue($field);
                    $this->debug->debugVariable($formatted[$field], "{$debugHeading} -- formatted[$field]");
            }
        }
        return $formatted;
    }

    /**
     * Converts internal error structure into controller-facing error array.
     *
     * Returns errors keyed by the input name(s), including composite field aggregations
     * when needed (e.g., dates). Only the first error per input is returned.
     *
     * @param array $normalizedPlan Normalized validation plan steps.
     *
     * @return array Formatted validation errors keyed by input.
     */
    protected function formatErrors(array $normalizedPlan): array
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Validator", "formatErrors");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($normalizedPlan, "{$debugHeading} -- normalizedPlan");

        // Process each step's inputs for errors; where fields different from inputs additional checks are below
        $formatted = [];
        foreach ($normalizedPlan as $step) {
            // Extract the input name(s)
            $fields = $step['fields'];
            // Retrieve the input errors and store them to be returned to the controller
            foreach ($fields as $input) {
                // Retrieve the input's error(s) stored in the result object
                $error = $this->result->getFieldErrors($input);
                // Only add the input field if there are real errors for it
                if (is_array($error) && isset($error[0])) {
                    // Store the formatted input field error(s)
                    $formatted[$input] = $error[0];
                    $this->debug->debugVariable($formatted[$input], "{$debugHeading} -- formatted[$input]");
                }
            }

            // Grab the overarching field errors
            if (count($fields) !== 1) {
                // Extract the field name
                $field = $step['field'];
                // Retrieve the umbrella field's error(s) stored in the result object
                $fieldErrors = $this->collectCompositeFieldErrors($field);
                // Only add the field if there are real errors for it
                if ($fieldErrors !== '') {
                    // Store the formatted field error(s)
                    $formatted[$field] = $fieldErrors;
                    $this->debug->debugVariable($formatted[$field], "{$debugHeading} -- formatted[$field]");
                }
            }
        }
        return $formatted;
    }

    /**
     * Confirms that submitted program metadata matches trusted server values.
     *
     * Validates that critical form keys ('a', 'i', 'n', 's') were not tampered with
     * between initial render and submission. Throws debug failure if mismatch is detected.
     *
     * @param array $submitted Client-submitted program data (e.g., $_POST).
     * @param array $trusted   Server-side trusted values for comparison.
     *
     * @throws Exception If integrity check fails.
     */
    public function validateProgramIntegrity(array $submitted, array $trusted): void
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Validator", "validateProgramIntegrity");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($submitted, "{$debugHeading} -- submitted");
        $this->debug->debugVariable($trusted, "{$debugHeading} -- trusted");

        $message = "Program data mismatch: "
            . "feedback form has been altered or corrupted. "
            . "Please refresh the page and try again.";
        foreach (['a', 'i', 'n', 's'] as $key) {
            if ((string) ($submitted[$key] ?? '') !== (string) ($trusted[$key] ?? '')) {
                $this->debug->fail($message);
            }
        }
        $this->debug->debug("{$debugHeading} -- SUCCESS! Integrity check passed!");
    }
}
