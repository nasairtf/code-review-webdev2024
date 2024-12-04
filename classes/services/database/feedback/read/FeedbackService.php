<?php

namespace App\services\database\feedback\read;

use App\services\database\feedback\FeedbackService as BaseService;

/**
 * FeedbackService handles read operations for Feedback entities.
 *
 * @category Services
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class FeedbackService extends BaseService
{
    /**
     * Query methods that interface directly with DB Class
     *
     * fetchSemesterData - retrieves the semester data
     * fetchProposalData - retrieves the proposal data
     */

    public function fetchSemesterProposalListingFormData(int $year, string $semester): array
    {
        return $this->fetchDataWithQuery(
            $this->getProposalListingFormDataQuery(true),
            [$year, $semester],
            'is',
            'No proposals found for the selected semester.'
        );
    }

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
    public function fetchProposalProgramData(int $year, string $semester, int $program): array
    {
        return $this->fetchDataWithQuery(
            $this->getProposalProgramDataQuery(),
            [$year, $semester, $program],
            'isi',
            'No proposal found for the given program.'
        );
    }

    /**
     * Helper methods to return the query strings
     *
     * getProposalListingFormDataQuery - return proposal data select SQL string
     */

    private function getProposalListingFormDataQuery(bool $semester = true): string
    {
        $query = [];
        $query[] = "SELECT ObsApp_id, semesterYear, semesterCode, ProgramNumber, InvLastName1, code, creationDate";
        $query[] = "FROM ObsApp";
        $query[] = $semester
            ? "WHERE semesterYear = ? AND semesterCode = ?"
            : "WHERE ObsApp_id = ?";
        $query[] = "ORDER BY creationDate ASC;";
        return implode(' ', $query);
    }

    /**
     * Returns the SQL query string for fetching proposal program data.
     *
     * This query is used to match records in the ObsApp table based on the
     * specified year, semester, and program number.
     *
     * @return string The SQL query string.
     */
    private function getProposalProgramDataQuery(): string
    {
        $fields = 'ObsApp_id, semesterYear, semesterCode, ProgramNumber, InvLastName1, code, creationDate';
        return "SELECT {$fields} FROM ObsApp WHERE semesterYear = ? AND semesterCode = ? AND ProgramNumber = ?;";
    }
}
