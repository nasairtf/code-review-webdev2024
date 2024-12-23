<?php

declare(strict_types=1);

namespace Tests\classes\services\database\feedback\read;

use Mockery;
use PHPUnit\Framework\TestCase;
use Tests\utilities\CustomDebugMockTrait;
use Tests\utilities\DBConnectionMockTrait;
use Tests\utilities\DatabaseServiceMockTrait;
use Tests\classes\services\database\feedback\read\TestFeedbackService;
use App\services\database\feedback\read\FeedbackService;
use App\exceptions\DatabaseException;

/**
 * Unit tests for the FeedbackService read class.
 *
 * This test suite validates the behavior of the FeedbackService class,
 * specifically ensuring that its read operations interact with the database
 * as expected.
 *
 * List of method tests:
 *
 * - testFetchSemesterProposalListingFormDataSucceeds [DONE]
 * - testFetchSemesterProposalListingFormDataFails [DONE]
 * - testFetchProposalProgramDataSucceeds [DONE]
 * - testFetchProposalProgramDataFails [DONE]
 * - testGetProposalListingFormDataQuery [DONE]
 * - testGetProposalProgramDataQuery [DONE]
 *
 * @covers \App\services\database\feedback\read\FeedbackService
 *
 *------------------------------------------------------------------------------
 * Test Plan For FeedbackService (Read Class)
 *
 * This class contains two public methods to test:
 *
 * - fetchSemesterProposalListingFormData
 * - fetchProposalProgramData
 *
 * And contains two protected methods to test:
 *
 * - getProposalListingFormDataQuery
 * - getProposalProgramDataQuery
 *
 * Test Cases
 *
 * 1. fetchSemesterProposalListingFormData
 *    - Success case: Query returns a valid result array.
 *    - Failure case: Query returns no results (empty array).
 *    - Verify correct SQL query generation.
 *    - Verify parameters binding and types (is).
 *
 * 2. fetchProposalProgramData
 *    - Success case: Query returns a valid result array for a specific program.
 *    - Failure case: Query returns no matching results (empty array).
 *    - Verify SQL query generation and parameter binding (isi).
 *
 * 3. getProposalListingFormDataQuery
 *    - Verify SQL query generation for a propsal listing.
 *
 * 4. getProposalProgramDataQuery
 *    - Verify SQL query generation.
 *
 * Mocking
 *
 * Mock the fetchDataWithQuery method in the base FeedbackService to simulate database
 * responses for the tests.
 */
class FeedbackServiceReadTest extends TestCase
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
     * TEST METHOD 1: fetchSemesterProposalListingFormData
     */

    /**
     * Tests that fetchSemesterProposalListingFormData successfully retrieves data.
     *
     * @covers \App\services\database\feedback\read\FeedbackService::fetchSemesterProposalListingFormData
     *
     * @return void
     */
    public function testFetchSemesterProposalListingFormDataSucceeds(): void
    {
        // Define the test data
        $data = $this->createTestData();

        // Arrange
        $this->mockFetchDataWithQuery(
            $this->srvMock,
            $data['sqlsemester']['sql'],
            $data['sqlsemester']['params'],
            $data['sqlsemester']['types'],
            $data['successResult'],
            $data['sqlsemester']['errorMsg']
        );

        // Act
        $result = $this->srvMock->fetchSemesterProposalListingFormData($data['year'], $data['semester']);

        // Assert
        $this->assertSame($data['successResult'], $result);
    }

    /**
     * Tests that fetchSemesterProposalListingFormData fails gracefully when no data is found.
     *
     * @covers \App\services\database\feedback\read\FeedbackService::fetchSemesterProposalListingFormData
     *
     * @return void
     */
    public function testFetchSemesterProposalListingFormDataFails(): void
    {
        // Define the test data
        $data = $this->createTestData();

        // Arrange
        $this->mockFetchDataWithQuery(
            $this->srvMock,
            $data['sqlsemester']['sql'],
            $data['sqlsemester']['params'],
            $data['sqlsemester']['types'],
            $data['failureResult'],
            $data['sqlsemester']['errorMsg']
        );

        // Act
        $result = $this->srvMock->fetchSemesterProposalListingFormData($data['year'], $data['semester']);

        // Assert
        $this->assertSame($data['failureResult'], $result);
    }

    /**
     * TEST METHOD 2: fetchProposalProgramData
     */

    /**
     * Tests that fetchProposalProgramData successfully retrieves data.
     *
     * @covers \App\services\database\feedback\read\FeedbackService::fetchProposalProgramData
     *
     * @return void
     */
    public function testFetchProposalProgramDataSucceeds(): void
    {
        // Define the test data
        $data = $this->createTestData();

        // Arrange
        $this->mockFetchDataWithQuery(
            $this->srvMock,
            $data['sqlproposal']['sql'],
            $data['sqlproposal']['params'],
            $data['sqlproposal']['types'],
            [$data['successResult'][0]],
            $data['sqlproposal']['errorMsg']
        );

        // Act
        $result = $this->srvMock->fetchProposalProgramData($data['year'], $data['semester'], $data['program']);

        // Assert
        $this->assertSame([$data['successResult'][0]], $result);
    }

    /**
     * Tests that fetchProposalProgramData fails gracefully when no data is found.
     *
     * @covers \App\services\database\feedback\read\FeedbackService::fetchProposalProgramData
     *
     * @return void
     */
    public function testFetchProposalProgramDataFails(): void
    {
        // Define the test data
        $data = $this->createTestData();

        // Arrange
        $this->mockFetchDataWithQuery(
            $this->srvMock,
            $data['sqlproposal']['sql'],
            $data['sqlproposal']['params'],
            $data['sqlproposal']['types'],
            $data['failureResult'],
            $data['sqlproposal']['errorMsg']
        );

        // Act
        $result = $this->srvMock->fetchProposalProgramData($data['year'], $data['semester'], $data['program']);

        // Assert
        $this->assertSame($data['failureResult'], $result);
    }

    /**
     * TEST METHOD 3: getProposalListingFormDataQuery [PROTECTED]
     */

    /**
     * Tests the SQL query generation for fetching semester listing data.
     *
     * This test ensures that the `getProposalListingFormDataQuery` method in
     * the FeedbackService class generates the correct SQL query string when
     * semester-based data is requested.
     *
     * @covers \App\services\database\feedback\read\FeedbackService::getProposalListingFormDataQuery
     *
     * @return void
     */
    public function testGetProposalListingFormDataQuery(): void
    {
        // Define the test data
        $data = $this->createTestData();

        // Arrange
        $service = new TestFeedbackService(false, null, null, null, null, $this->dbMock, $this->debugMock);

        // Act
        $query = $service->getProposalListingFormDataQueryProxy(true);

        // Assert
        $expect = $data['sqlsemester']['sql'];
        $this->assertSame($expect, $query);
    }

    /**
     * TEST METHOD 4: getProposalProgramDataQuery [PROTECTED]
     */

    /**
     * Tests the SQL query generation for fetching program-specific data.
     *
     * This test ensures that the `getProposalProgramDataQuery` method in the
     * FeedbackService class generates the correct SQL query string for
     * retrieving program-specific data from the ObsApp table.
     *
     * @covers \App\services\database\feedback\read\FeedbackService::getProposalProgramDataQuery
     *
     * @return void
     */
    public function testGetProposalProgramDataQuery(): void
    {
        // Define the test data
        $data = $this->createTestData();

        // Arrange
        $service = new TestFeedbackService(false, null, null, null, null, $this->dbMock, $this->debugMock);

        // Act
        $query = $service->getProposalProgramDataQueryProxy();

        // Assert
        $expect = $data['sqlproposal']['sql'];
        $this->assertSame($expect, $query);
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
            ['fetchDataWithQuery']
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
     * Creates an array containing the standard test data for this test unit suite.
     * Can be overridden locally in individual tests.
     *
     * @return array Array of test data arrays.
     */
    private function createTestData(): array
    {
        // Set up the test data
        $data = [
            // test inputs (data values for testing)
            'year' => 2024,
            'semester' => 'A',
            'program' => 31,
            // test outputs (method return values, etc)
            'successResult' => [ // Expected result for successful record retrieval
                [
                    'ObsApp_id' => 3210,
                    'semesterYear' => 2024,
                    'semesterCode' => 'B',
                    'ProgramNumber' => 31,
                    'InvLastName1' => 'Doe',
                    'code' => 'G46FLDGJT8',
                    'creationDate' => '1711852286',
                ],
                [
                    'ObsApp_id' => 3195,
                    'semesterYear' => 2024,
                    'semesterCode' => 'B',
                    'ProgramNumber' => 36,
                    'InvLastName1' => 'Smith',
                    'code' => 'V3NDETTA2H',
                    'creationDate' => '1711918400',
                ],
            ],
            'failureResult' => [], // Expected result for record retrieval failure
        ];
        // Expected results for each query part
        $data['sqlsemester'] = $this->createTestQueryParts(true, $data);
        $data['sqlproposal'] = $this->createTestQueryParts(false, $data);
        return $data;
    }

    /**
     * Generates components for a SQL query for fetching semester or program data.
     *
     * This method returns an array containing the components required to construct
     * a SQL query: the query string, parameter types, and an array of parameters.
     * It supports generating components for either semester-based data or
     * program-specific data, depending on the $semester parameter.
     *
     * @param bool $semester True to generate components for semester-based data.
     *                       False to generate components for program-specific data.
     *
     * @return array An associative array with the following keys:
     *               - 'sql' (string): The SQL query string.
     *               - 'params' (array): The parameters to bind to the query.
     *               - 'types' (string): The types of the query parameters.
     *               - 'errorMsg' (string): The message to output for query errors.
     */
    private function createTestQueryParts(bool $semester, array $data): array
    {
        $sql = "SELECT ObsApp_id, semesterYear, semesterCode, ProgramNumber, InvLastName1, code, creationDate"
            . " FROM ObsApp WHERE semesterYear = ? AND semesterCode = ?";
        $params = [$data['year'], $data['semester']];
        $types = 'is';
        $errorMsg = '';

        if ($semester) {
            $sql .= " ORDER BY creationDate ASC;";
            $errorMsg = 'No proposals found for the selected semester.';
        } else {
            $sql .= " AND ProgramNumber = ?;";
            $params[] = $data['program'];
            $types .= 'i';
            $errorMsg = 'No proposal found for the given program.';
        }

        return [
            // Query's SQL string
            'sql' => $sql,
            // Query's params array
            'params' => $params,
            // Query's params types string
            'types' => $types,
            // Query's failure error message
            'errorMsg' => $errorMsg,
        ];
    }
}
