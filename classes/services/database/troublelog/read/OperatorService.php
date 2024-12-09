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
     *
     * fetchFullOperatorData    - retrieves the full operators list
     * fetchActiveOperatorData  - retrieves the active telescope operator list
     * fetchActiveAssistantData - retrieves the active observatory assistant list
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

    public function fetchOperatorData(): array
    {
        return $this->fetchDataWithQuery(
            $this->getTelescopeOperatorsListQuery(true),
            [],
            '',
            'No active operators found.'
        );
    }

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
     *
     * getAllOperatorsListQuery                        - return the full operators list select SQL string
     * getTelescopeOperatorsListQuery                  - return the telescope operator list select SQL string
     * getObservatoryAssistantsListQuery               - return the observatory assistant list select SQL string
     */

    private function getAllOperatorsListQuery(bool $sortAsc = true): string
    {
        // Filter: 'nightAttend' = -1 indicates non-active operators
        return "SELECT * "
            . "FROM Operator "
            . "ORDER BY lastName " . $this->getSortString($sortAsc) . ";";
    }

    private function getTelescopeOperatorsListQuery(bool $sortAsc = true): string
    {
        // Filter: 'nightAttend' = 0 indicates active operators
        return "SELECT * "
            . "FROM Operator "
            . "WHERE nightAttend = '0' "
            . "ORDER BY lastName " . $this->getSortString($sortAsc) . ";";
    }

    private function getObservatoryAssistantsListQuery(bool $sortAsc = true): string
    {
        // Filter: 'nightAttend' = 1 indicates active assistants
        return "SELECT * "
            . "FROM Operator "
            . "WHERE nightAttend = '1' "
            . "ORDER BY lastName " . $this->getSortString($sortAsc) . ";";
    }
}
