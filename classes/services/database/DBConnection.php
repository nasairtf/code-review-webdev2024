<?php

declare(strict_types=1);

namespace App\services\database;

use App\core\common\Config;
use App\core\common\CustomDebug;
use App\exceptions\DatabaseException;
use App\services\database\MySQLiWrapper;

/**
 * DBConnection class provides a singleton connection pool for database interactions.
 *
 * This class supports CRUD operations, transactions, and singleton connection management.
 * It uses `CustomDebug` for error reporting and `Config` for database configuration.
 *
 * The DBConnection class is immutable and locked to ensure system stability.
 *
 * NOTE: For additional features or domain-specific behavior, extend this class
 * (e.g., CustomDBConnection).
 *
 * @category Database
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.2.4
 * @since    2024-10-29
 *
 * @uses \App\core\common\Config
 * @uses \App\core\common\CustomDebug
 * @uses \App\exceptions\DatabaseException
 * @uses \App\services\database\MySQLiWrapper
 */
class DBConnection
{
    /**
     * CustomDebug instance for logging and output.
     *
     * @var CustomDebug
     */
    private $debug;

    /**
     * MySQLi connection instance.
     *
     * @var \mysqli|null
     */
    private $connection;

    /**
     * Singleton instance pool of the DBConnection class.
     *
     * @var array
     */
    private static $instances = [];

    /**
     * Constructor for DBConnection class, initializes a database connection.
     *
     * @param string             $dbName        Database name for configuration lookup.
     * @param bool               $debugMode     Whether to enable debug mode.
     * @param MySQLiWrapper|null $mysqliWrapper Optional MySQLiWrapper instance for testing.
     * @param CustomDebug|null   $debug         Optional CustomDebug instance for testing.
     *
     * @throws DatabaseException If the database configuration is missing or the connection fails.
     */
    private function __construct(
        string $dbName,
        ?bool $debugMode = null,
        ?MySQLiWrapper $mysqliWrapper = null,
        ?CustomDebug $debug = null
    ) {
        // Only set debug mode once during instance creation
        $this->debug = $debug ?? new CustomDebug('db', $debugMode ?? false, $debugMode ? 1 : 0); // base-level service class

        // Fetch the config for DBConnection credentials
        $config = Config::get('db_config');

        // Check if the requested database exists in the config
        if (!isset($config[$dbName])) {
            $this->debug->failDatabase("Database configuration for '{$dbName}' not found.");
        }

        // Get the credentials for the specified database
        $dbConfig = $config[$dbName];

        // Establish the MySQLi connection
        $mysqliWrapper = $mysqliWrapper ?: new MySQLiWrapper(new \mysqli(
            $dbConfig['host'],
            $dbConfig['username'],
            $dbConfig['password'],
            $dbConfig['dbname']
        ));

        // Handle connection failure
        $connectError = $mysqliWrapper->getConnectError();
        if ($connectError) {
            $this->debug->failDatabase('Database connection failed.');
        }

        // Assign the MySQLiWrapper as the connection
        $this->connection = $mysqliWrapper;

        // Log successful connection
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
     * Returns the singleton instance of the DBConnection class for a specific database.
     *
     * @param string           $dbName    Database name.
     * @param bool             $debugMode Whether to enable debug mode.
     * @param mysqli|null      $mysqli    Optional mysqli instance for testing.
     * @param CustomDebug|null $debug     Optional CustomDebug instance for testing.
     *
     * @return DBConnection Database connection instance.
     *
     * @uses \mysqli
     * @uses \App\core\common\Config
     * @uses \App\core\common\CustomDebug
     */
    public static function getInstance(
        string $dbName,
        ?bool $debugMode = null,
        ?MySQLiWrapper $mysqliWrapper = null,
        ?CustomDebug $debug = null
    ): DBConnection {
        if (!isset(self::$instances[$dbName])) {
            self::$instances[$dbName] = new self($dbName, $debugMode ?? false, $mysqliWrapper, $debug); // base-level service class
        } elseif ($mysqliWrapper && self::$instances[$dbName]->connection !== $mysqliWrapper) {
            // Replace the existing connection with the provided $mysqliWrapper during testing
            self::$instances[$dbName]->connection = $mysqliWrapper;
        }
        return self::$instances[$dbName];
    }

    /**
     * Clears a specific database instance from the instance pool and closes the connection.
     *
     * @param string $dbName Name of the database connection to close.
     *
     * @return void
     */
    public static function clearInstance(string $dbName): void
    {
        if (isset(self::$instances[$dbName])) {
            $instance = self::$instances[$dbName];
            $instance->closeConnection();
            $instance->debug->debug("Connection to database {$dbName} has been closed.");
            unset(self::$instances[$dbName]);
        }
    }

    /**
     * Begins a database transaction.
     *
     * @throws DatabaseException If the connection is not established.
     *
     * @return void
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
     * @throws DatabaseException If the connection is not established.
     *
     * @return void
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
     * @throws DatabaseException If the connection is not established.
     *
     * @return void
     */
    public function rollback(): void
    {
        $this->ensureConnection();
        $this->debug->debug("Rolling back transaction.");
        $this->connection->rollback();
    }

    /**
     * Closes the MySQLi database connection.
     *
     * @return void
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
     * @param string $sql        SQL query to execute.
     * @param array  $params     Parameters for the prepared statement.
     * @param string $types      Parameter types for the prepared statement (e.g., 'ssi').
     * @param int    $resultType Type of result array (MYSQLI_ASSOC, MYSQLI_NUM, MYSQLI_BOTH).
     *
     * @return array|int Array of results for SELECT queries or number of affected rows for non-SELECT queries.
     *
     * @throws DatabaseException If query preparation or execution fails.
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
        $this->debug->debug("Preparing SQL: {$sql}");

        // Handle query preparation failure
        if (!$stmt) {
            $this->debug->failDatabase("Prepare failed for query: {$sql}", $this->connection->error);
        }

        // If there are parameters to bind, bind them to the prepared statement
        if (!empty($params)) {
            // 'bind_param' binds the parameters to the SQL query
            // Spread operator to pass array elements as individual arguments
            // The bind_param call has been replaced by the bindParams wrapper method
            //  to simplify unit testing.
            //$stmt->bind_param($types, ...$params);
            if (!$this->connection->bindParams($stmt, $types, $params)) {
                $this->debug->failDatabase("Failed to bind parameters for query: {$sql}");
            }
        }

        // Execute the prepared statement
        $stmterror = $stmt->execute();

        // Handle execution failure
        if (!$stmterror) {
            // $stmt->error interferes with unit testing.
            $this->debug->failDatabase("Execute failed for query: {$sql}");
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
        //$affectedRows = $stmt->affected_rows;
        $affectedRows = $this->connection->getAffectedRows();
        $this->debug->debug("Query affected {$affectedRows} rows.");
        $stmt->close();
        return $affectedRows;
    }

    /**
     * Executes a raw SQL query directly.
     *
     * @param string $sql Raw SQL query to execute.
     *
     * @return array|int Array of results for SELECT queries or number of affected rows for non-SELECT queries.
     *
     * @throws DatabaseException If query execution fails.
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
            $this->debug->failDatabase("Query failed: {$sql}", $this->connection->error);
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
        //$affectedRows = $this->connection->affected_rows;
        $affectedRows = $this->connection->getAffectedRows();
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
        // Verify connection is still valid
        $this->ensureConnection();

        // Return the affected rows
        //return $this->connection->affected_rows;
        return $this->connection->getAffectedRows();
    }

    /**
     * Returns the last inserted ID from an INSERT query.
     *
     * @return int Auto-generated ID from the last INSERT query.
     */
    public function getLastInsertId(): int
    {
        // Verify connection is still valid
        $this->ensureConnection();

        // Return the last inserted ID
        //return $this->connection->insert_id;
        return $this->connection->getLastInsertId();
    }

    /**
     * Ensures the database connection is valid.
     *
     * @throws DatabaseException If the connection is not valid.
     *
     * @return void
     */
    private function ensureConnection(): void
    {
        if ($this->connection === null) {
            $this->debug->failDatabase("Database connection is not established.");
        }
    }
}
