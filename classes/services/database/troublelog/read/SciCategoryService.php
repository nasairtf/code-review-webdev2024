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
     */

    /**
     * Fetches all scientific categories.
     *
     * @return array An array of scientific category data.
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

    /**
     * Fetches the ID of a scientific category based on its name.
     *
     * @param string $sciCatName The name of the scientific category.
     *
     * @return array An array containing the scientific category ID.
     */
    public function fetchScientificCategoryId(string $sciCatName): array
    {
        return $this->fetchDataWithQuery(
            $this->getScientificCategoryIdQuery(),
            [$sciCatName],
            's',
            'No scientific category found for the given name.'
        );
    }

    /**
     * Fetches the name of a scientific category based on its ID.
     *
     * @param int $sciCatId The ID of the scientific category.
     *
     * @return array An array containing the scientific category name.
     */
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
     */

    /**
     * Returns the SQL query string for fetching all scientific categories.
     *
     * @param bool $sortAsc Whether to sort the results in ascending order.
     *
     * @return string The SQL query string.
     */
    protected function getScientificCategoryListQuery(bool $sortAsc = true): string
    {
        /** NOTE: fix field names once table is refactored */
        return "SELECT SciCategory, SciCategoryText "
            . "FROM SciCategory "
            . "ORDER BY SciCategory " . $this->getSortString($sortAsc) . ";";
    }

    /**
     * Returns the SQL query string for fetching the ID of a scientific category by its name.
     *
     * @return string The SQL query string.
     */
    protected function getScientificCategoryIdQuery(): string
    {
        /** NOTE: fix field names once table is refactored */
        return "SELECT SciCategory "
            . "FROM SciCategory "
            . "WHERE SciCategoryText = ?";
    }

    /**
     * Returns the SQL query string for fetching the name of a scientific category by its ID.
     *
     * @return string The SQL query string.
     */
    protected function getScientificCategoryNameQuery(): string
    {
        /** NOTE: fix field names once table is refactored */
        return "SELECT SciCategoryText "
            . "FROM SciCategory "
            . "WHERE SciCategory = ?";
    }
}
