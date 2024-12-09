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
     *
     * fetchFullSupportAstronomerData   - retrieves the full support astronomer list
     * fetchActiveSupportAstronomerData - retrieves the active support astronomer list
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
     *
     * getAllSupportAstronomersListQuery               - return the full support astronomer list select SQL string
     * getSupportAstronomerListQuery                   - return the support astronomer list select SQL string
     */

    private function getAllSupportAstronomersListQuery(bool $sortAsc = true): string
    {
        // Filter: 'status' = 0 indicates non-active support astronomers
        return "SELECT * "
            . "FROM SupportAstronomer "
            . "ORDER BY lastName " . $this->getSortString($sortAsc) . ";";
    }

    private function getSupportAstronomerListQuery(bool $sortAsc = true): string
    {
        // Filter: 'status' = 1 indicates active support astronomers
        return "SELECT * "
            . "FROM SupportAstronomer "
            . "WHERE status = '1' "
            . "ORDER BY lastName " . $this->getSortString($sortAsc) . ";";
    }
}
