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
     * Fetches a list of programs for a specific semester.
     *
     * @param int    $year      The year of the semester.
     * @param string $semester  The semester code ('A' or 'B').
     *
     * @return array An array of program data for the specified semester.
     */
    public function fetchScheduleSemesterEngProgramList(string $semester): array
    {
        return $this->fetchDataWithQuery(
            $this->getScheduleSemesterEngProgramListQuery(),
            [$semester],
            's',
            'No proposals found for the selected semester.'
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

    /**
     * Returns a query string for fetching program data for a specific semester.
     *
     * @return string The SQL query string.
     */
    protected function getScheduleSemesterEngProgramListQuery(): string
    {
        return "SELECT "
            .       "programID, semesterID, projectPI, projectMembers, PIEmail, PIName, "
            .       "otherInfo, SciCategory, SciCategoryText, ApplicationTitle, Abstract "
            .   "FROM "
            .       "EngProgram "
            .   "WHERE "
            .       "semesterID = ? "
            .   "ORDER BY "
            .       "programID ASC;";
    }
}
