<?php

declare(strict_types=1);

namespace Tests\classes\services\database\feedback\write;

use App\services\database\feedback\write\InstrumentService as BaseService;

class TestInstrumentService extends BaseService
{
    /**
     * Proxy method for testing the protected getInstrumentInsertQuery method.
     *
     * @return string The SQL query string.
     */
    public function getInstrumentInsertQueryProxy(): string
    {
        return $this->getInstrumentInsertQuery();
    }
}
