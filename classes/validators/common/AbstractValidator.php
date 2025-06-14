<?php

declare(strict_types=1);

namespace App\validators\common;

/**
 * AbstractValidator defines the interface contract for all validator classes.
 *
 * All concrete validators must implement:
 * - validateData(): the main orchestration entry point
 * - getValidationPlan(): returns the declarative list of validation steps
 * - formatValidData(): prepares sanitized output for controller use
 * - formatErrors(): transforms internal error state into a controller-facing structure
 *
 * Validators operate on input + context and return validated data or throw on failure.
 *
 * @category Validators
 * @package  IRTF
 * @version  1.0.0
 */
abstract class AbstractValidator
{
    /**
     * Entry point for validation execution.
     *
     * Performs validation using the defined plan and context, and returns
     * a controller-safe array of validated data. May throw ValidationException
     * if validation fails.
     *
     * @param array $data    Raw user input or form/script values
     * @param array $context Additional data needed for validation (constraints, lists, etc.)
     *
     * @return array Validated and filtered output data for downstream use
     *
     * @throws ValidationException If any field fails validation
     */
    abstract public function validateData(array $data, array $context = []): array;

    /**
     * Defines the validation steps required for the current operation.
     *
     * This declarative plan determines what validation routines to run on each field.
     * It must be fully resolved — no dynamic lookups inside utility methods.
     *
     * @param array $data    Preprocessed or merged form/script input data
     * @param array $context External dependencies needed for building the plan
     *
     * @return array Validation plan structure (before orchestration)
     */
    abstract protected function getValidationPlan(array $data, array $context = []): array;

    /**
     * Extracts controller-safe validated values from the result set.
     *
     * This should return only the fields the controller cares about, in the format
     * it expects (e.g., scalar, timestamp, or flattened structure).
     *
     * @param array $normalizedPlan The fully-normalized validation plan for this run
     *
     * @return array Validated data for use by the controller
     */
    abstract protected function formatValidData(array $normalizedPlan): array;

    /**
     * Extracts controller-safe error messages from the result set.
     *
     * This should return only the fields declared in the plan, with flattened
     * error messages suitable for user feedback.
     *
     * @param array $normalizedPlan The fully-normalized validation plan for this run
     *
     * @return array Field-to-message mapping for failed fields
     */
    abstract protected function formatErrors(array $normalizedPlan): array;
}
