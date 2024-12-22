<?php

declare(strict_types=1);

namespace Tests\classes\services\database\feedback\write;

use Mockery;
use PHPUnit\Framework\TestCase;
use Tests\utilities\CustomDebugMockTrait;
use Tests\utilities\DBConnectionMockTrait;
use Tests\utilities\DatabaseServiceMockTrait;
use Tests\classes\services\database\feedback\write\TestOperatorService;
use App\services\database\feedback\write\OperatorService;
use App\exceptions\DatabaseException;

/**
 * Unit tests for the OperatorService write class.
 *
 * This test suite validates the behavior of the OperatorService class,
 * specifically ensuring that its write operations interact with the database
 * as expected.
 *
 * List of method tests:
 *
 * - testInsertOperatorRecordSucceeds
 * - testInsertOperatorRecordFails
 * - testGetOperatorInsertQuery
 *
 * @covers \App\services\database\feedback\write\OperatorService
 *
 *------------------------------------------------------------------------------
 * Test Plan For OperatorService (Write Classes)
 *
 * This class contains one public method to test:
 *
 * - insertOperatorRecord
 *
 * And contains one protected method to test:
 *
 * - getOperatorInsertQuery
 *
 * Test Cases
 *
 * 1. Success case:
 *    - Verify that the method calls modifyDataWithQuery with the correct SQL query, parameters, and types.
 *    - Ensure it returns affectedRows.
 *
 * 2. Failure case:
 *    - Simulate an exception thrown by modifyDataWithQuery and verify the correct error message is handled.
 *
 * 3. getOperatorInsertQuery
 *    - Verify SQL query generation.
 *
 * Mocking
 *
 * Mock the modifyDataWithQuery method in the base OperatorService to simulate database
 * behavior for the tests.
 */
class OperatorServiceWriteTest extends TestCase
{
    use CustomDebugMockTrait;
    use DBConnectionMockTrait;
    use DatabaseServiceMockTrait;

    /**
     * Mock instance of DBConnection.
     *
     * @var Mockery\MockInterface
     */
    private $dbMock;

    /**
     * Mock instance of CustomDebug.
     *
     * @var Mockery\MockInterface
     */
    private $debugMock;

    /**
     * Partial mock of OperatorService.
     *
     * @var OperatorService
     */
    private $srvMock;

    /**
     * TEST METHOD 1: insertOperatorRecord
     */

    /**
     * @covers \App\services\database\feedback\write\OperatorService::insertOperatorRecord
     *
     * @return void
     */
    public function testInsertOperatorRecordSucceeds(): void
    {
        // Define the test data
        $data = $this->createTestData();

        // Arrange
        $this->mockModifyDataWithQuery(
            $this->srvMock,
            $data['query']['sql'],
            $data['query']['params'],
            $data['query']['types'],
            $data['query']['expectedRows'],
            $data['affectedRows'],
            $data['query']['errorMsg']
        );

        // Act
        $result = $this->srvMock->insertOperatorRecord($data['feedbackId'], $data['operators'][0]);

        // Assert
        $this->assertSame($data['affectedRows'], $result);
    }

    /**
     * @covers \App\services\database\feedback\write\OperatorService::insertOperatorRecord
     *
     * @return void
     */
    public function testInsertOperatorRecordFails(): void
    {
        // Define the test data
        $data = $this->createTestData();

        // Arrange
        $this->srvMock->shouldReceive('modifyDataWithQuery')
            ->with(
                $data['query']['sql'],
                $data['query']['params'],
                $data['query']['types'],
                $data['query']['expectedRows'],
                $data['query']['errorMsg']
            )
            ->andThrow(new DatabaseException($data['query']['errorMsg']))
            ->once();

        // Expect exception
        $this->expectException(DatabaseException::class);
        $this->expectExceptionMessage($data['query']['errorMsg']);

        // Act
        $result = $this->srvMock->insertOperatorRecord($data['feedbackId'], $data['operators'][0]);
    }

    /**
     * TEST METHOD 2: getOperatorInsertQuery [PROTECTED]
     */

    /**
     * @covers \App\services\database\feedback\write\OperatorService::getOperatorInsertQuery
     *
     * @return void
     */
    public function testGetOperatorInsertQuery(): void
    {
        // Define the test data
        $data = $this->createTestData();

        // Arrange
        $service = new TestOperatorService(false, null, null, null, null, $this->dbMock, $this->debugMock);

        // Act
        $result = $service->getOperatorInsertQueryProxy();

        // Assert
        $expect = $data['query']['sql'];
        $this->assertSame($expect, $result);
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
        parent::setUp();

        $this->debugMock = $this->createCustomDebugMock();
        $this->dbMock = $this->createDBConnectionMock();
        $this->srvMock = $this->createPartialDatabaseServiceMock(
            OperatorService::class,
            [false, null, null, null, null, $this->dbMock, $this->debugMock],
            ['modifyDataWithQuery']
        );
    }

    /**
     * Cleans up after each test, closing Mockery expectations.
     *
     * @return void
     */
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Creates an array containing the standard test data for 'feedback', 'instruments',
     * 'operators', and 'support'. Can be overridden locally in individual tests.
     *
     * The array indices are 'feedback', 'instruments', 'operators', and 'support'.
     *
     * @return array Array of test data arrays.
     */
    private function createTestData(): array
    {
        // Set up the test data
        $data = [
            // test inputs (data arrays for testing)
            'operators' => ['BM', 'CM', 'BW', 'TM'],
            'feedbackId' => 6432,
            // test outputs (method return values)
            'affectedRows' => 1,
        ];
        // Expected result for each query part
        $data['query'] = $this->createTestQueryParts($data['feedbackId'], $data['operators']);
        return $data;
    }

    /**
     * Generates query components for inserting an operator record.
     *
     * This method creates the SQL query string, parameter array, parameter types string,
     * expected affected row count, and error message for inserting an operator record
     * into the `operator` table.
     *
     * @param int   $feedbackId The ID of the feedback record associated with this operator.
     * @param array $data       The data to be inserted, containing the operator ID as the first element.
     *
     * @return array Associative array containing:
     *               - 'sql' (string): The SQL query string.
     *               - 'params' (array): The parameters to bind to the query.
     *               - 'types' (string): The types of the parameters.
     *               - 'expectedRows' (int): The expected number of affected rows.
     *               - 'errorMsg' (string): The error message to throw on failure.
     */
    private function createTestQueryParts($feedbackId, $data): array
    {
        return [
            // Query's SQL string
            'sql' => "INSERT INTO operator (feedback_id, operatorID) VALUES (?, ?)",
            // Query's params array
            'params' => [$feedbackId, $data[0]],
            // Query's params types string
            'types' => 'is',
            // Query's expected row count
            'expectedRows' => 1,
            // Query's failure error message
            'errorMsg' => 'Telescope operator insert failed.',
        ];
    }
}
