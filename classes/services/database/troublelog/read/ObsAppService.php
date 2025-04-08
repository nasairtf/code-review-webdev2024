<?php

declare(strict_types=1);

namespace App\services\database\troublelog\read;

use App\services\database\troublelog\TroublelogService as BaseService;

/**
 * ObsAppService handles read operations for ObsApp entities.
 *
 * @category Services
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class ObsAppService extends BaseService
{
    /**
     * Query methods that interface directly with DB Class
     */

    /**
     * Fetches semester proposal listing form data.
     *
     * @param int    $year      The year of the semester.
     * @param string $semester  The semester code.
     *
     * @return array The fetched semester proposal data.
     */
    public function fetchSemesterProposalListingFormData(int $year, string $semester): array
    {
        return $this->fetchDataWithQuery(
            $this->getProposalQuery('semester'),
            [$year, $semester],
            'is',
            'No proposals found for the selected semester.'
        );
    }

    /**
     * Fetches semester proposal listing page data.
     *
     * @param int    $year      The year of the semester.
     * @param string $semester  The semester code.
     *
     * @return array The fetched semester proposal data.
     */
    public function fetchSemesterProposalListingPageData(int $year, string $semester): array
    {
        return $this->fetchDataWithQuery(
            $this->getProposalPageQuery('semester'),
            [$year, $semester],
            'is',
            'No proposals found for the selected semester.'
        );
    }

    /**
     * Fetches proposal listing form data for a specific session.
     *
     * @param int $obsAppId The ID of the proposal session.
     *
     * @return array An array of proposal data for the specified session.
     */
    public function fetchProposalListingFormData(int $obsAppId): array
    {
        return $this->fetchDataWithQuery(
            $this->getProposalQuery('session'),
            [$obsAppId],
            'i',
            'No proposal found for the selected session.'
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
    public function fetchScheduleSemesterProgramList(int $year, string $semester): array
    {
        return $this->fetchDataWithQuery(
            $this->getScheduleSemesterProgramListQuery(),
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
            $this->getProposalQuery('program'),
            [$year, $semester, $program],
            'isi',
            'No proposal found for the given program.'
        );
    }

    /**
     * Helper methods to return the query strings
     */

    /**
     * Returns a query string for fetching program data for a specific semester.
     *
     * @return string The SQL query string.
     */
    protected function getScheduleSemesterProgramListQuery(): string
    {
        return "SELECT "
            .       "ProgramNumber AS programID, "
            .       "CONCAT(semesterYear,semesterCode) AS semesterID, "
            .       "InvLastName1 AS projectPI, "
            .       "CONCAT_WS(' ',InvFirstName1,InvLastName1) as projectMembers1, "
            .       "CONCAT_WS(' ',InvFirstName2,InvLastName2) as projectMembers2, "
            .       "CONCAT_WS(' ',InvFirstName3,InvLastName3) as projectMembers3, "
            .       "CONCAT_WS(' ',InvFirstName4,InvLastName4) as projectMembers4, "
            .       "CONCAT_WS(' ',InvFirstName5,InvLastName5) as projectMembers5, "
            .       "AdditionalCoInvs AS projectMembers6, "
            .       "PIEmail, "
            .       "PIName, "
            .       "NULL AS otherInfo "
            .   "FROM "
            .       "ObsApp "
            .   "WHERE "
            .       "semesterYear = ? AND "
            .       "semesterCode = ? AND "
            .       "ProgramNumber > 0 "
            .   "ORDER BY "
            .       "ProgramNumber ASC;";
    }

    /**
     * Returns a query string for fetching proposal listing or program data.
     *
     * @param string $condition The WHERE clause to use:
     *                          - 'program':  Filter by semesterYear, semesterCode, and ProgramNumber.
     *                          - 'semester': Filter by semesterYear and semesterCode.
     *                          - 'session':  Filter by ObsApp_id.
     * @return string The SQL query string.
     */
    protected function getProposalQuery(string $condition = 'session'): string
    {
        $fields = 'ObsApp_id, semesterYear, semesterCode, ProgramNumber, InvLastName1, code, creationDate';

        switch ($condition) {
            case 'program':
                $where = "WHERE semesterYear = ? AND semesterCode = ? AND ProgramNumber = ?";
                break;

            case 'semester':
                $where = "WHERE semesterYear = ? AND semesterCode = ?";
                break;

            case 'session':
                $where = "WHERE ObsApp_id = ?";
                break;
        }

        return "SELECT {$fields} FROM ObsApp {$where} ORDER BY creationDate ASC;";
    }

    /**
     * Returns a query string for fetching proposal listing or program data.
     *
     * @param string $condition The WHERE clause to use:
     *                          - 'program':  Filter by semesterYear, semesterCode, and ProgramNumber.
     *                          - 'semester': Filter by semesterYear and semesterCode.
     *                          - 'session':  Filter by ObsApp_id.
     * @return string The SQL query string.
     */
    protected function getProposalPageQuery(string $condition = 'session'): string
    {
        $fields = 'ObsApp_id, semesterYear, semesterCode, ProgramNumber, InvLastName1, code, creationDate, ' .
            'FilePathName, UploadFileName, ProposalFileName';

        switch ($condition) {
            case 'program':
                $where = "WHERE semesterYear = ? AND semesterCode = ? AND ProgramNumber = ?";
                break;

            case 'semester':
                $where = "WHERE semesterYear = ? AND semesterCode = ?";
                break;

            case 'session':
                $where = "WHERE ObsApp_id = ?";
                break;
        }

        return "SELECT {$fields} FROM ObsApp {$where} ORDER BY creationDate ASC;";
    }
}
