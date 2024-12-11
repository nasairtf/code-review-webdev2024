<?php

declare(strict_types=1);

namespace Tests\classes\services\database;

use Mockery;
use PHPUnit\Framework\TestCase;
use Tests\utilities\PrivatePropertyTrait;
use App\services\database\DBConnection;
use App\core\common\Config;
use App\exceptions\DatabaseException;

/**
 * Unit tests for the DBConnection class.
 *
 * @covers \App\services\database\DBConnection
 */
class DBConnectionTest extends TestCase
{
    use PrivatePropertyTrait;

    /**
     * Mock instance of Debug.
     *
     * @var Mockery\MockInterface
     */
    private $debugMock;

    /**
     * Mock instance of Config.
     *
     * @var Mockery\MockInterface
     */
    private $configMock;

    /**
     * Mock instance of MySQLi.
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
     * Test getInstance method returns a singleton instance.
     *
     * @covers \App\services\database\DBConnection::getInstance
     */
    public function testGetInstanceReturnsSingleton(): void
    {
        $dbName = 'test_db';

        // Retrieve the singleton instance
        $dbInstance1 = DBConnection::getInstance($dbName, true, $this->mysqliMock);

        // Retrieve the same instance again
        $dbInstance2 = DBConnection::getInstance($dbName, true, $this->mysqliMock);

        // Assert the two instances are the same
        $this->assertSame($dbInstance1, $dbInstance2);
        $this->assertSame($this->mysqliMock, $this->getPrivateProperty($dbInstance1, 'connection'));
    }

    /**
     * Test that an exception is thrown if the database configuration is missing.
     *
     * @covers \App\services\database\DBConnection::getInstance
     */
    public function testThrowsExceptionForMissingConfiguration(): void
    {
        // Mock Config to return an empty configuration
        $this->configMock->shouldReceive('get')
            ->with('db_config')
            ->andReturn([]);

        $this->expectException(DatabaseException::class);
        $this->expectExceptionMessage("Database configuration for 'invalid_db' not found.");

        DBConnection::getInstance('invalid_db', false, $this->mysqliMock);
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
        $db = DBConnection::getInstance($dbName, true, $this->mysqliMock);

        // Assert connection is established and matches mysqliMock
        $this->assertSame($this->mysqliMock, $this->getPrivateProperty($db, 'connection'));
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
     * Test closeConnection method closes the database connection.
     *
     * @covers \App\services\database\DBConnection::closeConnection
     */
    public function testCloseConnection(): void
    {
        $db = $this->createMockedDB();

        // Close the connection
        $db->closeConnection();

        // Assert connection is null
        $this->assertNull($this->getPrivateProperty($db, 'connection'));
    }

    /**
     * Test executeQuery method executes a prepared statement.
     *
     * @covers \App\services\database\DBConnection::executeQuery
     */
    public function testExecuteQuery(): void
    {
        $db = $this->createMockedDB();

        // Mock MySQLi prepared statement
        $mockResult = Mockery::mock('mysqli_result');
        $mockResult->shouldReceive('fetch_array')
            ->twice() // Adjust based on how many rows you expect
            ->andReturn(['id' => 1, 'name' => 'John Doe'], null); // First call returns data, second ends loop

        // Mock MySQLi statement object
        $this->mysqliStmtMock->shouldReceive('execute')->andReturn(true);
        $this->mysqliStmtMock->shouldReceive('get_result')->andReturn($mockResult);
        $this->mysqliStmtMock->shouldReceive('close')->once();

        // Mock MySQLi connection
        $this->mysqliMock->shouldReceive('prepare')
            ->with('SELECT * FROM test_table')
            ->andReturn($this->mysqliStmtMock);

        // Execute a query
        $result = $db->executeQuery('SELECT * FROM test_table');

        // Assert result is as expected
        $this->assertSame([['id' => 1, 'name' => 'John Doe']], $result);
    }

    public function testExecuteQueryFailsOnError(): void
    {
        $db = $this->createMockedDB();

        $this->expectException(DatabaseException::class);
        $this->expectExceptionMessage('Execute failed for query: SELECT * FROM test_table');

        // Mock MySQLi statement object
        $this->mysqliStmtMock->shouldReceive('execute')->andReturn(false);

        // Mock MySQLi connection
        $this->mysqliMock->shouldReceive('prepare')
            ->with('SELECT * FROM test_table')
            ->andReturn($this->mysqliStmtMock);

        // Execute a query
        $db->executeQuery('SELECT * FROM test_table');
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

        $this->mysqliMock->shouldReceive('query')->andReturn($mockResult);

        // Execute raw query
        $result = $db->executeRawQuery('SELECT * FROM test_table');

        // Assert the results match
        $this->assertSame([['id' => 1, 'name' => 'John Doe']], $result);
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

        // Mock Config
        $this->configMock = Mockery::mock('alias:' . Config::class);

        // Mock db_config
        $this->configMock->shouldReceive('get')
            ->with('db_config')
            ->andReturn([
                'test_db' => [
                    'host' => 'localhost',
                    'username' => 'root',
                    'password' => '',
                    'dbname' => 'test_db',
                ],
            ]);

        // Mock debug_config for colors
        $this->configMock->shouldReceive('get')
            ->with('debug_config', 'colors')
            ->andReturn([
                'default' => 'green',
                'database' => 'red',
            ]);

        // Mock Debug
        $this->debugMock = Mockery::mock(\App\core\common\CustomDebug::class);

        // Mock MySQLi
        $this->mysqliMock = Mockery::mock('mysqli');
        $this->mysqliMock->shouldReceive('connect_error')->andReturnNull();
        $this->mysqliMock->shouldReceive('close')->andReturnTrue();
        $this->mysqliMock->shouldReceive('begin_transaction')->andReturnTrue();
        $this->mysqliMock->shouldReceive('commit')->andReturnTrue();
        $this->mysqliMock->shouldReceive('rollback')->andReturnTrue();

        // Mock MySQLi Statement
        $this->mysqliStmtMock = Mockery::mock('mysqli_stmt');
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
        // Ensure debugMode is false to avoid interference with testing.
        return DBConnection::getInstance('test_db', false, $this->mysqliMock);
    }
}
