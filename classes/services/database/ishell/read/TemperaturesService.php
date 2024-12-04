<?php

namespace App\services\database\ishell\read;

use App\services\database\ishell\IshellService as BaseService;

/**
 * TemperatureService handles read operations for Temperature entities.
 *
 * @category Services
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class TemperaturesService extends BaseService
{
    /**
     * Fetch temperature data with optional filters.
     *
     * @param string      $sensor_id   The sensor ID.
     * @param string|null $system      The system identifier (optional).
     * @param int|null    $timestamp   Timestamp limit in seconds (optional).
     * @param bool        $limitToOne  Whether to limit the result to the most recent record.
     * @param string      $order       The order of results by timestamp ('ASC' for earliest or 'DESC' for latest).
     *
     * @return array The fetched temperature data.
     */
    public function fetchTemperatureData(
        string $sensor_id,
        ?string $system = null,
        ?int $timestamp = null,
        bool $limitToOne = false,
        bool $sortAsc = true
    ): array {
        // Build the query dynamically
        $query = "SELECT * FROM temperatures WHERE sensor_id = ?";
        $params = [$sensor_id];
        $types = "s";

        // Add optional system filter
        if ($system !== null) {
            $query .= " AND system = ?";
            $params[] = $system;
            $types .= "s";
        }

        // Add optional timestamp filter
        if ($timestamp !== null) {
            $query .= " AND timestamp > FROM_UNIXTIME(UNIX_TIMESTAMP() - ?)";
            $params[] = $timestamp;
            $types .= "i";
        }

        // Determine sort order using helper method
        $sortOrder = $this->getSortString($sortAsc);
        $query .= " ORDER BY timestamp $sortOrder";

        // Add limit if only the most recent record is needed
        if ($limitToOne) {
            $query .= " LIMIT 1";
        }

        // Fetch and return data
        return $this->fetchDataWithQuery($query, $params, $types, 'No temperatures found.');
    }
}
