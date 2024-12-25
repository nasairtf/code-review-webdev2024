<?php

declare(strict_types=1);

namespace App\services\database\troublelog\read;

use App\services\database\troublelog\TroublelogService as BaseService;

/**
 * GuestAcctsService handles read operations for GuestAccts entities.
 *
 * @category Services
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class GuestAcctsService extends BaseService
{
    /**
     * Query methods that fetch guest account data
     */

    /**
     * Fetches data for guest accounts by semester.
     *
     * @param string $semester  The semester identifier.
     *
     * @return array An array of guest account data.
     */
    public function fetchGuestAccountData(string $semester): array
    {
        return $this->fetchDataWithQuery(
            $this->getSemesterProgramsQuery(),
            [$semester . '%'],
            's',
            'No programs found.'
        );
    }

    /**
     * Fetches program session data.
     *
     * @param string $program The program identifier.
     * @param string $session The session identifier (partial match).
     *
     * @return array An array of program session data.
     */
    public function fetchProgramSessionData(string $program, string $session): array
    {
        return $this->fetchDataWithQuery(
            $this->getSingleProgramSessionQuery(),
            [$program, $session . '%'],
            'ss',
            'No programs found.'
        );
    }

    /**
     * Validates the existence of a program session.
     *
     * @param string $program The program identifier.
     * @param string $session The session identifier (partial match).
     *
     * @return array Validation results as an associative array.
     */
    public function fetchProgramValidation(string $program, string $session): array
    {
        return $this->executeSelectQuery(
            $this->getValidateProgramQuery(),
            [$program, $session . '%'],
            'ss'
        );
    }

    /**
     * Helper methods to return the query strings
     */

    /**
     * Returns a query for guest accounts by semester.
     *
     * @return string The SQL query string.
     */
    protected function getSemesterProgramsQuery(): string
    {
        return "SELECT username as program, defaultpwd as session "
            . "FROM GuestAccts "
            . "WHERE username LIKE BINARY ?;";
    }

    /**
     * Returns a query for a single program session.
     *
     * @return string The SQL query string.
     */
    protected function getSingleProgramSessionQuery(): string
    {
        return "SELECT username as program, defaultpwd as session "
            . "FROM GuestAccts "
            . "WHERE username = ? AND defaultpwd LIKE BINARY ?;";
    }

    /**
     * Returns a query for validating a program session.
     *
     * @return string The SQL query string.
     */
    protected function getValidateProgramQuery(): string
    {
        return "SELECT COUNT(*) AS count "
            . "FROM GuestAccts "
            . "WHERE username = ? AND defaultpwd LIKE BINARY ?;";
    }
}
