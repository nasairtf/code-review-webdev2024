<?php

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
     *
     * fetchSemesterProgramData - retrieves a semester's program list
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
     *
     * getProgramInfoListQuery - return a semester's program list select SQL string
     */

    private function getProgramInfoListQuery(bool $sortAsc = true): string
    {
        // Filter: 'programID' < 900 indicates non-engineering programs
        return "SELECT programID, projectPI FROM Program WHERE semesterID = ? AND programID > 000 AND programID < 900 ORDER BY programID " . $this->getSortString($sortAsc) . ";";
    }
}
