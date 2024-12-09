<?php

declare(strict_types=1);

namespace App\services\database\troublelog\read;

use App\services\database\troublelog\TroublelogService as BaseService;

/**
 * SciCategoryService handles read operations for SciCategory entities.
 *
 * @category Services
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class SciCategoryService extends BaseService
{
    /**
     * Query methods that fetch scientific category data
     *
     * fetchScientificCategoryData - retrieves the scientific category list
     * fetchScientificCategoryId   - retrieves the scientific category id
     * fetchScientificCategoryName - retrieves the scientific category name
     */

    public function fetchScientificCategoryData(): array
    {
        return $this->fetchDataWithQuery(
            $this->getScientificCategoryListQuery(true),
            [],
            '',
            'No scientific categories found.'
        );
    }

    public function fetchScientificCategoryId(string $sciCatName): array
    {
        return $this->fetchDataWithQuery(
            $this->getScientificCategoryIdQuery(),
            [$sciCatName],
            's',
            'No scientific category found for the given name.'
        );
    }

    public function fetchScientificCategoryName(int $sciCatId): array
    {
        return $this->fetchDataWithQuery(
            $this->getScientificCategoryNameQuery(),
            [$sciCatId],
            'i',
            'No scientific category found for the given ID.'
        );
    }

    /**
     * Helper methods to return the query strings
     *
     * getScientificCategoryListQuery                  - return the scientific category list select SQL string
     * getScientificCategoryIDQuery                    - return a scientific category id select SQL string
     * getScientificCategoryNameQuery                  - return a scientific category name select SQL string
     */

    private function getScientificCategoryListQuery(bool $sortAsc = true): string
    {
        /** NOTE: fix field names once table is refactored */
        return "SELECT SciCategory, SciCategoryText "
            . "FROM SciCategory "
            . "ORDER BY SciCategory " . $this->getSortString($sortAsc) . ";";
    }

    private function getScientificCategoryIdQuery(): string
    {
        /** NOTE: fix field names once table is refactored */
        return "SELECT SciCategory "
            . "FROM SciCategory "
            . "WHERE SciCategoryText = ?";
    }

    private function getScientificCategoryNameQuery(): string
    {
        /** NOTE: fix field names once table is refactored */
        return "SELECT SciCategoryText "
            . "FROM SciCategory "
            . "WHERE SciCategory = ?";
    }
}
