<?php

namespace App\services\database\troublelog\write;

use App\services\database\troublelog\TroublelogService as BaseService;

/**
 * ScheduleObsService handles write operations for ScheduleObs entities.
 *
 * @category Services
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class ScheduleObsService extends BaseService
{
    /**
     * Query methods that interface directly with DB Class
     *
     * fetchProposalData - retrieves the proposal data
     */

    public function deleteSchedule(string $delete): int
    {
        return $this->executeUpdateQuery($delete);
    }

    public function updateScheduleInfile(string $infile): int
    {
        return $this->executeUpdateQuery($infile);
    }
}
