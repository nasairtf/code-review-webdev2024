<?php

declare(strict_types=1);

namespace Tests\classes\services\database;

use Mockery;
use PHPUnit\Framework\TestCase;
use Tests\utilities\PrivatePropertyTrait;
use App\services\database\DBConnection;

/**
 * Unit tests for the DBConnection class.
 *
 * @covers \App\services\database\DBConnection
 */
class DBConnectionTest extends TestCase
{
    use PrivatePropertyTrait;

    /**
     * @var Mockery\MockInterface|Debug Mocked Debug instance.
     */
    private $debugMock;

    /**
     * Set up the test environment.
     *
     * Initializes the Mockery Debug instance.
     */
    protected function setUp(): void
    {
        $this->debugMock = Mockery::mock(\App\core\common\Debug::class);
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
    }

    /**
     * Test getInstance method returns a singleton instance.
     *
     * @covers \App\services\database\DBConnection::getInstance
     */
    public function testGetInstanceReturnsSingleton(): void
    {
        $dbName = 'test_db';
        $config = [
            $dbName => [
                'host' => 'localhost',
                'username' => 'root',
                'password' => '',
                'dbname' => $dbName,
            ],
        ];

        // Mock the DBConnection configuration file
        $this->mockGlobalConfig($config);

        // Create the first instance
        $dbInstance1 = DBConnection::getInstance($dbName);

        // Retrieve the same instance
        $dbInstance2 = DBConnection::getInstance($dbName);

        // Assert the two instances are the same
        $this->assertSame($dbInstance1, $dbInstance2);
    }

    /**
     * Test that a database connection can be established successfully.
     *
     * @covers \App\services\database\DBConnection::getInstance
     */
    public function testConnectionEstablishment(): void
    {
        $dbName = 'test_db';
        $config = [
            $dbName => [
                'host' => 'localhost',
                'username' => 'root',
                'password' => '',
                'dbname' => $dbName,
            ],
        ];

        // Mock the DBConnection configuration file
        $this->mockGlobalConfig($config);

        // Mock Debug behavior
        $this->debugMock->shouldReceive('debug')->once();

        // Inject the Debug mock into DBConnection
        $db = DBConnection::getInstance($dbName);
        $this->setPrivateProperty($db, 'debug', $this->debugMock);

        // Assert connection is established
        $this->assertNotNull($this->getPrivateProperty($db, 'connection'));
    }

    /**
     * Test beginTransaction method starts a database transaction.
     *
     * @covers \App\services\database\DBConnection::beginTransaction
     */
    public function testBeginTransaction(): void
    {
        $db = $this->createMockedDB();

        // Mock Debug behavior
        $this->debugMock->shouldReceive('debug')->with('Starting transaction.')->once();

        // Call beginTransaction
        $db->beginTransaction();

        // Assertions (connection method called)
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

        // Mock Debug behavior
        $this->debugMock->shouldReceive('debug')->with('Database connection closed.')->once();

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

        // Mock Debug behavior
        $this->debugMock->shouldReceive('debug')->with(Mockery::any())->atLeast()->once();

        // Mock MySQLi behavior
        $mockMysqli = Mockery::mock('mysqli');
        $mockStmt = Mockery::mock('mysqli_stmt');

        $mockMysqli->shouldReceive('prepare')->andReturn($mockStmt);
        $mockStmt->shouldReceive('execute')->andReturn(true);
        $mockStmt->shouldReceive('get_result')->andReturn(null);
        $mockStmt->shouldReceive('close')->once();

        $this->setPrivateProperty($db, 'connection', $mockMysqli);

        // Execute a query
        $result = $db->executeQuery('SELECT * FROM test_table');

        // Assert result is as expected
        $this->assertNull($result);
    }

    /**
     * Helper method to mock global configuration.
     *
     * @param array $config Mock configuration data.
     */
    private function mockGlobalConfig(array $config): void
    {
        define('CONFIG_PATH', __DIR__ . '/');
        file_put_contents(CONFIG_PATH . 'db_config.php', '<?php return ' . var_export($config, true) . ';');
    }

    /**
     * Creates a mocked DBConnection instance with injected Debug mock.
     *
     * @return DBConnection Mocked DBConnection instance.
     */
    private function createMockedDB(): DBConnection
    {
        $db = DBConnection::getInstance('test_db');
        $this->setPrivateProperty($db, 'debug', $this->debugMock);
        return $db;
    }
}
