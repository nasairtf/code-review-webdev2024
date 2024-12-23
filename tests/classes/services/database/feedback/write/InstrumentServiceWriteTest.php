<?php

declare(strict_types=1);

namespace Tests\classes\services\database\feedback\write;

use Mockery;
use PHPUnit\Framework\TestCase;
use Tests\utilities\CustomDebugMockTrait;
use Tests\utilities\DBConnectionMockTrait;
use Tests\utilities\DatabaseServiceMockTrait;
use Tests\classes\services\database\feedback\write\TestInstrumentService;
use App\services\database\feedback\write\InstrumentService;
use App\exceptions\DatabaseException;

/**
 * Unit tests for the InstrumentService write class.
 *
 * This test suite validates the behavior of the InstrumentService class,
 * specifically ensuring that its write operations interact with the database
 * as expected.
 *
 * List of method tests:
 *
 * - testInsertInstrumentRecordSucceeds [DONE]
 * - testInsertInstrumentRecordFails [DONE]
 * - testGetInstrumentInsertQuery [DONE]
 *
 * @covers \App\services\database\feedback\write\InstrumentService
 *
 *------------------------------------------------------------------------------
 * Test Plan For InstrumentService (Write Classes)
 *
 * This class contains one public method to test:
 *
 * - insertInstrumentRecord
 *
 * And contains one protected method to test:
 *
 * - getInstrumentInsertQuery
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
 * 3. getInstrumentInsertQuery
 *    - Verify SQL query generation.
 *
 * Mocking
 *
 * Mock the modifyDataWithQuery method in the base InstrumentService to simulate database
 * behavior for the tests.
 */
class InstrumentServiceWriteTest extends TestCase
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
     * Partial mock of InstrumentService.
     *
     * @var InstrumentService
     */
    private $srvMock;

    /**
     * TEST METHOD 1: insertInstrumentRecord
     */

    /**
     * @covers \App\services\database\feedback\write\InstrumentService::insertInstrumentRecord
     *
     * @return void
     */
    public function testInsertInstrumentRecordSucceeds(): void
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
        $result = $this->srvMock->insertInstrumentRecord($data['feedbackId'], $data['instruments'][0]);

        // Assert
        $this->assertSame($data['affectedRows'], $result);
    }

    /**
     * @covers \App\services\database\feedback\write\InstrumentService::insertInstrumentRecord
     *
     * @return void
     */
    public function testInsertInstrumentRecordFails(): void
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
        $result = $this->srvMock->insertInstrumentRecord($data['feedbackId'], $data['instruments'][0]);
    }

    /**
     * TEST METHOD 2: getInstrumentInsertQuery [PROTECTED]
     */

    /**
     * @covers \App\services\database\feedback\write\InstrumentService::getInstrumentInsertQuery
     *
     * @return void
     */
    public function testGetInstrumentInsertQuery(): void
    {
        // Define the test data
        $data = $this->createTestData();

        // Arrange
        $service = new TestInstrumentService(false, null, null, null, null, $this->dbMock, $this->debugMock);

        // Act
        $result = $service->getInstrumentInsertQueryProxy();

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
            InstrumentService::class,
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
            'instruments' => ['moris', 'spex', 'texes'],
            'feedbackId' => 6432,
            // test outputs (method return values)
            'affectedRows' => 1,
        ];
        // Expected result for each query part
        $data['query'] = $this->createTestQueryParts($data['feedbackId'], $data['instruments']);
        return $data;
    }

    /**
     * Generates query components for inserting an instrument record.
     *
     * This method creates the SQL query string, parameter array, parameter types string,
     * expected affected row count, and error message for inserting an instrument record
     * into the `instrument` table.
     *
     * @param int   $feedbackId The ID of the feedback record associated with this instrument.
     * @param array $data       The data to be inserted, containing the hardware ID as the first element.
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
            'sql' => "INSERT INTO instrument (feedback_id, hardwareID) VALUES (?, ?)",
            // Query's params array
            'params' => [$feedbackId, $data[0]],
            // Query's params types string
            'types' => 'is',
            // Query's expected row count
            'expectedRows' => 1,
            // Query's failure error message
            'errorMsg' => 'Instrument insert failed.',
        ];
    }
}
