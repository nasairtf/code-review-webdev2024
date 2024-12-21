<?php

declare(strict_types=1);

namespace Tests\classes\services\database\troublelog\read;

use App\services\database\troublelog\read\HardwareService as BaseService;

class TestHardwareService extends BaseService
{
    /**
     * Proxy method for testing the protected getAllInstrumentsListQuery method.
     *
     * @param bool $sortAsc Determines 'ASC' or 'DESC' sort.
     *
     * @return string The SQL query string.
     */
    public function getAllInstrumentsListQueryProxy(bool $sortAsc): string
    {
        return $this->getAllInstrumentsListQuery($sortAsc);
    }

    /**
     * Proxy method for testing the protected getActiveSecondaryInstrumentsListQuery method.
     *
     * @param bool $sortAsc Determines 'ASC' or 'DESC' sort.
     *
     * @return string The SQL query string.
     */
    public function getActiveSecondaryInstrumentsListQueryProxy(bool $sortAsc): string
    {
        return $this->getActiveSecondaryInstrumentsListQuery($sortAsc);
    }

    /**
     * Proxy method for testing the protected getAllActiveFacilityInstrumentsListByIndexQuery method.
     *
     * @param bool $sortAsc Determines 'ASC' or 'DESC' sort.
     *
     * @return string The SQL query string.
     */
    public function getAllActiveFacilityInstrumentsListByIndexQueryProxy(bool $sortAsc): string
    {
        return $this->getAllActiveFacilityInstrumentsListByIndexQuery($sortAsc);
    }

    /**
     * Proxy method for testing the protected getAllActiveInstrumentsListByIndexQuery method.
     *
     * @param bool $sortAsc Determines 'ASC' or 'DESC' sort.
     *
     * @return string The SQL query string.
     */
    public function getAllActiveInstrumentsListByIndexQueryProxy(bool $sortAsc): string
    {
        return $this->getAllActiveInstrumentsListByIndexQuery($sortAsc);
    }

    /**
     * Proxy method for testing the protected getAllActiveInstrumentsListByNameQuery method.
     *
     * @param bool $sortAsc Determines 'ASC' or 'DESC' sort.
     *
     * @return string The SQL query string.
     */
    public function getAllActiveInstrumentsListByNameQueryProxy(bool $sortAsc): string
    {
        return $this->getAllActiveInstrumentsListByNameQuery($sortAsc);
    }

    /**
     * Proxy method for testing the protected getAllNotObsoleteInstrumentsListByNameQuery method.
     *
     * @param bool $sortAsc Determines 'ASC' or 'DESC' sort.
     *
     * @return string The SQL query string.
     */
    public function getAllNotObsoleteInstrumentsListByNameQueryProxy(bool $sortAsc): string
    {
        return $this->getAllNotObsoleteInstrumentsListByNameQuery($sortAsc);
    }

    /**
     * Proxy method for testing the protected getAllActiveVisitorInstrumentListQuery method.
     *
     * @param bool $sortAsc Determines 'ASC' or 'DESC' sort.
     *
     * @return string The SQL query string.
     */
    public function getAllActiveVisitorInstrumentListQueryProxy(bool $sortAsc): string
    {
        return $this->getAllActiveVisitorInstrumentListQuery($sortAsc);
    }
}
