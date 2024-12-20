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
 * testExecuteSelectQueryWithDebugReturnsResults [DONE]
 * testExecuteSelectQueryWithDebugLogsSqlAndParams [INDIRECTLY TESTED]
 * testExecuteSelectQueryWithDebugLogsResults [INDIRECTLY TESTED]
 * testEnsureQueryResultsNotEmptyThrowsExceptionOnEmptyData [DONE]
 * testEnsureQueryResultsNotEmptyPassesWithNonEmptyData [DONE]
 * testExecuteUpdateQueryWithDebugReturnsAffectedRows [DONE]
 * testExecuteUpdateQueryWithDebugLogsSqlAndParams [INDIRECTLY TESTED]
 * testExecuteUpdateQueryWithDebugLogsAffectedRows [INDIRECTLY TESTED]
 * testExecuteQueryWithDebugExecutesParameterizedQuery [DONE]
 * testExecuteQueryWithDebugExecutesRawQuery [DONE]
 * testExecuteQueryWithDebugLogsParameterizedQuery [INDIRECTLY TESTED]
 * testExecuteQueryWithDebugLogsRawQuery [INDIRECTLY TESTED]
 * testEnsureRowUpdateResultThrowsExceptionOnZeroAffectedRows [DONE]
 * testEnsureRowUpdateResultThrowsExceptionOnUnexpectedAffectedRows [DONE]
 * testEnsureRowUpdateResultPassesWithExpectedAffectedRows [DONE]
 * testExecuteRawQueryWithDebugReturnsAffectedRowsForNonSelectQuery [DONE]
 * testExecuteRawQueryWithDebugReturnsRowCountForSelectQuery [DONE]
 * testExecuteRawQueryWithDebugLogsSqlAndAffectedRows [INDIRECTLY TESTED]
 * testGetSortStringReturnsAscForTrueInput [DONE]
 * testGetSortStringReturnsDescForFalseInput [DONE]
 *
 * @covers \App\services\database\DbQueryUtility
 */
class DbQueryUtilityTest extends TestCase
{
    use CustomDebugMockTrait;
    use DBConnectionMockTrait;

    /**
     * TEST METHOD 1: executeSelectQueryWithDebug
     *
     * These tests are unneeded due to CustomDebug testing via its own unit tests:
     * - testExecuteSelectQueryWithDebugLogsSqlAndParams
     * - testExecuteSelectQueryWithDebugLogsResults
     */

    /**
     * Validates that executeSelectQueryWithDebug() successfully executes a SELECT query
     * and returns the expected results.
     *
     * @covers \App\services\database\DbQueryUtility::executeSelectQueryWithDebug
     *
     * @return void
     */
    public function testExecuteSelectQueryWithDebugReturnsResults(): void
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

        // Call the method under test
        $results = DbQueryUtility::executeSelectQueryWithDebug($debugMock, $dbMock, $sql, $params, $types);

        // Assert the results match
        $this->assertSame([['id' => 1, 'name' => 'John Doe']], $results);
    }

    /**
     * TEST METHOD 2: ensureQueryResultsNotEmpty
     */

    /**
     * Validates that ensureQueryResultsNotEmpty() throws an exception for an empty result set.
     *
     * @covers \App\services\database\DbQueryUtility::ensureQueryResultsNotEmpty
     *
     * @return void
     */
    public function testEnsureQueryResultsNotEmptyThrowsExceptionOnEmptyData(): void
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
    public function testEnsureQueryResultsNotEmptyPassesWithNonEmptyData(): void
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
     * TEST METHOD 3: executeUpdateQueryWithDebug
     *
     * These tests are unneeded due to CustomDebug testing via its own unit tests:
     * - testExecuteUpdateQueryWithDebugLogsSqlAndParams
     * - testExecuteUpdateQueryWithDebugLogsAffectedRows
     */

    /**
     * Validates that executeUpdateQueryWithDebug() successfully executes an UPDATE query
     * and returns the correct number of affected rows.
     *
     * @covers \App\services\database\DbQueryUtility::executeUpdateQueryWithDebug
     *
     * @return void
     */
    public function testExecuteUpdateQueryWithDebugReturnsAffectedRows(): void
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

        // Call the method under test
        $affectedRows = DbQueryUtility::executeUpdateQueryWithDebug($debugMock, $dbMock, $sql, $params, $types);

        // Assert the affectedRows counts match
        $this->assertSame(1, $affectedRows);
    }

    /**
     * TEST METHOD 4: executeQueryWithDebug
     *
     * These tests are unneeded due to CustomDebug testing via its own unit tests:
     * - testExecuteQueryWithDebugLogsParameterizedQuery
     * - testExecuteQueryWithDebugLogsRawQuery
     */

    /**
     * Validates that executeQueryWithDebug() executes a parameterized query when parameters are provided.
     *
     * @covers \App\services\database\DbQueryUtility::executeQueryWithDebug
     */
    public function testExecuteQueryWithDebugExecutesParameterizedQuery(): void
    {
        // Mock the Debug and DBConnection classes
        $debugMock = $this->createCustomDebugMock();
        $dbMock = $this->createDBConnectionMock();

        // Define the test data
        $sql = 'UPDATE users SET name = ? WHERE id = ?';
        $params = ['Jane Doe', 1];
        $types = 'si';
        $affectedRows = 3;

        // Mock the DBConnection::executeQuery
        $dbMock->shouldReceive('executeQuery')
            ->with($sql, $params, $types)
            ->once()
            ->andReturn($affectedRows);

        // Call the method under test
        $result = DbQueryUtility::executeQueryWithDebug($debugMock, $dbMock, $sql, $params, $types);

        // Assert that the correct result is returned
        $this->assertSame($affectedRows, $result);
    }

    /**
     * Validates that executeQueryWithDebug() executes a raw query when no parameters are provided.
     *
     * @covers \App\services\database\DbQueryUtility::executeQueryWithDebug
     */
    public function testExecuteQueryWithDebugExecutesRawQuery(): void
    {
        // Mock the Debug and DBConnection classes
        $debugMock = $this->createCustomDebugMock();
        $dbMock = $this->createDBConnectionMock();

        // Define the test data
        $sql = 'DELETE FROM users WHERE active = 0';
        $affectedRows = 5;

        // Mock the DBConnection::executeRawQuery
        $dbMock->shouldReceive('executeRawQuery')
            ->with($sql)
            ->once()
            ->andReturn($affectedRows);

        // Call the method under test
        $result = DbQueryUtility::executeQueryWithDebug($debugMock, $dbMock, $sql);

        // Assert that the correct result is returned
        $this->assertSame($affectedRows, $result);
    }

    /**
     * TEST METHOD 5: ensureRowUpdateResult
     */

    /**
     * Validates that ensureRowUpdateResult() throws an exception when no rows are affected.
     *
     * @covers \App\services\database\DbQueryUtility::ensureRowUpdateResult
     *
     * @return void
     */
    public function testEnsureRowUpdateResultThrowsExceptionOnZeroAffectedRows(): void
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
        DbQueryUtility::ensureRowUpdateResult($debugMock, 0, 0, 'Error:');
    }

    /**
     * Tests ensureRowUpdateResult() throws an exception for unexpected affected rows.
     *
     * @covers \App\services\database\DbQueryUtility::ensureRowUpdateResult
     *
     * @return void
     */
    public function testEnsureRowUpdateResultThrowsExceptionOnUnexpectedAffectedRows(): void
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
    public function testEnsureRowUpdateResultPassesWithExpectedAffectedRows(): void
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
     * TEST METHOD 6: executeRawQueryWithDebug
     *
     * This test is unneeded due to CustomDebug testing via its own unit tests:
     * - testExecuteRawQueryWithDebugLogsSqlAndAffectedRows
     */

    /**
     * Tests executeRawQueryWithDebug() successfully executes a raw SQL query.
     *
     * @covers \App\services\database\DbQueryUtility::executeRawQueryWithDebug
     */
    public function testExecuteRawQueryWithDebugReturnsAffectedRowsForNonSelectQuery(): void
    {
        // Mock the Debug and DBConnection classes
        $debugMock = $this->createCustomDebugMock();
        $dbMock = $this->createDBConnectionMock([
            'executeRawQuery' => 5, // Simulate 5 affected rows
        ]);

        // Define the test data
        $sql = 'DELETE FROM users WHERE active = 0';

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
    public function testExecuteRawQueryWithDebugReturnsRowCountForSelectQuery(): void
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

        // Call the method under test
        $rowCount = DbQueryUtility::executeRawQueryWithDebug($debugMock, $dbMock, $sql);

        // Assert the row count matches the number of rows returned
        $this->assertSame(2, $rowCount);
    }

    /**
     * TEST METHOD 7: executeRawQueryWithDebug
     */

    /**
     * Tests getSortString() for ascending order.
     *
     * @covers \App\services\database\DbQueryUtility::getSortString
     *
     * @return void
     */
    public function testGetSortStringReturnsAscForTrueInput(): void
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
    public function testGetSortStringReturnsDescForFalseInput(): void
    {
        // Assert the sort strings matches
        $this->assertSame('DESC', DbQueryUtility::getSortString(false));
    }

    /**
     * HELPER METHODS -- TEST SETUP AND/OR CLEANUP
     */

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
