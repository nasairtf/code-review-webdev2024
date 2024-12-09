<?php

declare(strict_types=1);

namespace App\core\htmlbuilder;

use App\exceptions\HtmlBuilderException;

/**
 * Trait for validating the presence of required keys in proposal arrays.
 */
trait ProposalValidationTrait
{
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
        $requiredKeys = [
            'ObsApp_id',
            'code',
            'semesterYear',
            'semesterCode',
            'ProgramNumber',
            'InvLastName1'
        ];

        $missingKeys = [];
        foreach ($requiredKeys as $key) {
            if (!array_key_exists($key, $proposal)) {
                $missingKeys[] = $key;
            }
        }

        if (!empty($missingKeys)) {
            throw new HtmlBuilderException(
                'Missing required keys in proposal array: ' . implode(', ', $missingKeys)
            );
        }
    }
}
