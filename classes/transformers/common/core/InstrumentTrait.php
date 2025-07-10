<?php

declare(strict_types=1);

namespace App\transformers\common\core;

use App\transformers\common\utilities\InstrumentUtility;

trait InstrumentTrait
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
    public function transformInstruments(
        array $facility,
        array $visitor,
        array $allowedFacility,
        array $allowedVisitor
    ): array {
        return InstrumentUtility::consolidateInstrumentsForValidation(
            $facility,
            $visitor,
            $allowedFacility,
            $allowedVisitor
        );
    }
}
