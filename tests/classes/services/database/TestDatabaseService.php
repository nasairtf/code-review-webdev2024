<?php

declare(strict_types=1);

namespace Tests\classes\services\database;

use App\services\database\DatabaseService as BaseService;

class TestDatabaseService extends BaseService
{
    /**
     * Proxy method for testing the protected startTransaction method.
     *
     * @return void
     * @throws Exception If the transaction fails to begin.
     */
    public function startTransactionProxy(): void
    {
        parent::startTransaction();
    }

    /**
     * Proxy method for testing the protected commitTransaction method.
     *
     * @return void
     * @throws Exception If the commit operation fails.
     */
    public function commitTransactionProxy(): void
    {
        parent::commitTransaction();
    }

    /**
     * Proxy method for testing the protected rollbackTransaction method.
     *
     * @return void
     * @throws Exception If the rollback operation fails.
     */
    public function rollbackTransactionProxy(): void
    {
        parent::rollbackTransaction();
    }

    /**
     * Proxy method for testing the protected fetchDataWithQuery method.
     *
     * @param string $sql          The SQL query string.
     * @param array  $params       Parameters to bind to the query.
     * @param string $types        The types of the parameters (e.g., 's' for string, 'i' for integer).
     * @param string $errorMessage The error message if no results are found.
     *
     * @return array               The query results.
     * @throws Exception           If the query returns empty or fails.
     */
    public function fetchDataWithQueryProxy(string $sql, array $params, string $types, string $errorMessage): array
    {
        return $this->fetchDataWithQuery($sql, $params, $types, $errorMessage);
    }

    /**
     * Proxy method for testing the protected executeSelectQuery method.
     *
     * @param string $sql          The SQL query string.
     * @param array  $params       Parameters to bind to the query.
     * @param string $types        The types of the parameters.
     * @param int    $resultType   The type of result array to return (e.g., MYSQLI_ASSOC).
     *
     * @return array               The query results.
     * @throws Exception           If the query fails.
     */
    public function executeSelectQueryProxy(string $sql, array $params, string $types, int $resultType): array
    {
        return $this->executeSelectQuery($sql, $params, $types, $resultType);
    }

    /**
     * Proxy method for testing the protected ensureNotEmpty method.
     *
     * @param array  $data         The data returned from the query.
     * @param string $errorMessage The error message to throw if data is empty.
     *
     * @return void
     * @throws Exception
     */
    public function ensureNotEmptyProxy(array $data, string $errorMessage): void
    {
        parent::ensureNotEmpty($data, $errorMessage);
    }

    /**
     * Proxy method for testing the protected modifyDataWithQuery method.
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
    public function modifyDataWithQueryProxy(
        string $sql,
        array $params,
        string $types,
        int $rowsExpected,
        string $errorMessage
    ): int {
        return $this->modifyDataWithQuery($sql, $params, $types, $rowsExpected, $errorMessage);
    }

    /**
     * Proxy method for testing the protected executeUpdateQuery method.
     *
     * @param string $sql          The SQL query string.
     * @param array  $params       Parameters to bind to the query.
     * @param string $types        The types of the parameters.
     *
     * @return int                 The number of affected rows.
     * @throws Exception           If the query fails.
     */
    public function executeUpdateQueryProxy(string $sql, array $params, string $types): int
    {
        return $this->executeUpdateQuery($sql, $params, $types);
    }

    /**
     * Proxy method for testing the protected ensureValidRowCount method.
     *
     * @param int    $rowsAffected The number of affected rows.
     * @param int    $rowsExpected The expected number of affected rows.
     * @param string $errorMessage The error message if the row count is invalid.
     *
     * @return void
     * @throws Exception
     */
    public function ensureValidRowCountProxy(int $rowsAffected, int $rowsExpected, string $errorMessage): void
    {
        parent::ensureValidRowCount($rowsAffected, $rowsExpected, $errorMessage);
    }

    /**
     * Proxy method for testing the protected getSortString method.
     *
     * @param bool $sortAsc        Whether to sort in ascending order (default: true).
     *
     * @return string              The SQL sort direction (ASC/DESC).
     */
    public function getSortStringProxy(bool $sortAsc): string
    {
        return $this->getSortString($sortAsc);
    }
}
