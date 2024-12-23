<?php

declare(strict_types=1);

namespace App\services\database\troublelog\read;

use App\services\database\troublelog\TroublelogService as BaseService;

/**
 * OperatorService handles read operations for Operator entities.
 *
 * @category Services
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class OperatorService extends BaseService
{
    /**
     * Query methods that fetch operator data
     */

    /**
     * Fetches the full list of operators.
     *
     * @return array An array of operator data.
     */
    public function fetchFullOperatorData(): array
    {
        return $this->fetchDataWithQuery(
            $this->getAllOperatorsListQuery(true),
            [],
            '',
            'No operators found.'
        );
    }

    /**
     * Fetches active telescope operator data.
     *
     * @return array An array of active telescope operator data.
     */
    public function fetchOperatorData(): array
    {
        return $this->fetchDataWithQuery(
            $this->getTelescopeOperatorsListQuery(true),
            [],
            '',
            'No active operators found.'
        );
    }

    /**
     * Fetches active observatory assistant data.
     *
     * @return array An array of active observatory assistant data.
     */
    public function fetchAssistantData(): array
    {
        return $this->fetchDataWithQuery(
            $this->getObservatoryAssistantsListQuery(true),
            [],
            '',
            'No active assistants found.'
        );
    }

    /**
     * Helper methods to return the query strings
     */

    /**
     * Returns the SQL query string for fetching the full list of operators.
     *
     * @param bool $sortAsc Whether to sort the results in ascending order.
     *
     * @return string The SQL query string.
     */
    protected function getAllOperatorsListQuery(bool $sortAsc = true): string
    {
        // Filter: 'nightAttend' = -1 indicates non-active operators
        return "SELECT * "
            . "FROM Operator "
            . "ORDER BY lastName " . $this->getSortString($sortAsc) . ";";
    }

    /**
     * Returns the SQL query string for fetching active telescope operators.
     *
     * @param bool $sortAsc Whether to sort the results in ascending order.
     *
     * @return string The SQL query string.
     */
    protected function getTelescopeOperatorsListQuery(bool $sortAsc = true): string
    {
        // Filter: 'nightAttend' = 0 indicates active operators
        return "SELECT * "
            . "FROM Operator "
            . "WHERE nightAttend = '0' "
            . "ORDER BY lastName " . $this->getSortString($sortAsc) . ";";
    }

    /**
     * Returns the SQL query string for fetching active observatory assistants.
     *
     * @param bool $sortAsc Whether to sort the results in ascending order.
     *
     * @return string The SQL query string.
     */
    protected function getObservatoryAssistantsListQuery(bool $sortAsc = true): string
    {
        // Filter: 'nightAttend' = 1 indicates active assistants
        return "SELECT * "
            . "FROM Operator "
            . "WHERE nightAttend = '1' "
            . "ORDER BY lastName " . $this->getSortString($sortAsc) . ";";
    }
}
