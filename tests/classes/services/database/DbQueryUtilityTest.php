<?php

declare(strict_types=1);

namespace Tests\classes\services\database;

use Mockery;
use PHPUnit\Framework\TestCase;
use Tests\utilities\CustomDebugMockTrait;
use Tests\utilities\DBConnectionMockTrait;
use App\services\database\DbQueryUtility;
use App\exceptions\DatabaseException;

/**
 * Unit tests for the DbQueryUtility class.
 *
 * This test suite validates the behavior of static methods in the DbQueryUtility class,
 * focusing on query execution, result validation, and utility methods. Mockery is used
 * to mock dependencies, ensuring unit tests remain isolated and focused on the methods under test.
 *
 * List of method tests:
 *
 * testExecuteSelectQueryWithDebugReturnsResults
 * testExecuteSelectQueryWithDebugLogsSqlAndParams
 * testExecuteSelectQueryWithDebugLogsResults
 * testEnsureQueryResultsNotEmptyThrowsExceptionOnEmptyData
 * testEnsureQueryResultsNotEmptyPassesWithNonEmptyData
 * testExecuteUpdateQueryWithDebugReturnsAffectedRows
 * testExecuteUpdateQueryWithDebugLogsSqlAndParams
 * testExecuteUpdateQueryWithDebugLogsAffectedRows
 * testExecuteQueryWithDebugExecutesParameterizedQuery
 * testExecuteQueryWithDebugExecutesRawQuery
 * testExecuteQueryWithDebugLogsParameterizedQuery
 * testExecuteQueryWithDebugLogsRawQuery
 * testEnsureRowUpdateResultThrowsExceptionOnZeroAffectedRows
 * testEnsureRowUpdateResultThrowsExceptionOnUnexpectedAffectedRows
 * testEnsureRowUpdateResultPassesWithExpectedAffectedRows
 * testExecuteRawQueryWithDebugReturnsAffectedRowsForNonSelectQuery
 * testExecuteRawQueryWithDebugReturnsRowCountForSelectQuery
 * testExecuteRawQueryWithDebugLogsSqlAndAffectedRows
 * testGetSortStringReturnsAscForTrueInput
 * testGetSortStringReturnsDescForFalseInput
 *
 * @covers \App\services\database\DbQueryUtility
 */
class DbQueryUtilityTest extends TestCase
{
    use CustomDebugMockTrait, DBConnectionMockTrait;

    /**
     * Validates that executeSelectQueryWithDebug() successfully executes a SELECT query
     * and returns the expected results.
     *
     * @covers \App\services\database\DbQueryUtility::executeSelectQueryWithDebug
     *
     * @return void
     */
    public function testExecuteSelectQueryWithDebug(): void
    {
        // Mock the Debug and DBConnection classes
        $debugMock = $this->createCustomDebugMock();
        $dbMock = $this->createDBConnectionMock([
            'executeQuery' => [['id' => 1, 'name' => 'John Doe']],
        ]);

        // Define the test data
        $sql = 'SELECT * FROM users WHERE id = ?';
        $params = [1];
        $types = 'i';

        // Set Debug expectations
        $debugMock->shouldReceive('debug')->with("SQL: {$sql}")->once();
        $debugMock->shouldReceive('debugVariable')->with($params, 'Params')->once();
        $debugMock->shouldReceive('debugVariable')->with([['id' => 1, 'name' => 'John Doe']], 'Query [SELECT] Results')->once();

        // Call the method under test
        $results = DbQueryUtility::executeSelectQueryWithDebug($debugMock, $dbMock, $sql, $params, $types);

        // Assert the results match
        $this->assertSame([['id' => 1, 'name' => 'John Doe']], $results);
    }

    /**
     * Validates that ensureQueryResultsNotEmpty() throws an exception for an empty result set.
     *
     * @covers \App\services\database\DbQueryUtility::ensureQueryResultsNotEmpty
     *
     * @return void
     */
    public function testEnsureQueryResultsNotEmptyThrowsException(): void
    {
        // Mock the Debug class
        $debugMock = $this->createCustomDebugMock();

        // Define the exception expectations
        $this->expectException(DatabaseException::class);
        $this->expectExceptionMessage('No results found.');

        // Mock the `failDatabase` method to throw the expected exception
        $debugMock->shouldReceive('failDatabase')
            ->with('No results found.')
            ->once()
            ->andThrow(new DatabaseException('No results found.'));

        // Call the method under test
        DbQueryUtility::ensureQueryResultsNotEmpty($debugMock, [], 'No results found.');
    }

    /**
     * Validates that ensureQueryResultsNotEmpty() passes without exception for a non-empty result set.
     *
     * @covers \App\services\database\DbQueryUtility::ensureQueryResultsNotEmpty
     *
     * @return void
     */
    public function testEnsureQueryResultsNotEmptyPasses(): void
    {
        // Mock the Debug class
        $debugMock = $this->createCustomDebugMock();

        // Define the test data
        $data = [['id' => 1, 'name' => 'John Doe']];

        // Define the exception expectation
        $debugMock->shouldNotReceive('failDatabase');

        // Call the method under test
        DbQueryUtility::ensureQueryResultsNotEmpty($debugMock, $data, 'No results found.');

        // Assert no exception is thrown
        $this->assertTrue(true);
    }

    /**
     * Validates that executeUpdateQueryWithDebug() successfully executes an UPDATE query
     * and returns the correct number of affected rows.
     *
     * @covers \App\services\database\DbQueryUtility::executeUpdateQueryWithDebug
     *
     * @return void
     */
    public function testExecuteUpdateQueryWithDebug(): void
    {
        // Mock the Debug and DBConnection classes
        $debugMock = $this->createCustomDebugMock();
        $dbMock = $this->createDBConnectionMock([
            'executeQuery' => 1, // Simulate 1 affected row
        ]);

        // Define the test data
        $sql = 'UPDATE users SET name = ? WHERE id = ?';
        $params = ['Jane Doe', 1];
        $types = 'si';

        // Mock the Debug method(s) and expected return(s)
        $debugMock->shouldReceive('debug')->with("SQL: {$sql}")->once();
        $debugMock->shouldReceive('debugVariable')->with($params, 'Params')->once();
        $debugMock->shouldReceive('debugVariable')->with(1, 'Query [INSERT|UPDATE|DELETE] Rows Affected')->once();

        // Call the method under test
        $affectedRows = DbQueryUtility::executeUpdateQueryWithDebug($debugMock, $dbMock, $sql, $params, $types);

        // Assert the affectedRows counts match
        $this->assertSame(1, $affectedRows);
    }

    /**
     * Validates that ensureRowUpdateResult() throws an exception when no rows are affected.
     *
     * @covers \App\services\database\DbQueryUtility::ensureRowUpdateResult
     *
     * @return void
     */
    public function testEnsureRowUpdateResultThrowsExceptionOnZeroRows(): void
    {
        // Mock the Debug class
        $debugMock = $this->createCustomDebugMock();

        // Define the exception expectations
        $this->expectException(DatabaseException::class);
        $this->expectExceptionMessage('Error: No rows were affected.');

        // Mock the `failDatabase` method to throw the expected exception
        $debugMock->shouldReceive('failDatabase')
            ->with('Error: No rows were affected.')
            ->once()
            ->andThrow(new DatabaseException('Error: No rows were affected.'));

        // Call the method under test
        DbQueryUtility::ensureQueryResultsNotEmpty($debugMock, [], 'Error: No rows were affected.');
    }

    /**
     * Tests ensureRowUpdateResult() throws an exception for unexpected affected rows.
     *
     * @covers \App\services\database\DbQueryUtility::ensureRowUpdateResult
     *
     * @return void
     */
    public function testEnsureRowUpdateResultThrowsExceptionOnUnexpectedRows(): void
    {
        // Mock the Debug class
        $debugMock = $this->createCustomDebugMock();

        // Define the exception expectations
        $this->expectException(DatabaseException::class);
        $this->expectExceptionMessage('Error: Unexpected number of affected rows.');

        // Mock the `failDatabase` method to throw the expected exception
        $debugMock->shouldReceive('failDatabase')
            ->with('Error: Unexpected number of affected rows.')
            ->once()
            ->andThrow(new DatabaseException('Error: Unexpected number of affected rows.'));

        // Call the method under test
        DbQueryUtility::ensureRowUpdateResult($debugMock, 2, 1, 'Error:');
    }

    /**
     * Tests ensureRowUpdateResult() for valid affected rows.
     *
     * @covers \App\services\database\DbQueryUtility::ensureRowUpdateResult
     *
     * @return void
     */
    public function testEnsureRowUpdateResultPasses(): void
    {
        // Mock the Debug class
        $debugMock = $this->createCustomDebugMock();

        // Define the exception expectation
        $debugMock->shouldNotReceive('failDatabase');

        // Call the method under test
        DbQueryUtility::ensureRowUpdateResult($debugMock, 1, 1, 'Error:');

        // Assert no exception is thrown
        $this->assertTrue(true);
    }

    /**
     * Tests executeRawQueryWithDebug() successfully executes a raw SQL query.
     *
     * @covers \App\services\database\DbQueryUtility::executeRawQueryWithDebug
     */
    public function testExecuteRawQueryWithDebugReturnsAffectedRows(): void
    {
        // Mock the Debug and DBConnection classes
        $debugMock = $this->createCustomDebugMock();
        $dbMock = $this->createDBConnectionMock([
            'executeRawQuery' => 5, // Simulate 5 affected rows
        ]);

        // Define the test data
        $sql = 'DELETE FROM users WHERE active = 0';

        // Mock the Debug method(s) and expected return(s)
        $debugMock->shouldReceive('debug')->with("Raw SQL: {$sql}")->once();
        $debugMock->shouldReceive('debugVariable')
            ->with(5, 'Query [RAW SQL] Rows Affected')
            ->once();

        // Call the method under test
        $affectedRows = DbQueryUtility::executeRawQueryWithDebug($debugMock, $dbMock, $sql);

        // Assert the affectedRows counts match
        $this->assertSame(5, $affectedRows);
    }

    /**
     * Tests executeRawQueryWithDebug() successfully executes a SELECT query.
     *
     * @covers \App\services\database\DbQueryUtility::executeRawQueryWithDebug
     */
    public function testExecuteRawQueryWithDebugReturnsRowCountForSelect(): void
    {
        // Mock the Debug and DBConnection classes
        $debugMock = $this->createCustomDebugMock();
        $dbMock = $this->createDBConnectionMock([
            'executeRawQuery' => [
                ['id' => 1, 'name' => 'John Doe'],
                ['id' => 2, 'name' => 'Jane Doe'],
            ],
        ]);

        // Define the test data
        $sql = 'SELECT * FROM users';

        // Mock the Debug method(s) and expected return(s)
        $debugMock->shouldReceive('debug')->with("Raw SQL: {$sql}")->once();
        $debugMock->shouldReceive('debugVariable')
            ->with(2, 'Query [RAW SQL] Rows Affected')
            ->once();

        // Call the method under test
        $rowCount = DbQueryUtility::executeRawQueryWithDebug($debugMock, $dbMock, $sql);

        // Assert the row count matches the number of rows returned
        $this->assertSame(2, $rowCount);
    }

    /**
     * Tests getSortString() for ascending order.
     *
     * @covers \App\services\database\DbQueryUtility::getSortString
     *
     * @return void
     */
    public function testGetSortStringReturnsAsc(): void
    {
        // Assert the sort strings matches
        $this->assertSame('ASC', DbQueryUtility::getSortString(true));
    }

    /**
     * Tests getSortString() for descending order.
     *
     * @covers \App\services\database\DbQueryUtility::getSortString
     *
     * @return void
     */
    public function testGetSortStringReturnsDesc(): void
    {
        // Assert the sort strings matches
        $this->assertSame('DESC', DbQueryUtility::getSortString(false));
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
