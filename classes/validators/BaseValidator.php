<?php

declare(strict_types=1);

namespace App\validators;

use Exception;
use App\exceptions\ValidationException;
use App\core\common\DebugFactory;
use App\core\common\AbstractDebug             as Debug;
use App\validators\common\AbstractValidator;
use App\validators\common\ValidationResult    as Result;
use App\validators\common\core\ValidationCore as Core;

/**
 * BaseValidator serves as the validation orchestrator for all concrete validators.
 *
 * It coordinates context and validation plans, executes validation routines
 * via ValidationCore, and manages ValidationResult and Debug.
 *
 * Concrete validators define the validation plan and output formatting logic.
 *
 * @category Validators
 * @package  IRTF
 * @version  1.0.0
 */
abstract class BaseValidator extends AbstractValidator
{
    /** @var Debug Debugging/tracing interface */
    protected $debug;

    /** @var Core ValidationCore: orchestrates utility-backed validation methods */
    protected $core;

    /** @var Result ValidationResult: accumulates field values and validation errors */
    protected $result;

    /**
     * BaseValidator constructor with optional dependency injection.
     *
     * @param Debug|null  $debug  Debug instance for output tracing
     * @param Core|null   $core   ValidationCore instance
     * @param Result|null $result ValidationResult to populate
     */
    public function __construct(
        ?Debug $debug = null,
        ?Core $core = null,
        ?Result $result = null
    ) {
        // Initialize debugging
        $this->debug = $debug ?? DebugFactory::create('default', false, 0);
        $debugHeading = $this->debug->debugHeading("BaseValidator", "__construct");
        $this->debug->debug($debugHeading);

        // Initialise dependencies with fallbacks
        $this->core = $core ?? new Core();
        $this->result = $result ?? new Result();
        $this->debug->debug("{$debugHeading} -- Core, Result classes successfully initialised.");

        // Constructor completed
        $this->debug->debug("{$debugHeading} -- Parent Validator initialisation complete.");
    }

    /**
     * Main execution method: validates input data against a field plan.
     *
     * Runs required checks, dispatches validation methods, and throws
     * a formatted ValidationException if any field fails.
     *
     * @param array $data    Input data to validate
     * @param array $context Supplemental data to build the validation plan
     *
     * @return array Clean, validated controller-ready values
     *
     * @throws ValidationException If any validation fails
     */
    public function validateData(
        array $data,
        array $context = []
    ): array {
        // Debug output
        $debugHeading = $this->debug->debugHeading("BaseValidator", "validateData");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($data, "{$debugHeading} -- data");
        $this->debug->debugVariable($context, "{$debugHeading} -- context");

        $plan = $this->getValidationPlan($data, $context);
        $normalizedPlan = $this->normalizeValidationPlan($plan);
        $this->debug->debugVariable($normalizedPlan, "{$debugHeading} -- normalizedPlan");

        foreach ($normalizedPlan as $key => $step) {
            // Output the step to be processed
            $this->debug->debug("{$debugHeading} -- normalizedPlan step[{$key}]: {$step['field']} [START]");
            $this->debug->debugVariable($step, "{$debugHeading} -- step");

            // Retrieve the method to be used for validating this step
            $method = $step['method'];
            $this->debug->debugVariable($method, "{$debugHeading} -- method");

            // If value is required but missing, skip further validation for this field
            if ($this->skipValidationIfMissing($step, $data)) {
                continue;
            }

            // Confirm given method exists in ValidationCore prior to constructing arguments
            if (!method_exists($this->core, $method)) {
                $this->debug->fail("Method '{$method}' does not exist on ValidationCore.");
            }

            // Build method arguments: [result, value, field, ...additional args]
            $methodArgs = $this->buildMethodArgs($step, $data);
            $this->debug->debugVariable($methodArgs, "{$debugHeading} -- methodArgs");

            // All core methods return the same result instance passed in (mutable)
            call_user_func_array([$this->core, $method], $methodArgs);

            // This step's processing is complete
            $this->debug->debugVariable($this->result->getAllErrors(), "{$debugHeading} -- result->getAllErrors()");
            $this->debug->debug("{$debugHeading} -- normalizedPlan step[{$key}]: {$step['field']} [COMPLETE]");
        }

        // Final check; returns formatted errors array with the thrown exception
        $this->throwIfErrors($normalizedPlan);

        // Return formatted validated data array
        return $this->formatValidData($normalizedPlan);
    }

    /**
     * Validates and normalizes the raw validation plan structure.
     *
     * Ensures field entries are complete and consistently typed, and fills in
     * any missing optional keys with sane defaults.
     *
     * @param array $rawPlan Raw validation plan from getValidationPlan()
     *
     * @return array Normalized plan with explicit structure
     */
    protected function normalizeValidationPlan(
        array $rawPlan
    ): array {
        // Debug output
        $debugHeading = $this->debug->debugHeading("BaseValidator", "normalizeValidationPlan");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($rawPlan, "{$debugHeading} -- rawPlan");

        $normalized = [];

        // Ensure each field's plan piece is complete
        foreach ($rawPlan as $index => $step) {
            // Required: must exist
            if (!isset($step['field'], $step['method'])) {
                $this->debug->fail("Validation step at index ${index} is missing 'field' or 'method'");
            }

            $field  = $step['field'];
            $fields = $step['fields'] ?? (array) $step['field'];
            $method = $step['method'];

            if (!is_string($field)) {
                $this->debug->fail("'field' must be a string at step index ${index}");
            }

            if (!is_string($method)) {
                $this->debug->fail("'method' must be a string at step index ${index}");
            }

            if (!is_array($fields)) {
                $this->debug->fail("'fields' must be an array at step index ${index}");
            }

            if (isset($step['args']) && !is_array($step['args'])) {
                $this->debug->fail("'args' must be an array for field '${field}'");
            }

            if (isset($step['required']) && !is_bool($step['required'])) {
                $this->debug->fail("'required' must be a boolean for field '${field}'");
            }

            if (isset($step['required_msg']) && !is_string($step['required_msg'])) {
                $this->debug->fail("'required_msg' must be a string for field '${field}'");
            }

            $normalized[] = [
                'field'         => $field,
                'fields'        => $fields,
                'method'        => $method,
                'args'          => $step['args'] ?? [],
                'required'      => $step['required'] ?? false,
                'required_msg'  => $step['required_msg'] ?? 'This field is required',
            ];
        }

        return $normalized;
    }

    /**
     * Determines whether a validation method should be skipped due to missing required inputs.
     *
     * Validates presence of each input field listed in 'fields' using validateRequiredField().
     * Returns true if any required input is missing and records an error accordingly.
     *
     * @param array $step  Normalized validation step from the plan
     * @param array $data  Raw submitted input data
     *
     * @return bool True if validation for this step should be skipped
     */
    protected function skipValidationIfMissing(
        array $step,
        array $data
    ): bool {
        // Debug output
        $debugHeading = $this->debug->debugHeading("BaseValidator", "skipValidationIfMissing");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($step, "{$debugHeading} -- step");
        $this->debug->debugVariable($data, "{$debugHeading} -- data");

        $fields      = $step['fields'];
        $method      = $step['method'];
        $isRequired  = $step['required'];
        $requiredMsg = $step['required_msg'];

        // Check field requirement(s) (handles both scalar and composite fields)
        $missing = false;
        // Determine if input is required and present
        foreach ($fields as $f) {
            $value = $data[$f] ?? null;

            // Enforce presence check and store input if required OR provided [result instance is returned]
            $this->core->validateRequiredField(
                $this->result,
                $value,
                $isRequired,
                $f,
                $requiredMsg
            );

            // If input is required but missing, skip further validation for this field
            if ($isRequired && !$this->result->hasFieldValue($f)) {
                $this->debug->debug("{$debugHeading} -- Skipping '{$method}' for field '{$f}' (missing required value)");
                $missing = true;
            }
        }
        return $missing;
    }

    /**
     * Constructs the argument list for a validation method.
     *
     * For scalar validations, pulls a single value from $data.
     * For composite validations, pulls multiple values in field order.
     *
     * Resulting signature is:
     *   [ValidationResult, <value(s)>, string $fieldKey, ...$args]
     *
     * @param array $step  Normalized validation step
     * @param array $data  Raw submitted input data
     *
     * @return array Argument list for call_user_func_array()
     */
    protected function buildMethodArgs(
        array $step,
        array $data
    ): array {
        // Debug output
        $debugHeading = $this->debug->debugHeading("BaseValidator", "buildMethodArgs");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($step, "{$debugHeading} -- step");
        $this->debug->debugVariable($data, "{$debugHeading} -- data");

        // Build field validation method arguments
        $values = [];
        foreach ($step['fields'] as $f) {
            $values[] = $data[$f] ?? null;
        }
        return array_merge([$this->result], $values, [$step['field']], $step['args']);
    }

    /**
     * Throws a ValidationException if errors exist after validation execution.
     *
     * Converts internal ValidationResult format into controller-facing structure
     * via formatErrors(), and halts control flow on failure.
     *
     * @param array $normalizedPlan The resolved validation plan for this execution
     *
     * @throws ValidationException If any validation failed
     */
    protected function throwIfErrors(
        array $normalizedPlan
    ): void {
        // Debug output
        $debugHeading = $this->debug->debugHeading("BaseValidator", "throwIfErrors");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($normalizedPlan, "{$debugHeading} -- normalizedPlan");
        $this->debug->debugVariable($this->result->hasErrors(), "{$debugHeading} -- result->hasErrors()");
        $this->debug->debugVariable($this->result->getAllErrors(), "{$debugHeading} -- result->getAllErrors()");

        // Determine if there are errors in the result object and if so, rethrow them
        if ($this->result->hasErrors()) {
            $errors = $this->formatErrors($normalizedPlan);
            throw new ValidationException('Validation errors occurred.', $errors);
        }
    }

    /**
     * Aggregates and returns all validation error messages that match a composite field prefix.
     *
     * This is used for cases where a logical field (e.g. "dates") is composed of several
     * input fields (e.g. "dates_start", "dates_end"). The method looks for all error keys
     * beginning with the given prefix and merges their first-level messages into a single string.
     *
     * @param string $fieldPrefix The prefix to match against field keys in ValidationResult.
     *
     * @return string Combined error message string.
     */
    protected function collectCompositeFieldErrors(
        string $fieldPrefix
    ): string {
        // Debug output
        $debugHeading = $this->debug->debugHeading("BaseValidator", "collectCompositeFieldErrors");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($fieldPrefix, "{$debugHeading} -- fieldPrefix");

        // Handle the result object errors that are not associated with inputs or fields
        $allErrors = $this->result->getAllErrors();
        $messages = [];
        foreach ($allErrors as $key => $errors) {
            if (strpos($key, $fieldPrefix) === 0 && is_array($errors)) {
                foreach ($errors as $msg) {
                    $messages[] = $msg;
                }
            }
        }
        return implode('; ', $messages);
    }
}
