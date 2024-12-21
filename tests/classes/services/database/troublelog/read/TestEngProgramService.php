<?php

declare(strict_types=1);

namespace Tests\classes\services\database\troublelog\read;

use App\services\database\troublelog\read\EngProgramService as BaseService;

class TestEngProgramService extends BaseService
{
    /**
     * Proxy method for testing the protected getProposalEngProgramDataQuery method.
     *
     * @return string The params types string.
     */
    public function getProposalEngProgramDataQueryProxy(): string
    {
        return $this->getProposalEngProgramDataQuery();
    }
}
