<?php

declare(strict_types=1);

namespace App\services\database\troublelog\write;

use App\services\database\troublelog\TroublelogService as BaseService;

/**
 * ProgramService handles write operations for EngProgram entities.
 *
 * @category Services
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class EngProgramService extends BaseService
{
    /**
     * Query methods that interface directly with DB Class
     *
     * fetchProposalData - retrieves the proposal data
     */

    public function deleteEngPrograms(string $delete): int
    {
        return $this->executeUpdateQuery($delete, [], '');
    }

    public function updateEngProgramsInfile(string $infile): int
    {
        return $this->executeUpdateQuery($infile, [], '');
    }
}
