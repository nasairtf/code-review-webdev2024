<?php

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
     * Query methods that fetch guest acct data
     *
     * fetchGuestAcctData - retrieves a guest account's data
     */

    public function fetchGuestAccountData(string $semester): array
    {
        return $this->fetchDataWithQuery(
            $this->getSingleProgramSessionQuery(),
            [$semester],
            's',
            'No programs found.'
        );
    }

    public function fetchProgramSessionData(string $program, string $session): array
    {
        return $this->fetchDataWithQuery(
            $this->getSingleProgramSessionQuery(),
            [$program, $session . '%'],
            'ss',
            'No programs found.'
        );
    }

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
     *
     * getProgramInfoListQuery - return a semester's program list select SQL string
     */

    private function getSingleProgramSessionQuery(): string
    {
        //return "SELECT username as program, defaultpwd as session FROM GuestAccts WHERE username = ? AND defaultpwd = ?;";
        return "SELECT username as program, defaultpwd as session FROM GuestAccts WHERE username = ? AND defaultpwd LIKE BINARY ?;";
    }

    private function getValidateProgramQuery(): string
    {
        //return "SELECT COUNT(*) AS count FROM GuestAccts WHERE username = ? AND defaultpwd = ?;";
        return "SELECT COUNT(*) AS count FROM GuestAccts WHERE username = ? AND defaultpwd LIKE BINARY ?;";
    }
}
