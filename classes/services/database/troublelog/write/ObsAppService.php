<?php

declare(strict_types=1);

namespace App\services\database\troublelog\write;

use App\services\database\troublelog\TroublelogService as BaseService;

/**
 * ObsAppService handles write operations for ObsApp entities.
 *
 * @category Services
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class ObsAppService extends BaseService
{
    /**
     * Query methods that interface directly with DB Class
     *
     * fetchProposalData - retrieves the proposal data
     */

    public function modifyProposalCreationDate(int $obsAppId, int $timestamp): int
    {
        return $this->modifyDataWithQuery(
            $this->getUpdateProposalCreationDateQuery(),
            [$timestamp, $obsAppId],
            'ii',
            1,
            'Timestamp update failed.'
        );
    }

    /**
     * Helper methods to return the query strings
     *
     * getUpdateProposalCreationDateQuery - return proposal date update SQL string
     */

    private function getUpdateProposalCreationDateQuery(): string
    {
        return "UPDATE ObsApp SET creationDate = ? WHERE ObsApp_id = ?;";
    }
}
