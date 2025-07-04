<?php

declare(strict_types=1);

namespace App\transformers\common\utilities;

class InstrumentUtility
{
    /**
     * Combines instrument selections from user input and allowed options.
     *
     * @param array  $facility        Facility instruments selected.
     * @param string $visitor         Visitor instrument selected.
     * @param array  $allowedFacility Allowed facility instrument options.
     * @param array  $allowedVisitor  Allowed visitor instrument options.
     *
     * @return array An array containing 'values' (inputs) and 'allowed' (database options).
     */
    public static function consolidateInstrumentsForValidation(
        array $facility,
        array $visitor,
        array $allowedFacility,
        array $allowedVisitor
    ): array {
        // Consolidate the selected instruments
        $values = array_merge(
            $facility ?? [],
            array_filter(
                $visitor ?? [],
                function ($v) {
                    return $v !== 'none';
                }
            )
        );

        // Consolidate the allowed instruments
        $allowedValues = array_merge(
            $allowedFacility ?? [],
            array_filter(
                $allowedVisitor ?? [],
                function ($key) {
                    return $key !== 'none';
                },
                ARRAY_FILTER_USE_KEY
            )
        );

        // Return both arrays
        return ['values' => $values ?? [], 'allowed' => $allowedValues ?? []];
    }
}
