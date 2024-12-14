<?php

declare(strict_types=1);

namespace Tests\classes\services\database;

use Mockery;
use PHPUnit\Framework\TestCase;
use Tests\utilities\ConfigMockTrait;
use Tests\utilities\CustomDebugMockTrait;
use Tests\utilities\PrivatePropertyTrait;
use Tests\utilities\MySQLiWrapperMockTrait;
use App\services\database\DBConnection;
use App\exceptions\DatabaseException;

/**
 * Unit tests for the DBConnection class.
 *
 * @covers \App\services\database\DBConnection
 */
class DBConnectionTest extends TestCase
{
    use PrivatePropertyTrait, ConfigMockTrait, CustomDebugMockTrait, MySQLiWrapperMockTrait;

    /**
     * Mock instance of CustomDebug.
     *
     * @var Mockery\MockInterface
     */
    private $debugMock;

    /**
     * Mock instance of MySQLiWrapper.
     *
     * @var Mockery\MockInterface
     */
    private $mysqliMock;

    /**
     * Mock instance of MySQLi statement.
     *
     * @var Mockery\MockInterface
     */
    private $mysqliStmtMock;

    /**
     * Test that an exception is thrown if the database configuration is missing.
     *
     * @covers \App\services\database\DBConnection::getInstance
     */
    public function testThrowsExceptionForMissingConfiguration(): void
    {
        $dbName = 'invalid_db';

        // Override Config mock to simulate missing database configuration
        $configData = [
            'db_config' => [] // No databases configured
        ];
        $this->createConfigMock($configData);

        // Assert that the correct exception is thrown
        $this->expectException(DatabaseException::class);
        $this->expectExceptionMessage("Database configuration for '{$dbName}' not found.");

        // Attempt to get an instance for a non-existent database
        DBConnection::getInstance($dbName, false, $this->mysqliWrapperMock, $this->debugMock);
    }

    /**
     * Test constructor throws an exception for connection failure.
     *
     * @covers \App\services\database\DBConnection::__construct
     */
    public function testConstructorThrowsExceptionForConnectionFailure(): void
    {
        $dbName = 'test_db';

        // Explicitly set up the expected return value for MySQLiWrapper mock
        $this->mysqliWrapperMock->shouldReceive('getConnectError')
            ->andReturn('Connection error');

        // Expect exception
        $this->expectException(DatabaseException::class);
        $this->expectExceptionMessage('Database connection failed.');

        // Attempt to create an instance
        DBConnection::getInstance($dbName, false, $this->mysqliWrapperMock, $this->debugMock);
    }

    /**
     * Test that a database connection can be established successfully.
     *
     * @covers \App\services\database\DBConnection::getInstance
     */
    public function testConnectionEstablishment(): void
    {
        $dbName = 'test_db';

        // Inject mocks
        $db = DBConnection::getInstance($dbName, false, $this->mysqliWrapperMock, $this->debugMock);

        // Assert connection is established and matches mysqliMock
        $this->assertSame($this->mysqliWrapperMock, $this->getPrivateProperty($db, 'connection'));
    }

    /**
     * Test ensureConnection method throws an exception for invalid connection.
     *
     * @covers \App\services\database\DBConnection::ensureConnection
     */
    public function testEnsureConnectionThrowsException(): void
    {
        $db = $this->createMockedDB();

        // Manually invalidate the connection
        $this->setPrivateProperty($db, 'connection', null);

        // Expect exception
        $this->expectException(DatabaseException::class);
        $this->expectExceptionMessage('Database connection is not established.');

        // Call a method that triggers ensureConnection
        $db->beginTransaction();
    }

    /**
     * Test closeConnection method closes the database connection.
     *
     * @covers \App\services\database\DBConnection::closeConnection
     */
    /** WORKING **/
    public function testCloseConnection(): void
    {
        $db = $this->createMockedDB();

        // Close the connection
        $db->closeConnection();

        // Assert connection is null
        $this->assertNull($this->getPrivateProperty($db, 'connection'));
    }

    /**
     * Test getInstance method returns a singleton instance.
     *
     * @covers \App\services\database\DBConnection::getInstance
     */
    public function testGetInstanceReturnsSingleton(): void
    {
        $dbName = 'test_db';

        // Retrieve the singleton instance
        $dbInstance1 = DBConnection::getInstance($dbName, false, $this->mysqliWrapperMock, $this->debugMock);

        // Retrieve the same instance again
        $dbInstance2 = DBConnection::getInstance($dbName, false, $this->mysqliWrapperMock, $this->debugMock);

        // Assert the two instances are the same
        $this->assertSame($dbInstance1, $dbInstance2);
        $this->assertSame($this->mysqliWrapperMock, $this->getPrivateProperty($dbInstance1, 'connection'));
    }

    /**
     * Test clearInstance method clears the database instance.
     *
     * @covers \App\services\database\DBConnection::clearInstance
     */
    public function testClearInstance(): void
    {
        $dbName = 'test_db';

        // Inject mocks
        $db = DBConnection::getInstance($dbName, false, $this->mysqliWrapperMock, $this->debugMock);

        // Clear the instance
        DBConnection::clearInstance($dbName);

        // Use ReflectionClass to access the static property
        $reflection = new \ReflectionClass(DBConnection::class);
        $instancesProperty = $reflection->getProperty('instances');
        $instancesProperty->setAccessible(true);
        $instances = $instancesProperty->getValue();

        // Assert that the instance is no longer in the pool
        $this->assertArrayNotHasKey($dbName, $instances);
    }

    /**
     * Test beginTransaction method starts a database transaction.
     *
     * @covers \App\services\database\DBConnection::beginTransaction
     */
    public function testBeginTransaction(): void
    {
        $db = $this->createMockedDB();

        // Call beginTransaction
        $db->beginTransaction();

        // Assert no exceptions are thrown
        $this->assertTrue(true);
    }

    /**
     * Test rollback method rolls back the transaction.
     *
     * @covers \App\services\database\DBConnection::rollback
     */
    /** WORKING **/
    public function testRollback(): void
    {
        $db = $this->createMockedDB();

        // Call rollback
        $db->rollback();

        // Assert no exceptions are thrown
        $this->assertTrue(true);
    }

    /**
     * Test executeQuery method executes a prepared statement.
     *
     * @covers \App\services\database\DBConnection::executeQuery
     */
    public function testExecuteQuerySelect(): void
    {
        $db = $this->createMockedDB();

        // Mock MySQLi prepared statement
        $mockResult = Mockery::mock('mysqli_result');
        $mockResult->shouldReceive('fetch_array')
            ->twice() // Adjust based on how many rows you expect
            ->andReturn(['id' => 1, 'name' => 'John Doe'], null); // First call returns data, second ends loop

        // Mock MySQLi statement object
        $this->mysqliStatementMock->shouldReceive('execute')->andReturn(true);
        $this->mysqliStatementMock->shouldReceive('get_result')->andReturn($mockResult);
        $this->mysqliStatementMock->shouldReceive('close')->once();

        // Mock MySQLi connection
        $this->mysqliWrapperMock->shouldReceive('prepare')
            ->with('SELECT * FROM test_table')
            ->andReturn($this->mysqliStatementMock);

        // Execute a query
        $result = $db->executeQuery('SELECT * FROM test_table');

        // Assert result is as expected
        $this->assertSame([['id' => 1, 'name' => 'John Doe']], $result);
    }

    /**
     * Test executeQuery method returns an empty array for no results.
     *
     * @covers \App\services\database\DBConnection::executeQuery
     */
    public function testExecuteQuerySelectNoResults(): void
    {
        $db = $this->createMockedDB();

        // Mock MySQLi prepared statement
        $mockResult = Mockery::mock('mysqli_result');
        $mockResult->shouldReceive('fetch_array')->andReturn(null); // No rows

        // Mock MySQLi statement object
        $this->mysqliStatementMock->shouldReceive('execute')->andReturn(true);
        $this->mysqliStatementMock->shouldReceive('get_result')->andReturn($mockResult);
        $this->mysqliStatementMock->shouldReceive('close')->once();

        // Mock MySQLi connection
        $this->mysqliWrapperMock->shouldReceive('prepare')
            ->with('SELECT * FROM empty_table')
            ->andReturn($this->mysqliStatementMock);

        // Execute a query
        $result = $db->executeQuery('SELECT * FROM empty_table');

        // Assert result is an empty array
        $this->assertSame([], $result);
    }

    /**
     * Test executeQuery method throws an exception when query execution fails.
     *
     * @covers \App\services\database\DBConnection::executeQuery
     */
    /**
     * Test executeQuery method throws an exception when query execution fails.
     *
     * This test ensures that the `executeQuery` method correctly handles
     * a failure during statement execution by throwing a `DatabaseException`.
     *
     * @covers \App\services\database\DBConnection::executeQuery
     *
     * @return void
     * @throws \App\exceptions\DatabaseException If the query execution fails.
     */
    public function testExecuteQueryFailsOnError(): void
    {
        $db = $this->createMockedDB();

        // Expect exception
        $this->expectException(DatabaseException::class);
        $this->expectExceptionMessage('Execute failed for query: SELECT * FROM test_table');

        // Mock MySQLi statement object
        $this->mysqliStatementMock->shouldReceive('execute')->andReturn(false);

        // Mock MySQLi connection
        $this->mysqliWrapperMock->shouldReceive('prepare')
            ->with('SELECT * FROM test_table')
            ->andReturn($this->mysqliStatementMock);

        // Execute a query
        $db->executeQuery('SELECT * FROM test_table');
    }

    /**
     * Test executeQuery method handles non-SELECT queries.
     *
     * @covers \App\services\database\DBConnection::executeQuery
     */
    public function testExecuteQueryNonSelect(): void
    {
        $db = $this->createMockedDB();

        // Mock the statement's behavior
        $this->mysqliStatementMock->shouldReceive('execute')->andReturn(true);
        $this->mysqliStatementMock->shouldReceive('close')->once();

        // Mock the wrapper's higher-level methods
        $this->mysqliWrapperMock->shouldReceive('getAffectedRows')
            ->once()
            ->andReturn(3); // Simulate 3 rows affected
        $this->mysqliWrapperMock->shouldReceive('bindParams')
            ->once()
            ->with($this->mysqliStatementMock, 'si', ['New Name', 1])
            ->andReturn(true); // Simulate bind_params

        // Mock the connection to return the statement
        $this->mysqliWrapperMock->shouldReceive('prepare')
            ->with('UPDATE test_table SET name = ? WHERE id = ?')
            ->andReturn($this->mysqliStatementMock);

        // Execute the non-SELECT query
        $result = $db->executeQuery(
            'UPDATE test_table SET name = ? WHERE id = ?',
            ['New Name', 1],
            'si'
        );

        // Assert result is as expected (3 rows affected)
        $this->assertSame(3, $result);
    }

    /**
     * Test executeQuery method handles non-SELECT queries.
     *
     * @covers \App\services\database\DBConnection::executeQuery
     */
    public function testExecuteQueryNonSelectNoRowsAffected(): void
    {
        $db = $this->createMockedDB();

        // Mock the statement's behavior
        $this->mysqliStatementMock->shouldReceive('execute')->andReturn(true);
        $this->mysqliStatementMock->shouldReceive('close')->once();

        // Mock the wrapper's higher-level methods
        $this->mysqliWrapperMock->shouldReceive('getAffectedRows')
            ->once()
            ->andReturn(0); // Simulate 0 rows affected
        $this->mysqliWrapperMock->shouldReceive('bindParams')
            ->once()
            ->with($this->mysqliStatementMock, 'si', ['New Name', 1])
            ->andReturn(true); // Simulate bind_params

        // Mock the connection to return the statement
        $this->mysqliWrapperMock->shouldReceive('prepare')
            ->with('UPDATE test_table SET name = ? WHERE id = ?')
            ->andReturn($this->mysqliStatementMock);

        // Execute the non-SELECT query
        $result = $db->executeQuery(
            'UPDATE test_table SET name = ? WHERE id = ?',
            ['New Name', 1],
            'si'
        );

        // Assert result is as expected (0 rows affected)
        $this->assertSame(0, $result);
    }

    /**
     * Test executeRawQuery method for SELECT query.
     *
     * @covers \App\services\database\DBConnection::executeRawQuery
     */
    public function testExecuteRawQuerySelect(): void
    {
        $db = $this->createMockedDB();

        // Mock MySQLi behavior
        $mockResult = Mockery::mock('mysqli_result');
        $mockResult->shouldReceive('fetch_assoc')->andReturn(['id' => 1, 'name' => 'John Doe'], null);
        $mockResult->shouldReceive('free')->once();

        // Mock MySQLi connection
        $this->mysqliWrapperMock->shouldReceive('query')->andReturn($mockResult);

        // Execute raw query
        $result = $db->executeRawQuery('SELECT * FROM test_table');

        // Assert the results match
        $this->assertSame([['id' => 1, 'name' => 'John Doe']], $result);
    }

    /**
     * Test executeRawQuery method for non-SELECT queries.
     *
     * @covers \App\services\database\DBConnection::executeRawQuery
     */
    public function testExecuteRawQueryNonSelect(): void
    {
        $db = $this->createMockedDB();

        // Mock MySQLi behavior
        $this->mysqliWrapperMock->shouldReceive('query')->andReturn(true);
        $this->mysqliWrapperMock->shouldReceive('getAffectedRows')->andReturn(2);

        // Execute raw query
        $result = $db->executeRawQuery('DELETE FROM test_table');

        // Assert the number of affected rows is returned
        $this->assertSame(2, $result);
    }

    /**
     * Test getAffectedRows method.
     *
     * @covers \App\services\database\DBConnection::getAffectedRows
     */
    public function testGetAffectedRows(): void
    {
        $db = $this->createMockedDB();

        // Mock MySQLi affected_rows
        $this->mysqliWrapperMock->shouldReceive('getAffectedRows')->andReturn(3);

        // Assert the number of affected rows is correct
        $this->assertSame(3, $db->getAffectedRows());
    }

    /**
     * Test getLastInsertId method.
     *
     * @covers \App\services\database\DBConnection::getLastInsertId
     */
    public function testGetLastInsertId(): void
    {
        $db = $this->createMockedDB();

        // Mock MySQLi insert_id
        $this->mysqliWrapperMock->shouldReceive('getLastInsertId')->andReturn(42);

        // Assert the last inserted ID is correct
        $this->assertSame(42, $db->getLastInsertId());
    }

    /**
     * Set up the test environment.
     *
     * Initializes the Mockery Debug, Config, and MySQLi instances.
     *
     * @return void
     */
    protected function setUp(): void
    {
        // Ensure parent setup runs if necessary
        parent::setUp();

        // Set up Config Mock with default values
        $configData = [
            'db_config' => [
                'test_db' => [
                    'host' => 'localhost',
                    'username' => 'root',
                    'password' => '',
                    'dbname' => 'test_db',
                ],
            ],
            'debug_config' => [
                'colors' => [
                    'default' => 'green',
                    'database' => 'red',
                ],
            ],
        ];
        $this->createConfigMock($configData);

        // Set up CustomDebug Mock
        $this->debugMock = $this->createCustomDebugMock('database', false, 0, 'red');

        // Mock CustomDebug debug() calls
        $this->mockDebug($this->debugMock, "Connected to database: test_db at localhost");
        $this->mockDebug($this->debugMock, "Starting transaction.");
        $this->mockDebug($this->debugMock, "Rolling back transaction.");
        $this->mockDebug($this->debugMock, "Preparing SQL: SELECT * FROM test_table");
        $this->mockDebug($this->debugMock, "Preparing SQL: SELECT * FROM empty_table");
        $this->mockDebug($this->debugMock, "Preparing SQL: UPDATE test_table SET name = ? WHERE id = ?");
        $this->mockDebug($this->debugMock, "Executing Raw SQL: SELECT * FROM test_table");
        $this->mockDebug($this->debugMock, "Executing Raw SQL: DELETE FROM test_table");
        $this->mockDebug($this->debugMock, "Executed query successfully.");
        $this->mockDebug($this->debugMock, "Query returned 0 rows.");
        $this->mockDebug($this->debugMock, "Query returned 1 rows.");
        $this->mockDebug($this->debugMock, "Query affected 0 rows.");
        $this->mockDebug($this->debugMock, "Query affected 2 rows.");
        $this->mockDebug($this->debugMock, "Query affected 3 rows.");
        $this->mockDebug($this->debugMock, "Database connection closed.");
        $this->mockDebug($this->debugMock, "Connection to database test_db has been closed.");

        // Mock CustomDebug fail() calls and exception throws
        // For testThrowsExceptionForMissingConfiguration():
        $errorMsg = "Database configuration for 'invalid_db' not found.";
        $this->mockFail(
            $this->debugMock,
            'failDatabase',
            $errorMsg,
            new DatabaseException($errorMsg)
        );
        // For testConstructorThrowsExceptionForConnectionFailure():
        $errorMsg = 'Database connection failed.';
        $this->mockFail(
            $this->debugMock,
            'failDatabase',
            $errorMsg,
            new DatabaseException($errorMsg)
        );
        // For testEnsureConnectionThrowsException():
        $errorMsg = 'Database connection is not established.';
        $this->mockFail(
            $this->debugMock,
            'failDatabase',
            $errorMsg,
            new DatabaseException($errorMsg)
        );
        // For testExecuteQueryFailsOnError():
        $errorMsg = 'Execute failed for query: SELECT * FROM test_table';
        $this->mockFail(
            $this->debugMock,
            'failDatabase',
            $errorMsg,
            new DatabaseException($errorMsg)
        );

        // MySQLiWrapper mock
        $this->mysqliWrapperMock = $this->createMySQLiWrapperMock();

        // Set up MySQLi Statement Mock
        $this->mysqliStatementMock = Mockery::mock('mysqli_stmt');
    }

    /**
     * Cleans up after each test, closing Mockery expectations.
     *
     * Ensures Mockery expectations are met and prevents leaks between tests.
     *
     * @return void
     */
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Creates a mocked DBConnection instance with injected Debug and MySQLi mocks.
     *
     * @return DBConnection Mocked DBConnection instance.
     */
    private function createMockedDB(): DBConnection
    {
        $dbName = 'test_db';

        // Set up the DB
        return DBConnection::getInstance($dbName, false, $this->mysqliWrapperMock, $this->debugMock);
    }
}
