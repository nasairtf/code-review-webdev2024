<?php

declare(strict_types=1);

namespace App\services\database\troublelog\read;

use App\services\database\troublelog\TroublelogService as BaseService;

/**
 * ProgramService handles read operations for Program entities.
 *
 * @category Services
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class ProgramService extends BaseService
{
    /**
     * Query methods that fetch observing program data
     */

    /**
     * Fetches program data for a given semester.
     *
     * @param string $semester  The semester identifier.
     *
     * @return array An array of program data.
     */
    public function fetchSemesterProgramData(string $semester): array
    {
        return $this->fetchDataWithQuery(
            $this->getProgramInfoListQuery(true),
            [$semester],
            's',
            'No programs found.'
        );
    }

    /**
     * Helper methods to return the query strings
     */

    /**
     * Returns a query for program information.
     *
     * @param bool $sortAsc Whether to sort in ascending order.
     *
     * @return string The SQL query string.
     */
    protected function getProgramInfoListQuery(bool $sortAsc = true): string
    {
        // Filter: 'programID' < 900 indicates non-engineering programs
        return "SELECT programID, projectPI "
            . "FROM Program "
            . "WHERE semesterID = ? AND programID > 000 AND programID < 900 "
            . "ORDER BY programID " . $this->getSortString($sortAsc) . ";";
    }
}
