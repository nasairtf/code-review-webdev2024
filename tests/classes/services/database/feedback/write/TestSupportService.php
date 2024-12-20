<?php

declare(strict_types=1);

namespace Tests\classes\services\database\feedback\write;

use App\services\database\feedback\write\SupportService as BaseService;

class TestSupportService extends BaseService
{
    /**
     * Proxy method for testing the protected getSupportAstronomerInsertQuery method.
     *
     * @return string The SQL query string.
     */
    public function getSupportAstronomerInsertQueryProxy(): string
    {
        return $this->getSupportAstronomerInsertQuery();
    }
}
