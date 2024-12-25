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
            $this->getSupportAstronomersQuery(true, false),
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
            $this->getSupportAstronomersQuery(true, true),
            [],
            '',
            'No support astronomers found.'
        );
    }

    /**
     * Helper methods to return the query strings
     */

    /**
     * Returns the SQL query string for fetching support astronomers.
     *
     * This method can retrieve either all support astronomers or only active ones,
     * based on the `$status` parameter. The results are sorted by the last name.
     *
     * @param bool $sortAsc    Whether to sort the results in ascending order.
     * @param bool $status     Whether to include only active support astronomers.
     *                         True for active astronomers only, false for all.
     *
     * @return string The SQL query string.
     */
    protected function getSupportAstronomersQuery(bool $sortAsc = true, bool $status = true): string
    {
        $where = $status ? "WHERE status = '1' " : '';
        return "SELECT * "
            . "FROM SupportAstronomer "
            . $where
            . "ORDER BY lastName " . $this->getSortString($sortAsc) . ";";
    }
}
