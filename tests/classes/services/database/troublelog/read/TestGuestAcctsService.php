<?php

declare(strict_types=1);

namespace Tests\classes\services\database\troublelog\read;

use App\services\database\troublelog\read\GuestAcctsService as BaseService;

class TestGuestAcctsService extends BaseService
{
    /**
     * Proxy method for testing the protected getSemesterProgramsQuery method.
     *
     * @return string The params types string.
     */
    public function getSemesterProgramsQueryProxy(): string
    {
        return $this->getSemesterProgramsQuery();
    }

    /**
     * Proxy method for testing the protected getSingleProgramSessionQuery method.
     *
     * @return string The params types string.
     */
    public function getSingleProgramSessionQueryProxy(): string
    {
        return $this->getSingleProgramSessionQuery();
    }

    /**
     * Proxy method for testing the protected getValidateProgramQuery method.
     *
     * @return string The params types string.
     */
    public function getValidateProgramQueryProxy(): string
    {
        return $this->getValidateProgramQuery();
    }
}
