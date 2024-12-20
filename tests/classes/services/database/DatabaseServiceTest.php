<?php

declare(strict_types=1);

namespace Tests\classes\services\database;

use Mockery;
use PHPUnit\Framework\TestCase;
use Tests\utilities\CustomDebugMockTrait;
use Tests\utilities\DBConnectionMockTrait;
use Tests\utilities\PrivatePropertyTrait;
use Tests\classes\services\database\TestDatabaseService;
use App\services\database\DatabaseService;
use App\exceptions\DatabaseException;

/**
 * Unit tests for the DatabaseService class.
 *
 * This test suite validates the behavior of the DatabaseService class by mocking
 * dependencies like Debug and DBConnection classes. Mockery is used for all mocking.
 *
 * List of method tests:
 *
 * Constructor Tests:
 * - testConstructorInitializesDebugAndDBConnection [DONE]
 * Transaction Handling:
 * - testStartTransactionBeginsTransaction [DONE]
 * - testStartTransactionThrowsExceptionOnFailure [DONE]
 * - testCommitTransactionCommitsTransaction [DONE]
 * - testCommitTransactionThrowsExceptionOnFailure [DONE]
 * - testRollbackTransactionRollsBackTransaction [DONE]
 * - testRollbackTransactionThrowsExceptionOnFailure [DONE]
 * Read Query Handling:
 * - testFetchDataWithQueryReturnsResults [DONE]
 * - testFetchDataWithQueryThrowsExceptionOnEmptyResults [DONE]
 * - testExecuteSelectQueryReturnsResults [DONE]
 * - testExecuteSelectQueryThrowsExceptionOnFailure [DONE]
 * - testEnsureNotEmptyPassesForNonEmptyResults [DONE]
 * - testEnsureNotEmptyThrowsExceptionOnEmptyResults [DONE]
 * Write Query Handling:
 * - testModifyDataWithQueryReturnsAffectedRows [DONE]
 * - testModifyDataWithQueryThrowsExceptionOnUnexpectedRowCount [DONE]
 * - testExecuteUpdateQueryReturnsAffectedRows [DONE]
 * - testExecuteUpdateQueryThrowsExceptionOnFailure [DONE]
 * - testEnsureValidRowCountPassesForExpectedRowCount [DONE]
 * - testEnsureValidRowCountThrowsExceptionOnZeroRowCount [DONE]
 * - testEnsureValidRowCountThrowsExceptionOnUnexpectedRowCount [DONE]
 * Helper Methods:
 * - testGetSortStringReturnsASCForTrue [DONE]
 * - testGetSortStringReturnsDESCForFalse [DONE]
 *
 * @covers \App\services\database\DatabaseService
 */
class DatabaseServiceTest extends TestCase
{
    use PrivatePropertyTrait;
    use CustomDebugMockTrait;
    use DBConnectionMockTrait;

    /**
     * Mock instance of CustomDebug.
     *
     * @var Mockery\MockInterface
     */
    private $debugMock;

    /**
     * Mock instance of DBConnection.
     *
     * @var Mockery\MockInterface
     */
    private $dbMock;

    /**
     * Instance of TestDatabaseService.
     *
     * @var TestDatabaseService
     */
    private $service;

    /**
     * TEST METHOD 1: __construct
     */

    /**
     * Tests the constructor initializes CustomDebug and DBConnection instances correctly.
     *
     * @covers \App\services\database\DatabaseService::__construct
     *
     * @return void
     */
    public function testConstructorInitializesDependencies(): void
    {
        // Arrange
        $dbMock = $this->dbMock; // DBConnection mock
        $debugMock = $this->debugMock; // CustomDebug mock

        // Act
        $service = new TestDatabaseService('test_db', false, $dbMock, $debugMock);

        // Assert
        $this->assertInstanceOf(TestDatabaseService::class, $service);
        $this->assertDependency($dbMock, 'db', $service);
        $this->assertDependency($debugMock, 'debug', $service);
    }

    /**
     * TEST METHOD 2: startTransaction [PROTECTED]
     *
     */

    /**
     * Tests startTransaction() starts a transaction.
     *
     * @covers \App\services\database\DatabaseService::startTransaction
     *
     * @return void
     */
    public function testStartTransactionBeginsTransaction()
    {
        // Mock the DBConnection method(s) and expected return(s)
        $this->dbMock->shouldReceive('beginTransaction')->andReturnNull()->once();

        // Call the method under test
        $this->service->startTransactionProxy();

        // Assert no exceptions are thrown
        $this->assertTrue(true);
    }

    /**
     * Tests startTransaction() throws an exception when the transaction fails to begin.
     *
     * @covers \App\services\database\DatabaseService::startTransaction
     *
     * @return void
     */
    public function testStartTransactionThrowsExceptionOnFailure(): void
    {
        // Mock the DBConnection method to throw an exception
        $this->dbMock->shouldReceive('beginTransaction')
            ->andThrow(new \Exception('Transaction failed to start'));

        // Expect exception
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Transaction failed to start');

        // Call the method under test
        $this->service->startTransactionProxy();
    }

    /**
     * TEST METHOD 3: commitTransaction [PROTECTED]
     *
     */

    /**
     * Tests commitTransaction() commits a transaction.
     *
     * @covers \App\services\database\DatabaseService::commitTransaction
     *
     * @return void
     */
    public function testCommitTransactionCommitsTransaction()
    {
        // Mock the DBConnection method(s) and expected return(s)
        $this->dbMock->shouldReceive('commit')->andReturnNull()->once();

        // Call the method under test
        $this->service->commitTransactionProxy();

        // Assert no exceptions are thrown
        $this->assertTrue(true);
    }

    /**
     * Tests commitTransaction() throws an exception when the transaction commit fails.
     *
     * @covers \App\services\database\DatabaseService::commitTransaction
     *
     * @return void
     */
    public function testCommitTransactionThrowsExceptionOnFailure(): void
    {
        // Mock the DBConnection method to throw an exception
        $this->dbMock->shouldReceive('commit')
            ->andThrow(new \Exception('Transaction commit failed'));

        // Expect exception
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Transaction commit failed');

        // Call the method under test
        $this->service->commitTransactionProxy();
    }

    /**
     * TEST METHOD 4: rollbackTransaction [PROTECTED]
     *
     */

    /**
     * Tests rollbackTransaction() rolls back a transaction.
     *
     * @covers \App\services\database\DatabaseService::rollbackTransaction
     *
     * @return void
     */
    public function testRollbackTransactionRollsBackTransaction()
    {
        // Mock the DBConnection method(s) and expected return(s)
        $this->dbMock->shouldReceive('rollback')->andReturnNull()->once();

        // Call the method under test
        $this->service->rollbackTransactionProxy();

        // Assert no exceptions are thrown
        $this->assertTrue(true);
    }

    /**
     * Tests rollbackTransaction() throws an exception when the transaction rollback fails.
     *
     * @covers \App\services\database\DatabaseService::rollbackTransaction
     *
     * @return void
     */
    public function testRollbackTransactionThrowsExceptionOnFailure(): void
    {
        // Mock the DBConnection method to throw an exception
        $this->dbMock->shouldReceive('rollback')
            ->andThrow(new \Exception('Transaction rollback failed'));

        // Expect exception
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Transaction rollback failed');

        // Call the method under test
        $this->service->rollbackTransactionProxy();
    }

    /**
     * TEST METHOD 5: fetchDataWithQuery [PROTECTED]
     */

    /**
     * Tests fetchDataWithQuery() returns results for a valid query.
     *
     * @covers \App\services\database\DatabaseService::fetchDataWithQuery
     *
     * @return void
     */
    public function testFetchDataWithQueryReturnsResults(): void
    {
        // Define the test data
        $sql = 'SELECT * FROM test_table WHERE id = ?';
        $params = [1];
        $types = 'i';
        $mockResults = [['id' => 1, 'name' => 'John Doe']];

        // Mock the DBConnection method(s) and expected return(s)
        $this->dbMock->shouldReceive('executeQuery')
            ->with($sql, $params, $types, MYSQLI_ASSOC)
            ->andReturn($mockResults);

        // Mock CustomDebug behavior
        $this->mockDebug($this->debugMock, "SQL: {$sql}");
        $this->debugMock->shouldReceive('debugVariable')->with($params, 'Params')->once();
        $this->debugMock->shouldReceive('debugVariable')->with($mockResults, 'Query [SELECT] Results')->once();

        // Call the method under test
        $results = $this->service->fetchDataWithQueryProxy($sql, $params, $types, 'No data found');

        // Assert the results match
        $this->assertSame($mockResults, $results);
    }

    /**
     * Tests fetchDataWithQuery() throws an exception for empty results.
     *
     * @covers \App\services\database\DatabaseService::fetchDataWithQuery
     *
     * @return void
     */
    public function testFetchDataWithQueryThrowsExceptionOnEmptyResults(): void
    {
        // Define the test data
        $sql = 'SELECT * FROM test_table WHERE id = ?';
        $params = [1];
        $types = 'i';

        // Mock the DBConnection method(s) and expected return(s)
        $this->dbMock->shouldReceive('executeQuery')
            ->with($sql, $params, $types, MYSQLI_ASSOC)
            ->andReturn([]);

        // Mock CustomDebug behavior
        $this->mockDebug($this->debugMock, "SQL: {$sql}");
        $this->debugMock->shouldReceive('debugVariable')->with($params, 'Params')->once();
        $this->debugMock->shouldReceive('debugVariable')->with([], 'Query [SELECT] Results')->once();

        $errorMsg = 'No data found';
        $this->mockFail(
            $this->debugMock,
            'failDatabase',
            $errorMsg,
            new DatabaseException($errorMsg)
        );

        $errorMsg = 'Empty result error: No data found';
        $this->mockFail(
            $this->debugMock,
            'failDatabase',
            $errorMsg,
            new DatabaseException($errorMsg)
        );

        // Expect exception
        $this->expectException(DatabaseException::class);
        $this->expectExceptionMessage('No data found');

        // Call the method under test
        $this->service->fetchDataWithQueryProxy($sql, $params, $types, 'No data found');
    }

    /**
     * TEST METHOD 6: executeSelectQuery [PROTECTED]
     */

    /**
     * Tests executeSelectQuery() returns results for a valid query.
     *
     * @covers \App\services\database\DatabaseService::executeSelectQuery
     *
     * @return void
     */
    public function testExecuteSelectQueryReturnsResults(): void
    {
        // Define the test data
        $sql = 'SELECT * FROM test_table WHERE id = ?';
        $params = [1];
        $types = 'i';
        $mockResults = [['id' => 1, 'name' => 'John Doe']];

        // Mock the DBConnection method(s) and expected return(s)
        $this->dbMock->shouldReceive('executeQuery')
            ->with($sql, $params, $types, MYSQLI_ASSOC)
            ->andReturn($mockResults);

        // Mock CustomDebug behavior
        $this->mockDebug($this->debugMock, "SQL: {$sql}");
        $this->debugMock->shouldReceive('debugVariable')->with($params, 'Params')->once();
        $this->debugMock->shouldReceive('debugVariable')->with($mockResults, 'Query [SELECT] Results')->once();

        // Call the method under test
        $results = $this->service->executeSelectQueryProxy($sql, $params, $types, MYSQLI_ASSOC);

        // Assert the results match
        $this->assertSame($mockResults, $results);
    }

    /**
     * Tests executeSelectQuery() throws an exception for a failed query.
     *
     * @covers \App\services\database\DatabaseService::executeSelectQuery
     *
     * @return void
     */
    public function testExecuteSelectQueryThrowsExceptionOnFailure(): void
    {
        // Define the test data
        $sql = 'SELECT * FROM test_table WHERE id = ?';
        $params = [1];
        $types = 'i';

        // Mock the DBConnection method(s) and expected return(s)
        $this->dbMock->shouldReceive('executeQuery')
            ->andThrow(new DatabaseException('Query failed'));

        // Mock CustomDebug behavior
        $this->mockDebug($this->debugMock, "SQL: {$sql}");
        $this->debugMock->shouldReceive('debugVariable')->with($params, 'Params')->once();

        $errorMsg = 'Error executing SELECT query: Query failed';
        $this->mockFail(
            $this->debugMock,
            'failDatabase',
            $errorMsg,
            new DatabaseException($errorMsg)
        );

        // Expect exception
        $this->expectException(DatabaseException::class);
        $this->expectExceptionMessage('Error executing SELECT query: Query failed');

        // Call the method under test
        $this->service->executeSelectQueryProxy($sql, $params, $types, MYSQLI_ASSOC);
    }

    /**
     * TEST METHOD 7: ensureNotEmpty [PROTECTED]
     */

    /**
     * Tests ensureNotEmpty() passes for non-empty results.
     *
     * @covers \App\services\database\DatabaseService::ensureNotEmpty
     *
     * @return void
     */
    public function testEnsureNotEmptyPassesForNonEmptyResults(): void
    {
        // Define the test data
        $mockResults = [['id' => 1, 'name' => 'John Doe']];

        // Call the method under test
        $this->service->ensureNotEmptyProxy($mockResults, 'No data found');

        // Assert no exceptions are thrown
        $this->assertTrue(true); // No exception thrown
    }

    /**
     * Tests ensureNotEmpty() throws an exception for empty results.
     *
     * @covers \App\services\database\DatabaseService::ensureNotEmpty
     *
     * @return void
     */
    public function testEnsureNotEmptyThrowsExceptionOnEmptyResults(): void
    {
        // Mock CustomDebug behavior
        $errorMsg = 'No data found';
        $this->mockFail(
            $this->debugMock,
            'failDatabase',
            $errorMsg,
            new DatabaseException($errorMsg)
        );
        $errorMsg = 'Empty result error: No data found';
        $this->mockFail(
            $this->debugMock,
            'failDatabase',
            $errorMsg,
            new DatabaseException($errorMsg)
        );

        // Expect exception
        $this->expectException(DatabaseException::class);
        $this->expectExceptionMessage('Empty result error: No data found');

        // Call the method under test
        $this->service->ensureNotEmptyProxy([], 'No data found');
    }

    /**
     * TEST METHOD 8: modifyDataWithQuery [PROTECTED]
     */

    /**
     * Tests modifyDataWithQuery() returns the number of affected rows.
     *
     * @covers \App\services\database\DatabaseService::modifyDataWithQuery
     *
     * @return void
     */
    public function testModifyDataWithQueryReturnsAffectedRows(): void
    {
        // Define the test data
        $sql = 'UPDATE test_table SET name = ? WHERE id = ?';
        $params = ['Jane Doe', 1];
        $types = 'si';

        // Mock the DBConnection method(s) and expected return(s)
        $this->dbMock->shouldReceive('executeQuery')
            ->with($sql, $params, $types)
            ->andReturn(1);

        // Mock CustomDebug behavior
        $this->mockDebug($this->debugMock, "SQL: {$sql}");
        $this->mockDebug($this->debugMock, "Executing Param Bound SQL: {$sql}");
        $this->debugMock->shouldReceive('debugVariable')->with($params, 'Params')->once();

        // Call the method under test
        $affectedRows = $this->service->modifyDataWithQueryProxy($sql, $params, $types, 1, 'No rows were affected');

        // Assert the results match
        $this->assertSame(1, $affectedRows);
    }

    /**
     * Tests modifyDataWithQuery() throws an exception on unexpected row count.
     *
     * @covers \App\services\database\DatabaseService::modifyDataWithQuery
     *
     * @return void
     */
    public function testModifyDataWithQueryThrowsExceptionOnUnexpectedRowCount(): void
    {
        // Define the test data
        $sql = 'UPDATE test_table SET name = ? WHERE id = ?';
        $params = ['Jane Doe', 1];
        $types = 'si';

        // Mock the DBConnection method(s) and expected return(s)
        $this->dbMock->shouldReceive('executeQuery')
            ->with($sql, $params, $types)
            ->andReturn(0);

        // Mock CustomDebug behavior
        $this->mockDebug($this->debugMock, "SQL: {$sql}");
        $this->mockDebug($this->debugMock, "Executing Param Bound SQL: {$sql}");
        $this->debugMock->shouldReceive('debugVariable')->with($params, 'Params')->once();

        // Mock CustomDebug behavior
        $errorMsg = 'No rows were affected No rows were affected.';
        $this->mockFail(
            $this->debugMock,
            'failDatabase',
            $errorMsg,
            new DatabaseException($errorMsg)
        );
        $errorMsg = "Unexpected row-count error: {$errorMsg}";
        $this->mockFail(
            $this->debugMock,
            'failDatabase',
            $errorMsg,
            new DatabaseException($errorMsg)
        );

        // Expect exception
        $this->expectException(DatabaseException::class);
        $this->expectExceptionMessage('No rows were affected');

        // Call the method under test
        $this->service->modifyDataWithQueryProxy($sql, $params, $types, 1, 'No rows were affected');
    }

    /**
     * TEST METHOD 9: executeUpdateQuery [PROTECTED]
     */

    /**
     * Tests executeUpdateQuery() returns the number of affected rows.
     *
     * @covers \App\services\database\DatabaseService::executeUpdateQuery
     *
     * @return void
     */
    public function testExecuteUpdateQueryReturnsAffectedRows(): void
    {
        // Define the test data
        $sql = 'UPDATE test_table SET name = ? WHERE id = ?';
        $params = ['Jane Doe', 1];
        $types = 'si';

        // Mock the DBConnection method(s) and expected return(s)
        $this->dbMock->shouldReceive('executeQuery')
            ->with($sql, $params, $types)
            ->andReturn(1);

        // Mock CustomDebug behavior
        $this->mockDebug($this->debugMock, "SQL: {$sql}");
        $this->mockDebug($this->debugMock, "Executing Param Bound SQL: {$sql}");
        $this->debugMock->shouldReceive('debugVariable')->with($params, 'Params')->once();

        // Call the method under test
        $affectedRows = $this->service->executeUpdateQueryProxy($sql, $params, $types);

        // Assert the results match
        $this->assertSame(1, $affectedRows);
    }

    /**
     * Tests executeUpdateQuery() throws an exception for a failed query.
     *
     * @covers \App\services\database\DatabaseService::executeUpdateQuery
     *
     * @return void
     */
    public function testExecuteUpdateQueryThrowsExceptionOnFailure(): void
    {
        // Define the test data
        $sql = 'UPDATE test_table SET name = ? WHERE id = ?';
        $params = ['Jane Doe', 1];
        $types = 'si';

        // Mock the DBConnection method(s) and expected return(s)
        $this->dbMock->shouldReceive('executeQuery')
            ->andThrow(new DatabaseException('Query failed'));

        // Mock CustomDebug behavior
        $this->mockDebug($this->debugMock, "SQL: {$sql}");
        $this->mockDebug($this->debugMock, "Executing Param Bound SQL: {$sql}");
        $this->debugMock->shouldReceive('debugVariable')->with($params, 'Params')->once();

        // Mock CustomDebug behavior
        $errorMsg = 'Error executing INSERT/UPDATE/DELETE query: Query failed';
        $this->mockFail(
            $this->debugMock,
            'failDatabase',
            $errorMsg,
            new DatabaseException($errorMsg)
        );

        // Expect exception
        $this->expectException(DatabaseException::class);
        $this->expectExceptionMessage('Query failed');

        // Call the method under test
        $this->service->executeUpdateQueryProxy($sql, $params, $types);
    }

    /**
     * TEST METHOD 10: ensureValidRowCount [PROTECTED]
     */

    /**
     * Tests ensureValidRowCount() passes for the expected row count.
     *
     * @covers \App\services\database\DatabaseService::ensureValidRowCount
     *
     * @return void
     */
    public function testEnsureValidRowCountPassesForExpectedRowCount(): void
    {
        // Call the method under test
        $this->service->ensureValidRowCountProxy(1, 1, 'Unexpected row count');

        // Assert no exceptions are thrown
        $this->assertTrue(true);
    }

    /**
     * Tests ensureValidRowCount() throws an exception for zero row count.
     *
     * @covers \App\services\database\DatabaseService::ensureValidRowCount
     *
     * @return void
     */
    public function testEnsureValidRowCountThrowsExceptionOnZeroRowCount(): void
    {
        // Mock CustomDebug behavior
        $errorMsg = 'Unexpected row count No rows were affected.';
        $this->mockFail(
            $this->debugMock,
            'failDatabase',
            $errorMsg,
            new DatabaseException($errorMsg)
        );
        $errorMsg = "Unexpected row-count error: {$errorMsg}";
        $this->mockFail(
            $this->debugMock,
            'failDatabase',
            $errorMsg,
            new DatabaseException($errorMsg)
        );

        // Expect exception
        $this->expectException(DatabaseException::class);
        $this->expectExceptionMessage('Unexpected row count No rows were affected.');

        // Call the method under test
        $this->service->ensureValidRowCountProxy(0, 1, 'Unexpected row count');
    }

    /**
     * Tests ensureValidRowCount() throws an exception for unexpected row count.
     *
     * @covers \App\services\database\DatabaseService::ensureValidRowCount
     *
     * @return void
     */
    public function testEnsureValidRowCountThrowsExceptionOnUnexpectedRowCount(): void
    {
        // Mock CustomDebug behavior
        $errorMsg = 'Unexpected row count Unexpected number of affected rows.';
        $this->mockFail(
            $this->debugMock,
            'failDatabase',
            $errorMsg,
            new DatabaseException($errorMsg)
        );
        $errorMsg = "Unexpected row-count error: {$errorMsg}";
        $this->mockFail(
            $this->debugMock,
            'failDatabase',
            $errorMsg,
            new DatabaseException($errorMsg)
        );

        // Expect exception
        $this->expectException(DatabaseException::class);
        $this->expectExceptionMessage('Unexpected row count Unexpected number of affected rows.');

        // Call the method under test
        $this->service->ensureValidRowCountProxy(2, 1, 'Unexpected row count');
    }

    /**
     * TEST METHOD 11: getSortString [PROTECTED]
     */

    /**
     * Tests getSortString() for ascending order.
     *
     * @covers \App\services\database\DbQueryUtility::getSortString
     *
     * @return void
     */
    public function testGetSortStringReturnsASCForTrue(): void
    {
        // Call the method under test
        $sortstr = $this->service->getSortStringProxy(true);

        // Assert the results match
        $this->assertSame('ASC', $sortstr);
    }

    /**
     * Tests getSortString() for descending order.
     *
     * @covers \App\services\database\DbQueryUtility::getSortString
     *
     * @return void
     */
    public function testGetSortStringReturnsDESCForFalse(): void
    {
        // Call the method under test
        $sortstr = $this->service->getSortStringProxy(false);

        // Assert the results match
        $this->assertSame('DESC', $sortstr);
    }

    /**
     * HELPER METHODS -- TEST SETUP AND/OR CLEANUP
     */

    /**
     * Set up the test environment.
     *
     * Initializes the Mockery Debug and DBConnection instances.
     *
     * @return void
     */
    protected function setUp(): void
    {
        // Ensure parent setup runs if necessary
        parent::setUp();

        // Set up CustomDebug Mock
        $this->debugMock = $this->createCustomDebugMock();

        // Mock CustomDebug debug() calls

        // Set up DBConnection Mock
        $this->dbMock = $this->createDBConnectionMock();

        // Instantiate TestDatabaseService
        $this->service = new TestDatabaseService(
            'test_db',
            false,
            $this->dbMock,
            $this->debugMock
        );
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
}
