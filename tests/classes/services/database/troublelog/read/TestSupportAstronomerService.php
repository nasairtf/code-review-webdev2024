<?php

declare(strict_types=1);

namespace Tests\classes\services\database\troublelog\read;

use App\services\database\troublelog\read\SupportAstronomerService as BaseService;

class TestSupportAstronomerService extends BaseService
{
    /**
     * Proxy method for testing the protected getAllSupportAstronomersListQuery method.
     *
     * @param bool $sortAsc Determines 'ASC' or 'DESC' sort.
     *
     * @return string The SQL query string.
     */
    public function getAllSupportAstronomersListQueryProxy(bool $sortAsc): string
    {
        return $this->getAllSupportAstronomersListQuery($sortAsc);
    }

    /**
     * Proxy method for testing the protected getSupportAstronomerListQuery method.
     *
     * @param bool $sortAsc Determines 'ASC' or 'DESC' sort.
     *
     * @return string The SQL query string.
     */
    public function getSupportAstronomerListQueryProxy(bool $sortAsc): string
    {
        return $this->getSupportAstronomerListQuery($sortAsc);
    }
}
