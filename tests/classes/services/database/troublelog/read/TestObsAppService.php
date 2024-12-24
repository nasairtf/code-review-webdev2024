<?php

declare(strict_types=1);

namespace Tests\classes\services\database\troublelog\read;

use App\services\database\troublelog\read\ObsAppService as BaseService;

class TestObsAppService extends BaseService
{
    /**
     * Proxy method for testing the protected getScheduleSemesterProgramListQuery method.
     *
     * @return string The params types string.
     */
    public function getScheduleSemesterProgramListQueryProxy(): string
    {
        return $this->getScheduleSemesterProgramListQuery();
    }

    /**
     * Proxy method for testing the protected getProposalQuery method.
     *
     * @param string $condition The WHERE clause to use:
     *                          - 'program':  Filter by semesterYear, semesterCode, and ProgramNumber.
     *                          - 'semester': Filter by semesterYear and semesterCode.
     *                          - 'session':  Filter by ObsApp_id.
     *
     * @return string The SQL query string.
     */
    public function getProposalQueryProxy(string $condition = 'session'): string
    {
        return $this->getProposalQuery($condition);
    }

    /**
     * Proxy method for testing the protected getProposalListingFormDataQuery method.
     *
     * @param bool $sortAsc Determines 'ASC' or 'DESC' sort.
     *
     * @return string The SQL query string.
     *//*
    public function getProposalListingFormDataQueryProxy(bool $semester): string
    {
        return $this->getProposalListingFormDataQuery($semester);
    }*/

    /**
     * Proxy method for testing the protected getProposalProgramDataQuery method.
     *
     * @return string The params types string.
     *//*
    public function getProposalProgramDataQueryProxy(): string
    {
        return $this->getProposalProgramDataQuery();
    }*/
}
