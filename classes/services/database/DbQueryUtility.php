<?php

declare(strict_types=1);

namespace App\services\database;

use App\core\common\CustomDebug;

/**
 * Utility class for executing and managing database queries.
 *
 * This class provides static methods for executing SELECT, INSERT, UPDATE, and DELETE
 * queries with optional parameter binding and debugging capabilities. It also includes
 * helper methods for validating query results and managing SQL sort directions.
 *
 * @package App\services\database
 */
class DbQueryUtility
{
    /**
     *
     * HANDLES READ QUERIES AND RESULTS
     *
     */

    /**
     * Executes a SELECT query with optional parameter binding and debugging.
     *
     * Logs the SQL query and parameters, executes the query using the provided database
     * instance, and returns the result set. Useful for debugging read operations.
     *
     * @param CustomDebug  $debug      Instance of the CustomDebug class for logging and debugging.
     * @param DBConnection $db         Instance of the DBConnection class for executing queries.
     * @param string       $sql        SQL query string to execute.
     * @param array        $params     Parameters to bind to the query (optional).
     * @param string       $types      Parameter types (e.g., 's' for string, 'i' for integer).
     * @param int          $resultType Type of result array (e.g., MYSQLI_ASSOC).
     *
     * @return array                   Array of query results.
     */
    public static function executeSelectQueryWithDebug(
        CustomDebug $debug,
        DBConnection $db,
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
     * Validates that a query's result set is not empty.
     *
     * Throws an exception if the result set is empty, ensuring the caller can handle
     * cases where no data is returned from a query.
     *
     * @param CustomDebug $debug        Instance of the CustomDebug class for error reporting.
     * @param array       $data         The result set returned from the query.
     * @param string      $errorMessage Error message to log and throw if the result set is empty.
     *
     * @throws \DatabaseException       If the result set is empty.
     */
    public static function ensureQueryResultsNotEmpty(
        CustomDebug $debug,
        array $data,
        string $errorMessage
    ): void {
        if (empty($data)) {
            $debug->failDatabase($errorMessage);
        }
    }

    /**
     *
     * HANDLES WRITE QUERIES AND RESULTS
     *
     */

    /**
     * Executes an INSERT, UPDATE, or DELETE query with optional parameter binding and debugging.
     *
     * Logs the SQL query and parameters, executes the query using the provided database
     * instance, and returns the number of affected rows.
     *
     * @param CustomDebug  $debug        Instance of the CustomDebug class for logging and debugging.
     * @param DBConnection $db           Instance of the DBConnection class for executing queries.
     * @param string       $sql          SQL query string to execute.
     * @param array        $params       Parameters to bind to the query (optional).
     * @param string       $types        Parameter types (e.g., 's' for string, 'i' for integer).
     *
     * @return int                       Number of rows affected by the query.
     */
    public static function executeUpdateQueryWithDebug(
        CustomDebug $debug,
        DBConnection $db,
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
     * Executes a query with optional parameter binding and debugging.
     *
     * If parameters are provided, executes a parameterized query. Otherwise, executes
     * a raw SQL query. Logs the query type and returns the number of affected rows.
     *
     * @param CustomDebug  $debug  Instance of the CustomDebug class for logging and debugging.
     * @param DBConnection $db     Instance of the DBConnection class for executing queries.
     * @param string       $sql    SQL query string to execute.
     * @param array        $params Parameters to bind to the query (optional).
     * @param string       $types  Parameter types (optional).
     *
     * @return int                 Number of rows affected by the query.
     */
    public static function executeQueryWithDebug(
        CustomDebug $debug,
        DBConnection $db,
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
     * Ensures that a query's affected row count meets expectations.
     *
     * Verifies that the affected row count matches the expected count. If not, logs
     * the error and throws an exception to notify the caller.
     *
     * @param CustomDebug $debug        Instance of the CustomDebug class for error reporting.
     * @param int         $result       Number of rows affected by the query.
     * @param int         $expected     Expected number of affected rows.
     * @param string      $errorMessage Error message to log and throw if the row count is invalid.
     *
     * @throws \DatabaseException       If the row count is zero or does not match the expected count.
     */
    public static function ensureRowUpdateResult(
        CustomDebug $debug,
        int $result,
        int $expected,
        string $errorMessage
    ): void {
        if ($result === 0) {
            $debug->failDatabase($errorMessage . " No rows were affected.");
        }
        if ($result !== $expected) {
            $debug->failDatabase($errorMessage . " Unexpected number of affected rows.");
        }
    }

    /**
     * Executes a raw SQL query, typically for DELETE or LOAD DATA INFILE operations.
     *
     * Logs the SQL query, executes it using the provided database instance, and returns
     * the number of affected rows for non-SELECT queries.
     *
     * @param CustomDebug  $debug Instance of the CustomDebug class for logging and debugging.
     * @param DBConnection $db    Instance of the DBConnection class for executing queries.
     * @param string       $sql   Raw SQL query string to execute.
     *
     * @return int                Number of rows affected by the query (or rows returned for SELECT).
     */
    public static function executeRawQueryWithDebug(
        CustomDebug $debug,
        DBConnection $db,
        string $sql
    ): int {
        // Debug the raw SQL
        $debug->debug("Raw SQL: {$sql}");

        // Execute the query directly
        $result = $db->executeRawQuery($sql);

        // Determine the affected rows based on the result type
        if (is_array($result)) {
            // SELECT query: count the rows returned
            $affectedRows = count($result);
        } else {
            // Non-SELECT query: $result is the number of affected rows
            $affectedRows = (int) $result;
        }

        // Debug the result
        $debug->debugVariable($affectedRows, "Query [RAW SQL] Rows Affected");

        return $affectedRows;
    }

    /**
     *
     * HELPER METHODS
     *
     */

    /**
     * Returns the SQL sort direction (ASC or DESC) based on the input.
     *
     * @param bool $sortAsc Whether to sort in ascending order (true) or descending (false).
     *
     * @return string       The SQL sort direction ('ASC' or 'DESC').
     */
    public static function getSortString(
        bool $sortAsc = true
    ): string {
        return $sortAsc ? 'ASC' : 'DESC';
    }
}
