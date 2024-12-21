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
 * - testInsertInstrumentRecordSucceeds
 * - testInsertInstrumentRecordFails
 * - testGetInstrumentInsertQuery
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
        //$data = $this->createTestData();

        // Arrange

        // Act
        //$result = $this->srvMock->insertInstrumentRecord();

        // Assert
        //$this->assertSame($data['successResult'], $result);
    }

    /**
     * @covers \App\services\database\feedback\write\InstrumentService::insertInstrumentRecord
     *
     * @return void
     */
    public function testInsertInstrumentRecordFails(): void
    {
        // Define the test data
        //$data = $this->createTestData();

        // Arrange

        // Act
        //$result = $this->srvMock->insertInstrumentRecord();

        // Assert
        //$this->assertSame($data['failureResult'], $result);
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
            'feedback' => [
                'start_date' => 1732528800,
                'end_date' => 1734084000,
                'technical_rating' => 4,
                'technical_comments' => 'Dithering issues',
                'scientific_staff_rating' => 5,
                'TO_rating' => 5,
                'daycrew_rating' => null,
                'personnel_comment' => 'Great effort overall.',
                'scientific_results' => 'The shift went smoothly.',
                'suggestions' => 'Please put in the full name of the operators.',
                'name' => 'John Doe',
                'email' => 'johndoe@example.com',
                'location' => 1,
                'programID' => 31,
                'semesterID' => '2024B',
            ],
            'instruments' => ['moris', 'spex', 'texes'],
            'operators' => ['BM', 'CM', 'BW', 'TM'],
            'support' => ['MC', 'AB'],
            // test outputs (method return values)
            'feedbackId' => 6432,     // Mocked ID returned from FeedbackWrite
            'affectedRows' => 1,      // Expected affectedRow count for each insert method call
            'successResult' => true,  // Expected result for successful record insertion
            'failureResult' => false, // Expected result for record insertion failure
            'query' => $this->createTestQueryParts(), // Expect result for each query part
        ];
        return $data;
    }

    private function createTestQueryParts(): array
    {
        return [
            // Return SQL types string
            'types' => '',
            // Return SQL query string
            'sql' => "INSERT INTO instrument (feedback_id, hardwareID) VALUES (?, ?)",
            // Return SQL params array
            'params' => [],
        ];
    }
}
