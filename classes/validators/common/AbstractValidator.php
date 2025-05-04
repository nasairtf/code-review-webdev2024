<?php

declare(strict_types=1);

namespace App\validators\common;

/**
 * AbstractValidator defines the interface contract for all validator classes.
 *
 * All validators must implement getValidationPlan() and validateData()
 *
 * @category Validators
 * @package  IRTF
 * @version  1.0.0
 */
abstract class AbstractValidator
{
    /**
     * Validate data using the validation plan and return a validated dataset.
     *
     * @param array $data    Input data to validate
     * @param array $context Additional reference values needed for validation
     *
     * @return array Validated and transformed data
     */
    abstract public function validateData(array $data, array $context = []): array;

    /**
     * Define the list of validation tasks to run, indexed by field name.
     * Each entry may include method name, params, and optional rules.
     *
     * @param array $data    Submitted or merged form data
     * @param array $context Additional contextual info, e.g., allowed lists
     *
     * @return array Validation plan structure
     */
    abstract protected function getValidationPlan(array $data, array $context = []): array;
}
