<?php

declare(strict_types=1);

namespace App\core\htmlbuilder;

use App\exceptions\HtmlBuilderException;

/**
 * Trait for validating the presence of required keys in builder arrays.
 */
trait BuilderValidationTrait
{
    private function validateProgramFields(array $program): void
    {
        $this->validateRequiredFields(
            $program,
            [
                'semester',
                'programs',
                'pulldowns',
                'pi',
            ]
        );
    }

    /**
     * Validates that all required fields are present in the proposal array.
     *
     * This method ensures that the provided `$proposal` array contains all the
     * required keys needed for building the proposal list row. If any key is missing,
     * an `HtmlBuilderException` is thrown.
     *
     * Required keys:
     * - 'ObsApp_id'
     * - 'code'
     * - 'semesterYear'
     * - 'semesterCode'
     * - 'ProgramNumber'
     * - 'InvLastName1'
     *
     * @param array $proposal The proposal array to validate.
     *
     * @throws HtmlBuilderException If any required key is missing from the array.
     */
    private function validateProposalFields(array $proposal): void
    {
        $this->validateRequiredFields(
            $proposal,
            [
                'ObsApp_id',
                'code',
                'semesterYear',
                'semesterCode',
                'ProgramNumber',
                'InvLastName1',
            ]
        );
    }

    /**
     * Validates that all required fields are present in the proposal array.
     *
     * This method ensures that the provided `$proposal` array contains all the
     * required keys needed for building the proposal list row. If any key is missing,
     * an `HtmlBuilderException` is thrown.
     *
     * Required keys:
     * - 'a': Program ID.
     * - 'i': Database application ID.
     * - 'n': PI's last name.
     * - 's': Semester tag (e.g., '2024B').
     *
     * @param array $proposal The proposal array to validate.
     *
     * @throws HtmlBuilderException If any required key is missing from the array.
     */
    private function validateFeedbackProposalFields(array $proposal): void
    {
        $this->validateRequiredFields(
            $proposal,
            [
                'a',
                'i',
                'n',
                's',
            ]
        );
    }

    /**
     * Validates that all required fields are present in a button configuration array.
     *
     * This method ensures that the provided `$button` array contains the keys
     * required for rendering a button. If any key is missing, an `HtmlBuilderException` is thrown.
     *
     * Required keys:
     * - 'type' (e.g., 'submit', 'reset', 'button')
     * - 'label' (text displayed on the button)
     *
     * @param array $button The button configuration array to validate.
     *
     * @throws HtmlBuilderException If any required key is missing.
     */
    public function validateButtonFields(array $button): void
    {
        $this->validateRequiredFields(
            $button,
            [
                'type',
                'label',
            ]
        );
    }

    /**
     * Validates an array of buttons.
     *
     * Ensures the array is non-empty and that all buttons are either valid configuration arrays
     * (for dynamic generation) or valid pre-rendered HTML strings. If the array is empty,
     * or contains invalid data, an `HtmlBuilderException` is thrown.
     *
     * @param array $buttons An array of button configuration arrays or pre-rendered HTML strings.
     *
     * @throws HtmlBuilderException If the array is empty, or contains invalid items.
     */
    public function validateButtons(array $buttons): void
    {
        // Check if the buttons array is empty
        if (empty($buttons)) {
            throw new HtmlBuilderException('Buttons array cannot be empty.');
        }

        foreach ($buttons as $button) {
            if (is_string($button)) {
                // Pre-rendered HTML strings are valid, skip further validation
                continue;
            }

            if (is_array($button)) {
                // Validate configuration arrays
                $this->validateButtonFields($button);
            } else {
                // Invalid type
                throw new HtmlBuilderException(
                    'Buttons array must contain either pre-rendered HTML strings or configuration arrays.'
                );
            }
        }
    }

    /**
     * Validates that all required keys are present in the proposal array.
     *
     * This method checks whether all the provided `$requiredKeys` exist in the
     * `$proposal` array. If any key is missing, an `HtmlBuilderException` is thrown.
     *
     * @param array $proposal    The proposal array to validate.
     * @param array $requiredKeys The list of keys that must be present in the proposal.
     *
     * @throws HtmlBuilderException If any required key is missing from the array.
     */
    private function validateRequiredFields(array $proposal, array $requiredKeys): void
    {
        $missingKeys = array_filter($requiredKeys, function ($key) use ($proposal) {
            return !array_key_exists($key, $proposal);
        });

        if (!empty($missingKeys)) {
            throw new HtmlBuilderException(
                'Missing required keys in proposal array: ' . implode(', ', $missingKeys)
            );
        }
    }
}
