<?php

namespace App\services\database\troublelog\write;

use App\services\database\troublelog\TroublelogService as BaseService;

/**
 * DailyOperatorService handles write operations for DailyOperator entities.
 *
 * @category Services
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class DailyOperatorService extends BaseService
{
    /**
     * Query methods that interface directly with DB Class
     *
     * fetchProposalData - retrieves the proposal data
     */

    public function deleteOperators(string $delete): int
    {
        return $this->executeUpdateQuery($delete);
    }

    public function updateOperatorsInfile(string $infile): int
    {
        return $this->executeUpdateQuery($infile);
    }
}
