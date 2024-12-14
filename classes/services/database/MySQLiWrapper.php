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
}
