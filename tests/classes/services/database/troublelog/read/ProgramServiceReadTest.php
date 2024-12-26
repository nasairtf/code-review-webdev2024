<?php

declare(strict_types=1);

namespace Tests\classes\services\database\troublelog\read;

use Mockery;
use PHPUnit\Framework\TestCase;
use Tests\utilities\CustomDebugMockTrait;
use Tests\utilities\DBConnectionMockTrait;
use Tests\utilities\DatabaseServiceMockTrait;
use Tests\classes\services\database\troublelog\read\TestProgramService;
use App\services\database\troublelog\read\ProgramService;
use App\exceptions\DatabaseException;

/**
 * Unit tests for the ProgramService read class.
 *
 * This test suite validates the behavior of the ProgramService class,
 * specifically ensuring that its read operations interact with the database
 * as expected.
 *
 * List of method tests:
 *
 * - testFetchSemesterProgramDataSucceeds [DONE]
 * - testFetchSemesterProgramDataFails [DONE]
 * - testGetProgramInfoListQueryTrue [DONE]
 * - testGetProgramInfoListQueryFalse [DONE]
 *
 * @covers \App\services\database\troublelog\read\ProgramService
 */
class ProgramServiceReadTest extends TestCase
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
     * Partial mock of ProgramService.
     *
     * @var ProgramService
     */
    private $srvMock;

    /**
     * TEST METHOD 1: fetchSemesterProgramData
     */

    /**
     * Tests that fetchSemesterProgramData successfully retrieves data.
     *
     * @covers \App\services\database\troublelog\read\ProgramService::fetchSemesterProgramData
     *
     * @return void
     */
    public function testfetchSemesterProgramDataSucceeds(): void
    {
        // Define the test data
        $data = $this->createTestData(true);
        $data['query']['resultType'] = true;
        $data['query']['result'] = $data['successResult'];

        // Arrange
        $this->arrangeFetchDataWithQueryExpectations($data['query']);

        // Act
        $result = $this->srvMock->fetchSemesterProgramData($data['semester']);

        // Assert
        $this->assertFetchDataWithQueryExpectations($result, $data['query']);
    }

    /**
     * Tests that fetchSemesterProgramData fails gracefully when no data is found.
     *
     * @covers \App\services\database\troublelog\read\ProgramService::fetchSemesterProgramData
     *
     * @return void
     */
    public function testfetchSemesterProgramDataFails(): void
    {
        // Define the test data
        $data = $this->createTestData(true);
        $data['query']['resultType'] = true;
        $data['query']['result'] = $data['failureResult'];

        // Arrange
        $this->arrangeFetchDataWithQueryExpectations($data['query']);

        // Act
        $result = $this->srvMock->fetchSemesterProgramData($data['semester']);

        // Assert
        $this->assertFetchDataWithQueryExpectations($result, $data['query']);
    }

    /**
     * TEST METHOD 2: getProgramInfoListQuery [PROTECTED]
     */

    /**
     *
     * @covers \App\services\database\troublelog\read\ProgramService::getProgramInfoListQuery
     *
     * @return void
     */
    public function testGetProgramInfoListQueryTrue(): void
    {
        // Define the test data
        $data = $this->createTestData(true);

        // Arrange
        $service = new TestProgramService(false, $this->dbMock, $this->debugMock);

        // Act
        $query = $service->getProgramInfoListQueryProxy(true);

        // Assert
        $expect = $data['query']['sql'];
        $this->assertSame($expect, $query);
    }

    /**
     *
     * @covers \App\services\database\troublelog\read\ProgramService::getProgramInfoListQuery
     *
     * @return void
     */
    public function testGetProgramInfoListQueryFalse(): void
    {
        // Define the test data
        $data = $this->createTestData(false);

        // Arrange
        $service = new TestProgramService(false, $this->dbMock, $this->debugMock);

        // Act
        $query = $service->getProgramInfoListQueryProxy(false);

        // Assert
        $expect = $data['query']['sql'];
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
            ProgramService::class,
            [false, $this->dbMock, $this->debugMock],
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
    private function createTestData(bool $sort): array
    {
        // Set up the test data
        $data = [
            // test inputs (data values for testing)
            'semester' => '2023B',
            'sort' => $sort,
            // test outputs (method return values, etc)
            'successResult' => [ // Expected result for successful record retrieval
                [
                    'programID' => 20,
                    'projectPI' => 'Giles',
                ],
                [
                    'programID' => 52,
                    'projectPI' => 'Fry',
                ],
                [
                    'programID' => 70,
                    'projectPI' => 'Dahl',
                ],
            ],
            'failureResult' => [], // Expected result for record retrieval failure
        ];
        // Expected results for each query part
        $data['query'] = $this->createTestQueryParts($data);
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
    private function createTestQueryParts(array $data): array
    {
        return [
            // Query's SQL string
            'sql' => "SELECT programID, projectPI FROM Program WHERE semesterID = ? AND programID > 000 "
                . "AND programID < 900 ORDER BY programID " . ($data['sort'] ? 'ASC' : 'DESC') . ";",
            // Query's params array
            'params' => [$data['semester']],
            // Query's params types string
            'types' => 's',
            // Query's failure error message
            'errorMsg' => 'No programs found.',
        ];
    }
}
