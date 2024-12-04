<?php

namespace App\services\database\troublelog\write;

use App\services\database\troublelog\TroublelogService as BaseService;

/**
 * ProgramService handles write operations for Program entities.
 *
 * @category Services
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class ProgramService extends BaseService
{
    /**
     * Query methods that interface directly with DB Class
     *
     * fetchProposalData - retrieves the proposal data
     */

    public function deletePrograms(string $delete): int
    {
        return $this->executeUpdateQuery($delete);
    }

    public function updateProgramsInfile(string $infile): int
    {
        return $this->executeUpdateQuery($infile);
    }
}
