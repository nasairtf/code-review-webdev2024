<?php

declare(strict_types=1);

namespace App\services\database;

use App\core\common\Debug;

class DbQueryUtility
{
    /**
     *
     * HANDLES READ QUERIES AND RESULTS
     *
     */

    /**
     * Executes a SELECT query with optional parameter binding and returns the result set.
     *
     * @param Debug  $debug        Debug instance.
     * @param DB     $db           Database instance.
     * @param string $sql          SQL query string.
     * @param array  $params       Parameters to bind to the query.
     * @param string $types        Parameter types (e.g., 's' for string, 'i' for integer).
     * @param int    $resultType   The type of result array (e.g., MYSQLI_ASSOC).
     *
     * @return array               Results array.
     */
    public static function executeSelectQueryWithDebug(
        Debug $debug,
        DB $db,
        string $sql,
        array $params,
        string $types,
        int $resultType = MYSQLI_ASSOC
    ): array {
        $debug->debug("SQL: {$sql}");
        $debug->debugVariable($params, "Params");
        $results = $db->executeQuery($sql, $params, $types, $resultType);
        $debug->debugVariable($results, "Query [SELECT] Results");
        return $results;
    }

    /**
     * Ensures that query results are not empty.
     *
     * @param Debug  $debug        Debug instance.
     * @param array  $data         The data returned from the query.
     * @param string $errorMessage Error message to throw if data is empty.
     *
     * @throws Exception
     */
    public static function ensureQueryResultsNotEmpty(
        Debug $debug,
        array $data,
        string $errorMessage
    ): void {
        if (empty($data)) {
            $debug->fail($errorMessage);
        }
    }

    /**
     *
     * HANDLES WRITE QUERIES AND RESULTS
     *
     */

    /**
     * Executes an INSERT, UPDATE, or DELETE query with optional parameter binding.
     *
     * @param Debug  $debug        Debug instance.
     * @param DB     $db           Database instance.
     * @param string $sql          SQL query string.
     * @param array  $params       Parameters to bind to the query.
     * @param string $types        Parameter types (e.g., 's' for string, 'i' for integer).
     *
     * @return int                 Number of affected rows.
     */
    public static function executeUpdateQueryWithDebug(
        Debug $debug,
        DB $db,
        string $sql,
        array $params = [],
        string $types = ''
    ): int {
        $debug->debug("SQL: {$sql}");
        $debug->debugVariable($params, "Params");
        $rows = $db->executeQuery($sql, $params, $types);
        $debug->debugVariable($rows, "Query [INSERT|UPDATE|DELETE] Rows Affected");
        return $rows;
    }

    /**
     * Executes a query with or without parameter binding.
     *
     * @param Debug  $debug        Debug instance.
     * @param DB     $db           Database instance.
     * @param string $sql          SQL query string.
     * @param array  $params       Parameters to bind (optional).
     * @param string $types        Parameter types (optional).
     *
     * @return int                 Number of affected rows.
     */
    public static function executeQueryWithDebug(
        Debug $debug,
        DB $db,
        string $sql,
        array $params = [],
        string $types = ''
    ): int {
        if (!empty($params)) {
            // Use parameterized query execution
            $debug->debug("Executing Param Bound SQL: {$sql}");
            $debug->debugVariable($params, "Params");
            return $db->executeQuery($sql, $params, $types);
        } else {
            // Use raw query execution
            $debug->debug("Executing Raw SQL: {$sql}");
            return $db->executeRawQuery($sql);
        }
    }

    /**
     * Ensures that query rows affected are valid.
     *
     * @param Debug  $debug        Debug instance.
     * @param int    $affectedRows The row count returned from the query.
     * @param int    $expectedRows The row count expected from the query.
     * @param string $errorMessage Error message to throw if row count is invalid.
     *
     * @throws Exception
     */
    public static function ensureRowUpdateResult(
        Debug $debug,
        int $result,
        int $expected,
        string $errorMessage
    ): void {
        if ($result === 0) {
            $debug->fail($errorMessage . " No rows were affected.");
        }
        if ($result !== $expected) {
            $debug->fail($errorMessage . " Unexpected number of affected rows.");
        }
    }

    /**
     * Executes a raw SQL query for DELETE or LOAD DATA INFILE operations.
     *
     * @param Debug  $debug        Debug instance.
     * @param DB     $db           Database instance.
     * @param string $sql          SQL query string.
     *
     * @return int                 Number of affected rows.
     */
    public static function executeRawQueryWithDebug(
        Debug $debug,
        DB $db,
        string $sql
    ): int {
        // Debug the raw SQL
        $debug->debug("Raw SQL: {$sql}");

        // Execute the query directly
        $result = $db->executeRawQuery($sql);

        // Debug the result
        $affectedRows = $result['affected_rows'] ?? 0;
        $debug->debugVariable($affectedRows, "Query [RAW SQL] Rows Affected");

        return $affectedRows;
    }

    /**
     *
     * HELPER METHODS
     *
     */

    /**
     * Helper for sorting SQL queries (ASC or DESC).
     */
    public static function getSortString(
        bool $sortAsc = true
    ): string {
        return $sortAsc ? 'ASC' : 'DESC';
    }
}
