<?php

declare(strict_types=1);

namespace App\services\database\troublelog\read;

use App\services\database\troublelog\TroublelogService as BaseService;

/**
 * EngProgramService handles read operations for EngProgram entities.
 *
 * @category Services
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class EngProgramService extends BaseService
{
    /**
     * Query methods that fetch engineering program data
     */

    /**
     * Fetches data for a specific proposal program based on semester and program number.
     *
     * @param string $semester  The semester code, either 'A' or 'B'.
     * @param int    $program   The program number.
     *
     * @return array The proposal data or an empty array if no matches are found.
     */
    public function fetchProposalEngProgramData(string $semester, int $program): array
    {
        return $this->fetchDataWithQuery(
            $this->getProposalEngProgramDataQuery(),
            [$semester, $program],
            'si',
            'No record found for the given program.'
        );
    }

    /**
     * Helper methods to return the query strings
     */

    /**
     * Returns the SQL query string for fetching proposal program data.
     *
     * @return string The SQL query string.
     */
    protected function getProposalEngProgramDataQuery(): string
    {
        $fields = 'semesterID, programID, projectPI';
        return "SELECT {$fields} "
            . "FROM EngProgram "
            . "WHERE semesterID = ? AND programID = ?;";
    }
}
