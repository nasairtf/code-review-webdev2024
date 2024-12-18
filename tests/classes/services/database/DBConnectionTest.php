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
 * List of method tests:
 *
 * testGetInstanceReturnsSingletonInstance [DONE]
 * testGetInstanceReplacesConnectionDuringTesting [DONE]
 * testGetInstanceThrowsExceptionOnMissingConfig [DONE]
 * testGetInstanceHandlesConnectionFailure [DONE]
 * testClearInstanceClosesConnectionAndRemovesInstance [DONE]
 * testClearInstanceDoesNothingIfInstanceDoesNotExist [INDIRECTLY TESTED]
 * testBeginTransactionStartsTransactionSuccessfully [DONE]
 * testBeginTransactionThrowsExceptionOnInvalidConnection [INDIRECTLY TESTED]
 * testCommitCommitsTransactionSuccessfully [DONE]
 * testCommitThrowsExceptionOnInvalidConnection [INDIRECTLY TESTED]
 * testRollbackRollsBackTransactionSuccessfully [DONE]
 * testRollbackThrowsExceptionOnInvalidConnection [INDIRECTLY TESTED]
 * testCloseConnectionClosesConnectionSuccessfully [DONE]
 * testCloseConnectionDoesNothingIfConnectionIsNull [DONE]
 * testExecuteQueryReturnsResultForSelectQuery [DONE]
 * testExecuteQueryReturnsNoResultForSelectQuery [DONE]
 * testExecuteQueryReturnsAffectedRowsForNonSelectQuery [DONE]
 * testExecuteQueryThrowsExceptionOnPrepareFailure [DONE]
 * testExecuteQueryThrowsExceptionOnBindParamsFailure [DONE]
 * testExecuteQueryThrowsExceptionOnExecuteFailure [DONE]
 * testExecuteRawQueryReturnsResultForSelectQuery [DONE]
 * testExecuteRawQueryReturnsAffectedRowsForNonSelectQuery [DONE]
 * testExecuteRawQueryThrowsExceptionOnExecutionFailure [DONE]
 * testGetAffectedRowsReturnsCorrectRowCount [DONE]
 * testGetLastInsertIdReturnsCorrectInsertId [DONE]
 * testEnsureConnectionThrowsExceptionWhenConnectionIsInvalid [DONE]
 *
 * @covers \App\services\database\DBConnection
 */
class DBConnectionTest extends TestCase
{
    use PrivatePropertyTrait;
    use ConfigMockTrait;
    use CustomDebugMockTrait;
    use MySQLiWrapperMockTrait;

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
     * TEST METHOD 1: __construct [PRIVATE]
     *
     * Tested indirectly via getInstance tests.
     */

    /**
     * TEST METHOD 2: __destruct
     *
     * Tested indirectly via clearInstance tests.
     */

    /**
     * TEST METHOD 3: getInstance
     */

    /**
     * Test getInstance method returns a singleton instance.
     *
     * @covers \App\services\database\DBConnection::__construct
     * @covers \App\services\database\DBConnection::getInstance
     */
    public function testGetInstanceReturnsSingletonInstance(): void
    {
        $dbName = 'test_db';

        // Retrieve the singleton instance
        $dbInstance1 = DBConnection::getInstance($dbName, false, $this->mysqliWrapperMock, $this->debugMock);

        // Retrieve the same instance again
        $dbInstance2 = DBConnection::getInstance($dbName, false, $this->mysqliWrapperMock, $this->debugMock);

        // Assert the two instances are the same
        $this->assertSame($dbInstance1, $dbInstance2);
        $this->assertDependency($this->mysqliWrapperMock, 'connection', $dbInstance1);
    }

    /**
     * Test that a database connection can be established successfully.
     *
     * @covers \App\services\database\DBConnection::__construct
     * @covers \App\services\database\DBConnection::getInstance
     */
    public function testGetInstanceReplacesConnectionDuringTesting(): void
    {
        $dbName = 'test_db';

        // Inject mocks
        $db = DBConnection::getInstance($dbName, false, $this->mysqliWrapperMock, $this->debugMock);

        // Assert connection is established and matches mysqliMock
        $this->assertDependency($this->mysqliWrapperMock, 'connection', $db);
    }

    /**
     * Test constructor throws an exception for connection failure.
     *
     * @covers \App\services\database\DBConnection::__construct
     * @covers \App\services\database\DBConnection::getInstance
     */
    public function testGetInstanceHandlesConnectionFailure(): void
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
     * Test that an exception is thrown if the database configuration is missing.
     *
     * @covers \App\services\database\DBConnection::__construct
     * @covers \App\services\database\DBConnection::getInstance
     */
    public function testGetInstanceThrowsExceptionOnMissingConfig(): void
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
     * TEST METHOD 4: clearInstance
     *
     * This test is unneeded due to ensureConnection() testing via its own unit test:
     * - testClearInstanceDoesNothingIfInstanceDoesNotExist
     */

    /**
     * Test clearInstance method clears the database instance.
     *
     * @covers \App\services\database\DBConnection::clearInstance
     */
    public function testClearInstanceClosesConnectionAndRemovesInstance(): void
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
     * TEST METHOD 5: beginTransaction
     *
     * This test is unneeded due to ensureConnection() testing via its own unit test:
     * - testBeginTransactionThrowsExceptionOnInvalidConnection
     */

    /**
     * Test beginTransaction method starts a database transaction.
     *
     * @covers \App\services\database\DBConnection::beginTransaction
     */
    public function testBeginTransactionStartsTransactionSuccessfully(): void
    {
        $db = $this->createMockedDB();

        // Call beginTransaction
        $db->beginTransaction();

        // Assert no exceptions are thrown
        $this->assertTrue(true);
    }

    /**
     * TEST METHOD 6: commit
     *
     * This test is unneeded due to ensureConnection() testing via its own unit test:
     * - testCommitThrowsExceptionOnInvalidConnection
     */

    /**
     * Test commit method commits a database transaction.
     *
     * @covers \App\services\database\DBConnection::commit
     */
    public function testCommitCommitsTransactionSuccessfully(): void
    {
        $db = $this->createMockedDB();

        // Call commit
        $db->commit();

        // Assert no exceptions are thrown
        $this->assertTrue(true);
    }

    /**
     * TEST METHOD 7: rollback
     *
     * This test is unneeded due to ensureConnection() testing via its own unit test:
     * - testRollbackThrowsExceptionOnInvalidConnection
     */

    /**
     * Test rollback method rolls back the transaction.
     *
     * @covers \App\services\database\DBConnection::rollback
     */
    /** WORKING **/
    public function testRollbackRollsBackTransactionSuccessfully(): void
    {
        $db = $this->createMockedDB();

        // Call rollback
        $db->rollback();

        // Assert no exceptions are thrown
        $this->assertTrue(true);
    }

    /**
     * TEST METHOD 8: closeConnection
     */

    /**
     * Test closeConnection method closes the database connection.
     *
     * @covers \App\services\database\DBConnection::closeConnection
     */
    /** WORKING **/
    public function testCloseConnectionClosesConnectionSuccessfully(): void
    {
        $db = $this->createMockedDB();

        // Close the connection
        $db->closeConnection();

        // Assert connection is null
        $this->assertNull($this->getPrivateProperty($db, 'connection'));
    }

    /**
     * Test closeConnection method does nothing if the connection is already null.
     *
     * @covers \App\services\database\DBConnection::closeConnection
     */
    /** WORKING **/
    public function testCloseConnectionDoesNothingIfConnectionIsNull(): void
    {
        $db = $this->createMockedDB();
        $db->closeConnection();

        // Close the connection again
        $db->closeConnection();

        // Assert no exceptions are thrown
        $this->assertTrue(true);
    }

    /**
     * TEST METHOD 9: executeQuery
     *
     * - testExecuteQueryThrowsExceptionOnPrepareFailure
     * - testExecuteQueryThrowsExceptionOnBindParamsFailure
     */

    /**
     * Test executeQuery method executes a prepared statement.
     *
     * @covers \App\services\database\DBConnection::executeQuery
     */
    public function testExecuteQueryReturnsResultForSelectQuery(): void
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
    public function testExecuteQueryReturnsNoResultForSelectQuery(): void
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
     * Test executeQuery method handles non-SELECT queries.
     *
     * @covers \App\services\database\DBConnection::executeQuery
     */
    public function testExecuteQueryReturnsAffectedRowsForNonSelectQuery(): void
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
     * Test executeQuery method throws an exception when query preparation fails.
     *
     * This test ensures that the `executeQuery` method correctly handles
     * a failure during statement preparation by throwing a `DatabaseException`.
     *
     * @covers \App\services\database\DBConnection::executeQuery
     *
     * @return void
     * @throws \App\exceptions\DatabaseException If the query preparation fails.
     */
    public function testExecuteQueryThrowsExceptionOnPrepareFailure(): void
    {
        $db = $this->createMockedDB();

        // Expect exception
        $this->expectException(DatabaseException::class);
        $this->expectExceptionMessage('Prepare failed for query: SELECT * FROM test_table');

        // Mock MySQLi connection to simulate preparation failure
        $this->mysqliWrapperMock->shouldReceive('prepare')
            ->with('SELECT * FROM test_table')
            ->andReturn(false);

        // Execute a query
        $db->executeQuery('SELECT * FROM test_table');
    }

    /**
     * Test executeQuery method throws an exception when parameter binding fails.
     *
     * This test ensures that the `executeQuery` method correctly handles
     * a failure during parameter binding by throwing a `DatabaseException`.
     *
     * @covers \App\services\database\DBConnection::executeQuery
     *
     * @return void
     * @throws \App\exceptions\DatabaseException If the parameter binding fails.
     */
    public function testExecuteQueryThrowsExceptionOnBindParamsFailure(): void
    {
        $db = $this->createMockedDB();

        // Expect exception
        $this->expectException(DatabaseException::class);
        $this->expectExceptionMessage('Failed to bind parameters for query: SELECT * FROM test_table');

        // Mock MySQLi statement object
        $this->mysqliStatementMock->shouldReceive('bind_param')
            ->andReturn(false); // Simulate failure in bind_param

        // Mock MySQLi connection
        $this->mysqliWrapperMock->shouldReceive('prepare')
            ->with('SELECT * FROM test_table')
            ->andReturn($this->mysqliStatementMock);

        // Mock the bindParams method in the wrapper
        $this->mysqliWrapperMock->shouldReceive('bindParams')
            ->with($this->mysqliStatementMock, 'i', [1])
            ->andReturn(false); // Simulate binding failure

        // Execute a query
        $db->executeQuery('SELECT * FROM test_table', [1], 'i');
    }

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
    public function testExecuteQueryThrowsExceptionOnExecuteFailure(): void
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
     * TEST METHOD 10: executeRawQuery
     */

    /**
     * Test executeRawQuery method for SELECT query.
     *
     * @covers \App\services\database\DBConnection::executeRawQuery
     */
    public function testExecuteRawQueryReturnsResultForSelectQuery(): void
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
    public function testExecuteRawQueryReturnsAffectedRowsForNonSelectQuery(): void
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
     * Test executeRawQuery method throws an exception when query execution fails.
     *
     * This test ensures that the `executeRawQuery` method correctly handles
     * a failure during statement execution by throwing a `DatabaseException`.
     *
     * @covers \App\services\database\DBConnection::executeRawQuery
     *
     * @return void
     * @throws \App\exceptions\DatabaseException If the query execution fails.
     */
    public function testExecuteRawQueryThrowsExceptionOnExecutionFailure(): void
    {
        $db = $this->createMockedDB();

        // Expect exception
        $this->expectException(DatabaseException::class);
        $this->expectExceptionMessage('Query failed: SELECT * FROM test_table');

        // Mock MySQLi statement object
        $this->mysqliWrapperMock->shouldReceive('query')->andReturn(false);

        // Execute a query
        $db->executeRawQuery('SELECT * FROM test_table');
    }

    /**
     * TEST METHOD 11: getAffectedRows
     */

    /**
     * Test getAffectedRows method.
     *
     * @covers \App\services\database\DBConnection::getAffectedRows
     */
    public function testGetAffectedRowsReturnsCorrectRowCount(): void
    {
        $db = $this->createMockedDB();

        // Mock MySQLi affected_rows
        $this->mysqliWrapperMock->shouldReceive('getAffectedRows')->andReturn(3);

        // Assert the number of affected rows is correct
        $this->assertSame(3, $db->getAffectedRows());
    }

    /**
     * TEST METHOD 12: getLastInsertId
     */

    /**
     * Test getLastInsertId method.
     *
     * @covers \App\services\database\DBConnection::getLastInsertId
     */
    public function testGetLastInsertIdReturnsCorrectInsertId(): void
    {
        $db = $this->createMockedDB();

        // Mock MySQLi insert_id
        $this->mysqliWrapperMock->shouldReceive('getLastInsertId')->andReturn(42);

        // Assert the last inserted ID is correct
        $this->assertSame(42, $db->getLastInsertId());
    }

    /**
     * TEST METHOD 13: ensureConnection [PRIVATE]
     */

    /**
     * Test ensureConnection method throws an exception for invalid connection.
     *
     * @covers \App\services\database\DBConnection::ensureConnection
     */
    public function testEnsureConnectionThrowsExceptionWhenConnectionIsInvalid(): void
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
     * HELPER METHODS -- TEST SETUP AND/OR CLEANUP
     */

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
        $this->mockDebug($this->debugMock, "Committing transaction.");
        $this->mockDebug($this->debugMock, "Rolling back transaction.");
        $this->mockDebug($this->debugMock, "Preparing SQL: SELECT * FROM test_table");
        $this->mockDebug($this->debugMock, "Preparing SQL: SELECT * FROM empty_table");
        $this->mockDebug($this->debugMock, "Preparing SQL: UPDATE test_table SET name = ? WHERE id = ?");
        $this->mockDebug($this->debugMock, "Prepare failed for query: SELECT * FROM test_table");
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
        // For testGetInstanceThrowsExceptionOnMissingConfig():
        $errorMsg = "Database configuration for 'invalid_db' not found.";
        $this->mockFail(
            $this->debugMock,
            'failDatabase',
            $errorMsg,
            new DatabaseException($errorMsg)
        );
        // For testGetInstanceHandlesConnectionFailure():
        $errorMsg = 'Database connection failed.';
        $this->mockFail(
            $this->debugMock,
            'failDatabase',
            $errorMsg,
            new DatabaseException($errorMsg)
        );
        // For testEnsureConnectionThrowsExceptionWhenConnectionIsInvalid():
        $errorMsg = 'Database connection is not established.';
        $this->mockFail(
            $this->debugMock,
            'failDatabase',
            $errorMsg,
            new DatabaseException($errorMsg)
        );
        // For testExecuteQueryThrowsExceptionOnExecuteFailure():
        $errorMsg = 'Execute failed for query: SELECT * FROM test_table';
        $this->mockFail(
            $this->debugMock,
            'failDatabase',
            $errorMsg,
            new DatabaseException($errorMsg)
        );
        // For testExecuteRawQueryThrowsExceptionOnExecutionFailure():
        $errorMsg = 'Query failed: SELECT * FROM test_table';
        $this->mockFail(
            $this->debugMock,
            'failDatabase',
            $errorMsg,
            new DatabaseException($errorMsg)
        );
        // For testExecuteQueryThrowsExceptionOnPrepareFailure():
        $errorMsg = 'Prepare failed for query: SELECT * FROM test_table';
        $this->mockFail(
            $this->debugMock,
            'failDatabase',
            $errorMsg,
            new DatabaseException($errorMsg)
        );
        // For testExecuteQueryThrowsExceptionOnBindParamsFailure():
        $errorMsg = 'Failed to bind parameters for query: SELECT * FROM test_table';
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
