<?php

declare(strict_types=1);

namespace Tests\classes\services\database\troublelog\read;

use App\services\database\troublelog\read\ProgramService as BaseService;

class TestProgramService extends BaseService
{
    /**
     * Proxy method for testing the protected getProgramInfoListQuery method.
     *
     * @param bool $sortAsc Determines 'ASC' or 'DESC' sort.
     *
     * @return string The SQL query string.
     */
    public function getProgramInfoListQueryProxy(bool $sortAsc): string
    {
        return $this->getProgramInfoListQuery($sortAsc);
    }
}
