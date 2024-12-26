<?php

declare(strict_types=1);

namespace App\services\database\troublelog\write;

use App\services\database\troublelog\TroublelogService as BaseService;

/**
 * DailyInstrumentService handles write operations for DailyInstrument entities.
 *
 * @category Services
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class DailyInstrumentService extends BaseService
{
    /**
     * Query methods that interface directly with DB Class
     *
     * fetchProposalData - retrieves the proposal data
     */

    public function deleteInstruments(string $delete): int
    {
        return $this->executeUpdateQuery($delete, [], '');
    }

    public function updateInstrumentsInfile(string $infile): int
    {
        return $this->executeUpdateQuery($infile, [], '');
    }
}
