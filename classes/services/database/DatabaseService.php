<?php

declare(strict_types=1);

namespace App\services\database;

use Exception;
use App\core\common\Debug;

/**
 * DatabaseService class that provides core functionality for all database services.
 *
 * @category Services
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class DatabaseService
{
    protected $debug;
    protected $db;

    public function __construct(
        string $dbName,
        ?bool $debugMode = null
    ) {
        $this->debug = new Debug('database', $debugMode ?? false, $debugMode ? 1 : 0); // base-level service class
        $this->db = DB::getInstance($dbName, $debugMode ?? false);
    }

    /**
     *
     * HANDLES TRANSACTIONS
     *
     */

    /**
     * Initiates a database transaction.
     *
     * This method begins a transaction on the database connection, allowing for
     * multiple database operations to be executed as a single unit of work.
     *
     * @return void
     * @throws Exception If the transaction fails to begin.
     */
    protected function startTransaction(): void
    {
        $this->db->beginTransaction();
    }

    /**
     * Commits the current transaction.
     *
     * Finalizes the transaction by saving all changes made during the transaction.
     * This is typically called after all operations in a transactional workflow succeed.
     *
     * @return void
     * @throws Exception If the commit operation fails.
     */
    protected function commitTransaction(): void
    {
        $this->db->commit();
    }

    /**
     * Rolls back the current transaction.
     *
     * Reverts all changes made during the current transaction. This should be used
     * if any operation within the transaction encounters an error, ensuring the database
     * remains in a consistent state.
     *
     * @return void
     * @throws Exception If the rollback operation fails.
     */
    protected function rollbackTransaction(): void
    {
        $this->db->rollback();
    }

    /**
     *
     * HANDLES READ QUERIES AND RESULTS
     *
     */

    /**
     * Executes a SELECT query and validates that the result is non-empty.
     *
     * @param string $sql          The SQL query string.
     * @param array  $params       Parameters to bind to the query.
     * @param string $types        The types of the parameters (e.g., 's' for string, 'i' for integer).
     * @param string $errorMessage The error message if no results are found.
     *
     * @return array               The query results.
     * @throws Exception           If the query returns empty or fails.
     */
    protected function fetchDataWithQuery(
        string $sql,
        array $params = [],
        string $types = '',
        string $errorMessage = "No data found"
    ): array {
        $results = $this->executeSelectQuery($sql, $params, $types);
        $this->ensureNotEmpty($results, $errorMessage);
        return $results;
    }

    /**
     * Executes a SELECT SQL query with error handling and logging.
     *
     * @param string $sql          The SQL query string.
     * @param array  $params       Parameters to bind to the query.
     * @param string $types        The types of the parameters.
     * @param int    $resultType   The type of result array to return (e.g., MYSQLI_ASSOC).
     *
     * @return array               The query results.
     * @throws Exception           If the query fails.
     */
    protected function executeSelectQuery(
        string $sql,
        array $params = [],
        string $types = '',
        int $resultType = MYSQLI_ASSOC
    ): array {
        try {
            return DbQueryUtility::executeSelectQueryWithDebug(
                $this->debug,
                $this->db,
                $sql,
                $params,
                $types,
                $resultType
            );
        } catch (Exception $e) {
            $this->debug->fail("Error executing SELECT query: " . $e->getMessage());
        }
    }

    /**
     * Ensures that the query results are not empty.
     *
     * @param array  $data         The data returned from the query.
     * @param string $errorMessage The error message to throw if data is empty.
     *
     * @throws Exception
     */
    protected function ensureNotEmpty(
        array $data,
        string $errorMessage
    ): void {
        try {
            DbQueryUtility::ensureQueryResultsNotEmpty(
                $this->debug,
                $data,
                $errorMessage
            );
        } catch (Exception $e) {
            $this->debug->fail("Empty result error: " . $e->getMessage());
        }
    }

    /**
     *
     * HANDLES WRITE QUERIES AND RESULTS
     *
     */

    /**
     * Executes an INSERT, UPDATE, or DELETE query and validates that the result affects the expected number of rows.
     *
     * @param string $sql          The SQL query string.
     * @param array  $params       Parameters to bind to the query.
     * @param string $types        The types of the parameters.
     * @param int    $rowsExpected The expected number of affected rows.
     * @param string $errorMessage The error message if no or unexpected rows are affected.
     *
     * @return int                 The number of affected rows.
     * @throws Exception           If the query fails or affects an unexpected number of rows.
     */
    protected function modifyDataWithQuery(
        string $sql,
        array $params = [],
        string $types = '',
        int $rowsExpected = 1,
        string $errorMessage = "No rows were affected"
    ): int {
        $rowsAffected = $this->executeUpdateQuery($sql, $params, $types);
        $this->ensureValidRowCount($rowsAffected, $rowsExpected, $errorMessage);
        return $rowsAffected;
    }

    /**
     * Executes an INSERT, UPDATE, or DELETE query with error handling and debugging.
     *
     * @param string $sql          The SQL query string.
     * @param array  $params       Parameters to bind to the query.
     * @param string $types        The types of the parameters.
     *
     * @return int                 The number of affected rows.
     * @throws Exception           If the query fails.
     */
    protected function executeUpdateQuery(
        string $sql,
        array $params = [],
        string $types = ''
    ): int {
        try {
            return DbQueryUtility::executeQueryWithDebug(
                $this->debug,
                $this->db,
                $sql,
                $params,
                $types
            );
        } catch (Exception $e) {
            $this->debug->fail("Error executing INSERT/UPDATE/DELETE query: " . $e->getMessage());
        }
    }

    /**
     * Ensures that the number of affected rows is valid.
     *
     * @param int    $rowsAffected The number of affected rows.
     * @param int    $rowsExpected The expected number of affected rows.
     * @param string $errorMessage The error message if the row count is invalid.
     *
     * @throws Exception
     */
    protected function ensureValidRowCount(
        int $rowsAffected,
        int $rowsExpected,
        string $errorMessage
    ): void {
        try {
            DbQueryUtility::ensureRowUpdateResult(
                $this->debug,
                $rowsAffected,
                $rowsExpected,
                $errorMessage
            );
        } catch (Exception $e) {
            $this->debug->fail("Unexpected row-count error: " . $e->getMessage());
        }
    }

    /**
     *
     * HELPER METHODS
     *
     */

    /**
     * Sort helper to be used in child classes.
     *
     * @param bool $sortAsc        Whether to sort in ascending order (default: true).
     *
     * @return string              The SQL sort direction (ASC/DESC).
     */
    protected function getSortString(
        bool $sortAsc = true
    ): string {
        return DbQueryUtility::getSortString($sortAsc);
    }
}
