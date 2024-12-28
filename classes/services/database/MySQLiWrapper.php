<?php

declare(strict_types=1);

namespace App\services\database;

/**
 * MySQLiWrapper class provides an abstraction layer over PHP's MySQLi extension.
 *
 * This class wraps MySQLi functionality for easier testing and enhanced usability.
 * It provides helper methods to interact with MySQLi properties and methods in
 * a clean, testable manner.
 *
 * @category Database
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 * @since    2024-12-13
 *
 * @uses \mysqli
 * @uses \mysqli_result
 * @uses \mysqli_stmt
 */
class MySQLiWrapper
{
    /**
     * The underlying MySQLi instance.
     *
     * @var \mysqli
     */
    private $mysqli;

    /**
     * Constructor for MySQLiWrapper.
     *
     * Initializes the wrapper with a MySQLi instance.
     *
     * @param \mysqli $mysqli The MySQLi instance to wrap.
     */
    public function __construct(\mysqli $mysqli)
    {
        $this->mysqli = $mysqli;
    }

    /**
     * Retrieves the connection error, if any.
     *
     * Returns the connection error string from the MySQLi instance or null
     * if no error occurred.
     *
     * @return string|null The connection error string or null if no error.
     */
    public function getConnectError(): ?string
    {
        return $this->mysqli->connect_error;
    }

    /**
     * Retrieves the last error message for the current connection.
     *
     * @return string|null The error message, or null if no error occurred.
     */
    public function getError(): ?string
    {
        return $this->mysqli->error;
    }

    /**
     * Retrieves the number of rows affected by the last operation.
     *
     * Returns the number of rows affected by the last INSERT, UPDATE, DELETE, or
     * other non-SELECT query executed on the MySQLi instance.
     *
     * @return int The number of affected rows.
     */
    public function getAffectedRows(): int
    {
        return $this->mysqli->affected_rows;
    }

    /**
     * Retrieves the ID generated for an AUTO_INCREMENT column by the last INSERT query.
     *
     * Returns the ID of the last row inserted into the database if the table
     * has an AUTO_INCREMENT column.
     *
     * @return int The ID of the last inserted row.
     */
    public function getLastInsertId(): int
    {
        return $this->mysqli->insert_id;
    }

    /**
     * Binds parameters to a prepared statement.
     *
     * Uses the spread operator to dynamically bind parameters to the provided
     * prepared statement, making it easier to pass an array of arguments.
     *
     * @param \mysqli_stmt $stmt   The prepared statement to bind parameters to.
     * @param string       $types  A string of types for each parameter ('i' for int, 's' for string, etc.).
     * @param array        &$params The parameters to bind (by reference).
     *
     * @return bool True on success, false on failure.
     */
    public function bindParams(\mysqli_stmt $stmt, string $types, array &$params): bool
    {
        // Use the spread operator to pass arguments dynamically
        return $stmt->bind_param($types, ...$params);
    }

    /**
     * Closes the database connection.
     *
     * @return bool True on success, false on failure.
     */
    public function close(): bool
    {
        return $this->mysqli->close();
    }

    /**
     * Starts a transaction.
     *
     * @return bool True on success, false on failure.
     */
    public function begin_transaction(): bool
    {
        return $this->mysqli->begin_transaction();
    }

    /**
     * Commits the current transaction.
     *
     * @return bool True on success, false on failure.
     */
    public function commit(): bool
    {
        return $this->mysqli->commit();
    }

    /**
     * Rolls back the current transaction.
     *
     * @return bool True on success, false on failure.
     */
    public function rollback(): bool
    {
        return $this->mysqli->rollback();
    }

    /**
     * Prepares a SQL statement.
     *
     * @param string $sql The SQL query to prepare.
     *
     * @return \mysqli_stmt|null The prepared statement object, or null on failure.
     */
    public function prepare(string $sql): ?\mysqli_stmt
    {
        return $this->mysqli->prepare($sql);
    }

    /**
     * Executes a raw SQL query.
     *
     * @param string $sql The SQL query to execute.
     *
     * @return \mysqli_result|bool The result object for SELECT queries or true/false for non-SELECT queries.
     */
    public function query(string $sql): ?\mysqli_result
    {
        return $this->mysqli->query($sql);
    }

    /**
     * Delegates method calls to the underlying MySQLi instance.
     *
     * This magic method intercepts calls to undefined methods and forwards them
     * to the underlying MySQLi instance if they exist.
     *
     * @param string $name      The name of the method being called.
     * @param array  $arguments The arguments passed to the method.
     *
     * @return mixed The result of the delegated method call.
     *
     * @throws \BadMethodCallException If the method does not exist on MySQLiWrapper or \mysqli.
     */
    public function __call(string $name, array $arguments)
    {
        if (method_exists($this->mysqli, $name)) {
            return $this->mysqli->$name(...$arguments);
        }

        throw new \BadMethodCallException("Method {$name} does not exist on MySQLiWrapper or \mysqli.");
    }
}
