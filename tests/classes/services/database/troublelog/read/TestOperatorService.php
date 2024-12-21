<?php

declare(strict_types=1);

namespace Tests\classes\services\database\troublelog\read;

use App\services\database\troublelog\read\OperatorService as BaseService;

class TestOperatorService extends BaseService
{
    /**
     * Proxy method for testing the protected getAllOperatorsListQuery method.
     *
     * @param bool $sortAsc Determines 'ASC' or 'DESC' sort.
     *
     * @return string The SQL query string.
     */
    public function getAllOperatorsListQueryProxy(bool $sortAsc): string
    {
        return $this->getAllOperatorsListQuery($sortAsc);
    }

    /**
     * Proxy method for testing the protected getTelescopeOperatorsListQuery method.
     *
     * @param bool $sortAsc Determines 'ASC' or 'DESC' sort.
     *
     * @return string The SQL query string.
     */
    public function getTelescopeOperatorsListQueryProxy(bool $sortAsc): string
    {
        return $this->getTelescopeOperatorsListQuery($sortAsc);
    }

    /**
     * Proxy method for testing the protected getObservatoryAssistantsListQuery method.
     *
     * @param bool $sortAsc Determines 'ASC' or 'DESC' sort.
     *
     * @return string The SQL query string.
     */
    public function getObservatoryAssistantsListQueryProxy(bool $sortAsc): string
    {
        return $this->getObservatoryAssistantsListQuery($sortAsc);
    }
}
