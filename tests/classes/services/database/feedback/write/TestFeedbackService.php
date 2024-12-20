<?php

declare(strict_types=1);

namespace Tests\classes\services\database\feedback\write;

use App\services\database\feedback\write\FeedbackService as BaseService;

class TestFeedbackService extends BaseService
{
    /**
     * Proxy method for testing the protected getFeedbackInsertQuery method.
     *
     * @return string The SQL query string.
     */
    public function getFeedbackInsertQueryProxy(): string
    {
        return $this->getFeedbackInsertQuery();
    }

    /**
     * Proxy method for testing the protected getFeedbackInsertParams method.
     *
     * @return string The params array.
     */
    public function getFeedbackInsertParamsProxy(array $data): array
    {
        return $this->getFeedbackInsertParams($data);
    }

    /**
     * Proxy method for testing the protected getFeedbackInsertTypes method.
     *
     * @return string The params types string.
     */
    public function getFeedbackInsertTypesProxy(): string
    {
        return $this->getFeedbackInsertTypes();
    }
}
