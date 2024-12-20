<?php

declare(strict_types=1);

namespace Tests\classes\services\database\feedback\write;

use App\services\database\feedback\write\OperatorService as BaseService;

class TestOperatorService extends BaseService
{
    /**
     * Proxy method for testing the protected getOperatorInsertQuery method.
     *
     * @return string The SQL query string.
     */
    public function getOperatorInsertQueryProxy(): string
    {
        return $this->getOperatorInsertQuery();
    }
}
