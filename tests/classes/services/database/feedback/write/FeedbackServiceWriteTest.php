<?php

declare(strict_types=1);

namespace Tests\classes\services\database\feedback\write;

use Mockery;
use PHPUnit\Framework\TestCase;
use Tests\utilities\CustomDebugMockTrait;
use Tests\utilities\DBConnectionMockTrait;
use Tests\utilities\DatabaseServiceMockTrait;
use Tests\classes\services\database\feedback\write\TestFeedbackService;
use App\services\database\feedback\write\FeedbackService;
use App\exceptions\DatabaseException;

/**
 * Unit tests for the FeedbackService write class.
 *
 * This test suite validates the behavior of the FeedbackService class,
 * specifically ensuring that its write operations interact with the database
 * as expected.
 *
 * List of method tests:
 *
 * - testReturnFeedbackRecordIdSucceeds
 * - testInsertFeedbackRecordSucceeds
 * - testInsertFeedbackRecordFails
 * - testGetFeedbackInsertQuery
 * - testGetFeedbackInsertParams
 * - testGetFeedbackInsertTypes
 *
 * @covers \App\services\database\feedback\write\FeedbackService
 *
 *------------------------------------------------------------------------------
 * Test Plan For FeedbackService (Write Class)
 *
 * This class contains two public methods to test:
 *
 * - returnFeedbackRecordId
 * - insertFeedbackRecord
 *
 * And contains three protected methods to test:
 *
 * - getFeedbackInsertQuery
 * - getFeedbackInsertParams
 * - getFeedbackInsertTypes
 *
 * Test Cases
 *
 * 1. returnFeedbackRecordId
 *    - Verify that it calls getLastInsertId on the database connection and returns the correct value.
 *
 * 2. insertFeedbackRecord
 *    - Success case: Data is inserted successfully, returning affectedRows.
 *    - Failure case: An exception is thrown, and the error message is verified.
 *
 * 3. getFeedbackInsertQuery
 *    - Verify SQL query generation.
 *
 * 4. getFeedbackInsertParams
 *    - Verify param array generation.
 *
 * 5. getFeedbackInsertTypes
 *    - Verify param type string generation.
 *
 * Mocking
 *
 * Mock the modifyDataWithQuery method in the base FeedbackService to simulate database
 * behavior for the tests.
 */
class FeedbackServiceWriteTest extends TestCase
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
     * Partial mock of FeedbackService.
     *
     * @var FeedbackService
     */
    private $srvMock;

    /**
     * TEST METHOD 1: returnFeedbackRecordId
     */

    /**
     * Tests that returnFeedbackRecordId successfully retrieves data.
     *
     * @covers \App\services\database\feedback\write\FeedbackService::returnFeedbackRecordId
     *
     * @return void
     */
    public function testReturnFeedbackRecordIdSucceeds(): void
    {
        // Define the test data
        //$data = $this->createTestData();

        // Arrange

        // Act
        //$result = $this->srvMock->returnFeedbackRecordId();

        // Assert
        //$this->assertSame($data['recordID'], $result);
    }

    /**
     * TEST METHOD 2: insertFeedbackRecord
     */

    /**
     * @covers \App\services\database\feedback\write\FeedbackService::insertFeedbackRecord
     *
     * @return void
     */
    public function testInsertFeedbackRecordSucceeds(): void
    {
        // Define the test data
        //$data = $this->createTestData();

        // Arrange

        // Act
        //$result = $this->srvMock->insertFeedbackRecord();

        // Assert
        //$this->assertSame($data['successResult'], $result);
    }

    /**
     * @covers \App\services\database\feedback\write\FeedbackService::insertFeedbackRecord
     *
     * @return void
     */
    public function testInsertFeedbackRecordFails(): void
    {
        // Define the test data
        //$data = $this->createTestData();

        // Arrange

        // Act
        //$result = $this->srvMock->insertFeedbackRecord();

        // Assert
        //$this->assertSame($data['failureResult'], $result);
    }

    /**
     * TEST METHOD 3: getFeedbackInsertQuery [PROTECTED]
     */

    /**
     * @covers \App\services\database\feedback\write\FeedbackService::getFeedbackInsertQuery
     *
     * @return void
     */
    public function testGetFeedbackInsertQuery(): void
    {
        // Define the test data
        $data = $this->createTestData();

        // Arrange
        $service = new TestFeedbackService(false, null, null, null, null, $this->dbMock, $this->debugMock);

        // Act
        $result = $service->getFeedbackInsertQueryProxy();

        // Assert
        $expect = $data['query']['sql'];
        $this->assertSame($expect, $result);
    }

    /**
     * TEST METHOD 4: getFeedbackInsertParams [PROTECTED]
     */

    /**
     * @covers \App\services\database\feedback\write\FeedbackService::getFeedbackInsertParams
     *
     * @return void
     */
    public function testGetFeedbackInsertParams(): void
    {
        // Define the test data
        $data = $this->createTestData();

        // Arrange
        $service = new TestFeedbackService(false, null, null, null, null, $this->dbMock, $this->debugMock);

        // Act
        $result = $service->getFeedbackInsertParamsProxy($data['feedback']);

        // Assert
        $expect = $data['query']['params'];
        $this->assertSame($expect, $result);
    }

    /**
     * TEST METHOD 5: getFeedbackInsertTypes [PROTECTED]
     */

    /**
     * @covers \App\services\database\feedback\write\FeedbackService::getFeedbackInsertTypes
     *
     * @return void
     */
    public function testGetFeedbackInsertTypes(): void
    {
        // Define the test data
        $data = $this->createTestData();

        // Arrange
        $service = new TestFeedbackService(false, null, null, null, null, $this->dbMock, $this->debugMock);

        // Act
        $result = $service->getFeedbackInsertTypesProxy();

        // Assert
        $expect = $data['query']['types'];
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
            FeedbackService::class,
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
        ];
        $data['query'] = $this->createTestQueryParts($data['feedback']); // Expect result for each query part
        return $data;
    }

    private function createTestQueryParts(array $data): array
    {
        return [
            // Return SQL types string
            'types' => 'iiisiiisssssiis',
            // Return SQL query string
            'sql' => "INSERT INTO feedback "
                . "("
                .    "start_date, end_date, technical_rating, "
                .    "technical_comments, scientific_staff_rating, "
                .    "TO_rating, daycrew_rating, personnel_comment, "
                .    "scientific_results, suggestions, "
                .    "name, email, location, programID, semesterID"
                . ") "
                . "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
            // Return SQL params array
            'params' => [
                $data['start_date'], $data['end_date'], $data['technical_rating'],
                $data['technical_comments'], $data['scientific_staff_rating'],
                $data['TO_rating'], $data['daycrew_rating'], $data['personnel_comment'],
                $data['scientific_results'], $data['suggestions'],
                $data['name'], $data['email'], $data['location'], $data['programID'], $data['semesterID']
            ],
        ];
    }
}
