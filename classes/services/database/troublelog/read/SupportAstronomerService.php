<?php

declare(strict_types=1);

namespace App\services\database\troublelog\read;

use App\services\database\troublelog\TroublelogService as BaseService;

/**
 * SupportAstronomerService handles read operations for SupportAstronomer entities.
 *
 * @category Services
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class SupportAstronomerService extends BaseService
{
    /**
     * Query methods that fetch support astronomer data
     */

    /**
     * Fetches all support astronomers.
     *
     * @return array An array of support astronomer data.
     */
    public function fetchFullSupportAstronomerData(): array
    {
        return $this->fetchDataWithQuery(
            $this->getAllSupportAstronomersListQuery(true),
            [],
            '',
            'No support astronomers found.'
        );
    }

    /**
     * Fetches active support astronomers.
     *
     * @return array An array of support astronomer data.
     */
    public function fetchSupportAstronomerData(): array
    {
        return $this->fetchDataWithQuery(
            $this->getSupportAstronomerListQuery(true),
            [],
            '',
            'No support astronomers found.'
        );
    }

    /**
     * Helper methods to return the query strings
     */

    /**
     * Returns the SQL query string for fetching all support astronomers.
     *
     * This query retrieves all entries in the `SupportAstronomer` table, sorted by the last name.
     *
     * @param bool $sortAsc Whether to sort the results in ascending order.
     *
     * @return string The SQL query string.
     */
    protected function getAllSupportAstronomersListQuery(bool $sortAsc = true): string
    {
        // Filter: 'status' = 0 indicates non-active support astronomers
        return "SELECT * "
            . "FROM SupportAstronomer "
            . "ORDER BY lastName " . $this->getSortString($sortAsc) . ";";
    }

    /**
     * Returns the SQL query string for fetching active support astronomers.
     *
     * This query retrieves entries in the `SupportAstronomer` table where the `status` field
     * indicates active astronomers.
     *
     * @param bool $sortAsc Whether to sort the results in ascending order.
     *
     * @return string The SQL query string.
     */
    protected function getSupportAstronomerListQuery(bool $sortAsc = true): string
    {
        // Filter: 'status' = 1 indicates active support astronomers
        return "SELECT * "
            . "FROM SupportAstronomer "
            . "WHERE status = '1' "
            . "ORDER BY lastName " . $this->getSortString($sortAsc) . ";";
    }
}
