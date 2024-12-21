<?php

declare(strict_types=1);

namespace App\services\database\troublelog\read;

use App\services\database\troublelog\TroublelogService as BaseService;

/**
 * EngProgramService handles read operations for ObsApp entities.
 *
 * @category Services
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class EngProgramService extends BaseService
{
    /**
     * Fetches data for a specific proposal program based on year, semester, and program number.
     *
     * This method queries the ObsApp database table to retrieve proposal data for
     * a given combination of year, semester, and program number, returning an
     * associative array of relevant proposal information.
     *
     * @param int    $year      The four-digit year (e.g., 2024).
     * @param string $semester  The semester code, either 'A' or 'B'.
     * @param int    $program   The program number, an integer without leading zeros.
     *
     * @return array            The proposal data for the specified program.
     *                           Returns an empty array if no matching proposal is found.
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
     * Returns the SQL query string for fetching proposal program data.
     *
     * This query is used to match records in the ObsApp table based on the
     * specified year, semester, and program number.
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
