<?php

declare(strict_types=1);

namespace Tests\classes\services\database\troublelog\read;

use App\services\database\troublelog\read\SciCategoryService as BaseService;

class TestSciCategoryService extends BaseService
{
    /**
     * Proxy method for testing the protected getScientificCategoryListQuery method.
     *
     * @param bool $sortAsc Determines 'ASC' or 'DESC' sort.
     *
     * @return string The SQL query string.
     */
    public function getScientificCategoryListQueryProxy(bool $sortAsc): string
    {
        return $this->getScientificCategoryListQuery($sortAsc);
    }

    /**
     * Proxy method for testing the protected getScientificCategoryIdQuery method.
     *
     * @return string The params types string.
     */
    public function getScientificCategoryIdQueryProxy(): string
    {
        return $this->getScientificCategoryIdQuery();
    }

    /**
     * Proxy method for testing the protected getScientificCategoryNameQuery method.
     *
     * @return string The params types string.
     */
    public function getScientificCategoryNameQueryProxy(): string
    {
        return $this->getScientificCategoryNameQuery();
    }
}
