<?php

declare(strict_types=1);

namespace App\services\database;

use App\core\common\Debug;

/**
 * /home/webdev2024/classes/core/database/DB.php
 *
 * DB class provides a singleton connection pool allowing interaction with multiple
 * IRTF databases, supporting basic CRUD and transaction operations.
 *
 * Created:
 *  2024/09/24 - Miranda Hawarden-Ogata
 *
 * Modified:
 *  2024/09/26 - Miranda Hawarden-Ogata
 *      - Refactored to use obsapp_id instead of SessionID for reliability.
 *      - Implemented Singleton pattern to reuse database connections.
 *      - PSR-12 formatting applied.
 *      - Refactored to throw exceptions instead of die() for better error handling.
 *      - Refactored to provide multi-line method signatures for methods with arguments.
 *      - Added outputDebugInfo method.
 *      - Added 'use mysqli' to import class.
 *      - Removed type hints due to PHP version 7.2. Can re-add after update to 7.4+.
 *  2024/10/15 - Miranda Hawarden-Ogata
 *      - Refactored logError/outputDebugInfo to DebugUtility class for better error handling.
 *      - Added connection verification method.
 *      - Added default debug colour of orange for DB debug output.
 *  2024/10/20 - Miranda Hawarden-Ogata
 *      - Updated DebugUtility:: calls to use Debug class instance calls.
 *  2024/10/21 - Miranda Hawarden-Ogata
 *      - Set up composer and autoloading.
 *  2024/10/29 - Miranda Hawarden-Ogata
 *      - Added instance pool handling to allow connections to multiple databases.
 *
 * @category Database
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.2.4
 */

class DB
{
    /** @var Debug       $debug      Debug instance for logging and output. */
    private $debug;

    /** @var mysqli|null $connection Stores the MySQLi connection. */
    private $connection;

    /** @var array       $instances  Singleton instance pool of the DB class. */
    private static $instances = [];

    /**
     * Constructor for DB class, initializes a database connection using provided credentials.
     *
     * @param string $dbName Database name for configuration lookup.
     * @param bool $debugMode Whether to enable debug mode.
     * @throws Exception if the database configuration is missing or connection fails.
     */
    private function __construct(
        string $dbName,
        ?bool $debugMode = null
    ) {
        // Only set debug mode once during instance creation
        $this->debug = new Debug('db', $debugMode ?? false, $debugMode ? 1 : 0); // base-level service class

        // Load the config file for DB credentials
        $config = require CONFIG_PATH . 'db_config.php';

        // Check if the requested database exists in the config
        if (!isset($config[$dbName])) {
            $this->debug->fail("Database configuration for '{$dbName}' not found.");
        }

        // Get the credentials for the specified database
        $dbConfig = $config[$dbName];

        // Establish the MySQLi connection
        $this->connection = new \mysqli(
            $dbConfig['host'],
            $dbConfig['username'],
            $dbConfig['password'],
            $dbConfig['dbname']
        );

        // Handle connection failure
        if ($this->connection->connect_error) {
            $this->debug->fail("Database connection failed: " . $this->connection->connect_error);
        }

        // Debug information for MySQLi connection
        $this->debug->debug("Connected to database: {$dbConfig['dbname']} at {$dbConfig['host']}");
    }

    /**
     * Destructor, closes the database connection when the object is destroyed.
     */
    public function __destruct()
    {
        $this->closeConnection();
    }

    /**
     * Returns the singleton instance of the DB class for a specific database.
     *
     * @param string $dbName Database name.
     * @param bool $debugMode Whether to enable debug mode.
     * @return DB Database connection instance.
     */
    public static function getInstance(
        string $dbName,
        ?bool $debugMode = null
    ): DB {
        if (!isset(self::$instances[$dbName])) {
            self::$instances[$dbName] = new self($dbName, $debugMode ?? false); // base-level service class
        }
        return self::$instances[$dbName];
    }

    /**
     * Clears a specific database instance from the instance pool and closes the connection.
     *
     * @param string $dbName Name of the database connection to close.
     */
    public static function clearInstance(string $dbName): void
    {
        if (isset(self::$instances[$dbName])) {
            self::$instances[$dbName]->closeConnection();
            unset(self::$instances[$dbName]);
            // Debug information for MySQLi connection
            $this->debug->debug("Connection to database {$dbName} has been closed.");
        }
    }

    /**
     * Begins a transaction.
     *
     * @throws Exception If connection is not established.
     */
    public function beginTransaction(): void
    {
        $this->ensureConnection();
        $this->debug->debug("Starting transaction.");
        $this->connection->begin_transaction();
    }

    /**
     * Commits the current transaction.
     *
     * @throws Exception If connection is not established.
     */
    public function commit(): void
    {
        $this->ensureConnection();
        $this->debug->debug("Committing transaction.");
        $this->connection->commit();
    }

    /**
     * Rolls back the current transaction.
     *
     * @throws Exception If connection is not established.
     */
    public function rollback(): void
    {
        $this->ensureConnection();
        $this->debug->debug("Rolling back transaction.");
        $this->connection->rollback();
    }

    /**
     * Closes the MySQLi database connection.
     */
    public function closeConnection(): void
    {
        if ($this->connection) {
            $this->connection->close();
            $this->connection = null;
            $this->debug->debug("Database connection closed.");
        }
    }

    /**
     * Executes a prepared SQL query with optional parameter binding.
     *
     * @param string $sql SQL query to execute.
     * @param array $params Parameters for the prepared statement.
     * @param string $types Parameter types for the prepared statement.
     * @param int $resultType Type of result array (MYSQLI_ASSOC, MYSQLI_NUM, MYSQLI_BOTH).
     * @return array|int Array of results for SELECT queries or number of affected rows for non-SELECT queries.
     * @throws Exception If query preparation or execution fails.
     */
    public function executeQuery(
        string $sql,
        array $params = [],
        string $types = '',
        int $resultType = MYSQLI_ASSOC
    ) {
        // Verify connection is still valid
        $this->ensureConnection();

        // Prepare the SQL query (prevents SQL injection)
        $stmt = $this->connection->prepare($sql);

        // Debug information for query preparation
        $this->debug->debug("Preparing SQL: {$sql}");
        if (!empty($params)) {
            $this->debug->debug("Binding Params: " . print_r($params, true));
            $this->debug->debug("Param Types: {$types}");
        }

        // Handle query preparation failure
        if (!$stmt) {
            $this->debug->fail(
                "Prepare failed for query: {$sql} - " . $this->connection->error,
                "Query preparation failed for: {$sql} with parameters: " . json_encode($params)
            );
        }

        // If there are parameters to bind, bind them to the prepared statement
        if (!empty($params)) {
            // 'bind_param' binds the parameters to the SQL query
            // Spread operator to pass array elements as individual arguments
            $stmt->bind_param($types, ...$params);
        }

        // Execute the prepared statement
        $stmt->execute();

        // Handle execution failure
        if ($stmt->error) {
            $this->debug->fail(
                "Execute failed for query: {$sql} - " . $stmt->error,
                "Query execution failed for: {$sql}"
            );
        }

        // Debug information for execution
        $this->debug->debug("Executed query successfully.");

        // Handle SELECT queries by returning the result set
        if (preg_match('/^\s*(SELECT|SHOW|DESCRIBE|EXPLAIN)\s/i', $sql)) {
            $result = $stmt->get_result();
            $rows = [];
            while ($row = $result->fetch_array($resultType)) {
                $rows[] = $row;
            }
            $stmt->close();
            $this->debug->debug("Query returned " . count($rows) . " rows.");
            return $rows;
        }

        // Handle non-SELECT queries by returning the number of affected rows
        $affectedRows = $stmt->affected_rows;
        $this->debug->debug("Query affected {$affectedRows} rows.");
        $stmt->close();
        return $affectedRows;
    }

    /**
     * Executes a raw SQL query directly.
     *
     * @param string $sql Raw SQL query to execute.
     * @return array|int Array of results for SELECT queries or number of affected rows for non-SELECT queries.
     * @throws Exception If query execution fails.
     */
    public function executeRawQuery(string $sql)
    {
        // Verify connection is still valid
        $this->ensureConnection();

        // Debug information for query execution
        $this->debug->debug("Executing Raw SQL: {$sql}");

        // Execute the query directly
        $result = $this->connection->query($sql);

        // Handle execution failure
        if (!$result) {
            $this->debug->fail(
                "Query failed: {$sql} - " . $this->connection->error,
                "Query execution failed for: {$sql}"
            );
        }

        // Handle SELECT queries by returning the result set
        if (preg_match('/^\s*(SELECT|SHOW|DESCRIBE|EXPLAIN)\s/i', $sql)) {
            $rows = [];
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            $result->free();
            $this->debug->debug("Query returned " . count($rows) . " rows.");
            return $rows;
        }

        // Handle non-SELECT queries by returning the number of affected rows
        $affectedRows = $this->connection->affected_rows;
        $this->debug->debug("Query affected {$affectedRows} rows.");
        return $affectedRows;
    }

    /**
     * Returns the number of rows affected by the most recent non-SELECT query.
     *
     * @return int Number of affected rows.
     */
    public function getAffectedRows(): int
    {
        return $this->connection->affected_rows;
    }

    /**
     * Returns the last inserted ID from an INSERT query.
     *
     * @return int Auto-generated ID from the last INSERT query.
     */
    public function getLastInsertId(): int
    {
        return $this->connection->insert_id;
    }

    /**
     * Ensures the database connection is valid.
     *
     * @throws Exception If the connection is not valid.
     */
    private function ensureConnection(): void
    {
        if ($this->connection === null) {
            $this->debug->fail("Database connection is not established.");
        }
    }
}
