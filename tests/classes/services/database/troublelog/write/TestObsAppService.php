<?php

declare(strict_types=1);

namespace Tests\classes\services\database\troublelog\write;

use App\services\database\troublelog\write\ObsAppService as BaseService;

class TestObsAppService extends BaseService
{
    /**
     * Proxy method for testing the protected getUpdateProposalCreationDateQuery method.
     *
     * @return string The SQL query string.
     */
    public function getUpdateProposalCreationDateQueryProxy(): string
    {
        return $this->getUpdateProposalCreationDateQuery();
    }
}
