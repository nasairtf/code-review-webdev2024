<?php

declare(strict_types=1);

namespace Tests\classes\services\database;

use Mockery;
use PHPUnit\Framework\TestCase;
use Tests\utilities\PrivatePropertyTrait;
use App\services\database\DbQueryUtility;

/**
 * Unit tests for the DbQueryUtility class.
 *
 * This test suite validates the behavior of static methods in the DbQueryUtility class,
 * focusing on query execution, result validation, and utility methods. Mockery is used
 * to mock dependencies, ensuring unit tests remain isolated and focused on the methods under test.
 *
 * @covers \App\services\database\DbQueryUtility
 */
class DbQueryUtilityTest extends TestCase
{
    use PrivatePropertyTrait;

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
     * Validates that executeSelectQueryWithDebug() successfully executes a SELECT query
     * and returns the expected results.
     *
     * @covers \App\services\database\DbQueryUtility::executeSelectQueryWithDebug
     *
     * @return void
     */
    public function testExecuteSelectQueryWithDebug(): void
    {
        // Mock the Debug and DB classes
        $debug = Mockery::mock(\App\core\common\Debug::class);
        $db = Mockery::mock(\App\services\database\DB::class);

        // Define the test data
        $sql = 'SELECT * FROM users WHERE id = ?';
        $params = [1];
        $types = 'i';
        $resultType = MYSQLI_ASSOC;
        $mockResults = [['id' => 1, 'name' => 'John Doe']];

        // Mock the Debug method(s) and expected return(s)
        $debug->shouldReceive('debug')
            ->with("SQL: {$sql}")
            ->once();
        $debug->shouldReceive('debugVariable')
            ->with($params, 'Params')
            ->once();
        $debug->shouldReceive('debugVariable')
            ->with($mockResults, 'Query [SELECT] Results')
            ->once();

        // Mock the DB method(s) and expected return(s)
        $db->shouldReceive('executeQuery')
            ->with($sql, $params, $types, $resultType)
            ->andReturn($mockResults);

        // Call the method under test
        $results = DbQueryUtility::executeSelectQueryWithDebug($debug, $db, $sql, $params, $types, $resultType);

        // Assert the results match
        $this->assertSame($mockResults, $results);
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
        // Define the exception expectations
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('No results found.');

        // Mock the Debug class
        $debug = Mockery::mock(\App\core\common\Debug::class);

        // Mock the `fail` method to throw the expected exception
        $debug->shouldReceive('fail')
            ->with('No results found.')
            ->once()
            ->andThrow(new \Exception('No results found.'));

        // Call the method under test
        DbQueryUtility::ensureQueryResultsNotEmpty($debug, [], 'No results found.');
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
        $debug = Mockery::mock(\App\core\common\Debug::class);

        // Define the test data
        $data = [['id' => 1, 'name' => 'John Doe']];

        // Define the exception expectation
        $debug->shouldNotReceive('fail');

        // Call the method under test
        DbQueryUtility::ensureQueryResultsNotEmpty($debug, $data, 'No results found.');

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
        // Mock the Debug and DB classes
        $debug = Mockery::mock(\App\core\common\Debug::class);
        $db = Mockery::mock(\App\services\database\DB::class);

        // Define the test data
        $sql = 'UPDATE users SET name = ? WHERE id = ?';
        $params = ['Jane Doe', 1];
        $types = 'si';
        $mockAffectedRows = 1;

        // Mock the Debug method(s) and expected return(s)
        $debug->shouldReceive('debug')
            ->with("SQL: {$sql}")
            ->once();
        $debug->shouldReceive('debugVariable')
            ->with($params, 'Params')
            ->once();
        $debug->shouldReceive('debugVariable')
            ->with($mockAffectedRows, 'Query [INSERT|UPDATE|DELETE] Rows Affected')
            ->once();

        // Mock the DB method(s) and expected return(s)
        $db->shouldReceive('executeQuery')
            ->with($sql, $params, $types)
            ->andReturn($mockAffectedRows);

        // Call the method under test
        $affectedRows = DbQueryUtility::executeUpdateQueryWithDebug($debug, $db, $sql, $params, $types);

        // Assert the affectedRows counts match
        $this->assertSame($mockAffectedRows, $affectedRows);
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
        // Define the exception expectations
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Error: No rows were affected.');

        // Mock the Debug class
        $debug = Mockery::mock(\App\core\common\Debug::class);

        // Mock the `fail` method to throw the expected exception
        $debug->shouldReceive('fail')
            ->with('Error: No rows were affected.')
            ->once()
            ->andThrow(new \Exception('Error: No rows were affected.'));

        // Call the method under test
        DbQueryUtility::ensureRowUpdateResult($debug, 0, 1, 'Error:');
    }

    /**
     * Validates that ensureRowUpdateResult() passes without exception for valid affected rows.
     *
     * @covers \App\services\database\DbQueryUtility::ensureRowUpdateResult
     *
     * @return void
     */
    public function testEnsureRowUpdateResultThrowsExceptionOnUnexpectedRows(): void
    {
        // Define the exception expectations
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Error: Unexpected number of affected rows.');

        // Mock the Debug class
        $debug = Mockery::mock(\App\core\common\Debug::class);

        // Mock the `fail` method to throw the expected exception
        $debug->shouldReceive('fail')
            ->with('Error: Unexpected number of affected rows.')
            ->once()
            ->andThrow(new \Exception('Error: Unexpected number of affected rows.'));

        // Call the method under test
        DbQueryUtility::ensureRowUpdateResult($debug, 2, 1, 'Error:');
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
        $debug = Mockery::mock(\App\core\common\Debug::class);

        // Define the exception expectation
        $debug->shouldNotReceive('fail');

        // Call the method under test
        DbQueryUtility::ensureRowUpdateResult($debug, 1, 1, 'Error:');

        // Assert no exception is thrown
        $this->assertTrue(true);
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
        // Call the method under test
        $result = DbQueryUtility::getSortString(true);

        // Assert the sort strings matches
        $this->assertSame('ASC', $result);
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
        // Call the method under test
        $result = DbQueryUtility::getSortString(false);

        // Assert the sort strings matches
        $this->assertSame('DESC', $result);
    }
}
