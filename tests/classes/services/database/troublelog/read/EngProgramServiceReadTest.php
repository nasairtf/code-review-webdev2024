<?php

declare(strict_types=1);

namespace Tests\classes\services\database\troublelog\read;

use Mockery;
use PHPUnit\Framework\TestCase;
use Tests\utilities\UnitTestTeardownTrait;
use Tests\utilities\CustomDebugMockTrait;
use Tests\utilities\DBConnectionMockTrait;
use Tests\utilities\DatabaseServiceMockTrait;
use Tests\classes\services\database\troublelog\read\TestEngProgramService;
use App\services\database\troublelog\read\EngProgramService;
use App\exceptions\DatabaseException;

/**
 * Unit tests for the EngProgramService read class.
 *
 * This test suite validates the behavior of the EngProgramService class,
 * specifically ensuring that its read operations interact with the database
 * as expected.
 *
 * List of method tests:
 *
 * - testFetchProposalEngProgramDataSucceeds [DONE]
 * - testFetchProposalEngProgramDataFails [DONE]
 * - testGetProposalEngProgramDataQuery [DONE]
 *
 * @covers \App\services\database\troublelog\read\EngProgramService
 */
class EngProgramServiceReadTest extends TestCase
{
    use UnitTestTeardownTrait;
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
     * Partial mock of EngProgramService.
     *
     * @var EngProgramService
     */
    private $srvMock;

    /**
     * TEST METHOD 1: fetchProposalEngProgramData
     */

    /**
     * Tests that fetchProposalEngProgramData successfully retrieves data.
     *
     * @covers \App\services\database\troublelog\read\EngProgramService::fetchProposalEngProgramData
     *
     * @return void
     */
    public function testFetchProposalEngProgramDataSucceeds(): void
    {
        // Define the test data
        $data = $this->createTestData();
        $data['query']['resultType'] = true;
        $data['query']['result'] = $data['successResult'];

        // Arrange
        $this->arrangeFetchDataWithQueryExpectations($data['query']);

        // Act
        $result = $this->srvMock->fetchProposalEngProgramData($data['semester'], $data['program']);

        // Assert
        $this->assertFetchDataWithQueryExpectations($result, $data['query']);
    }

    /**
     * Tests that fetchProposalEngProgramData fails gracefully when no data is found.
     *
     * @covers \App\services\database\troublelog\read\EngProgramService::fetchProposalEngProgramData
     *
     * @return void
     */
    public function testFetchProposalEngProgramDataFails(): void
    {
        // Define the test data
        $data = $this->createTestData();
        $data['query']['resultType'] = true;
        $data['query']['result'] = $data['failureResult'];

        // Arrange
        $this->arrangeFetchDataWithQueryExpectations($data['query']);

        // Act
        $result = $this->srvMock->fetchProposalEngProgramData($data['semester'], $data['program']);

        // Assert
        $this->assertFetchDataWithQueryExpectations($result, $data['query']);
    }

    /**
     * TEST METHOD 2: getProposalEngProgramDataQuery [PROTECTED]
     */

    /**
     * Tests the SQL query generation for fetching program-specific data.
     *
     * This test ensures that the `getProposalEngProgramDataQuery` method in the
     * EngProgramService class generates the correct SQL query string for
     * retrieving program-specific data from the EngProgram table.
     *
     * @covers \App\services\database\troublelog\read\EngProgramService::getProposalEngProgramDataQuery
     *
     * @return void
     */
    public function testGetProposalEngProgramDataQuery(): void
    {
        // Define the test data
        $data = $this->createTestData();

        // Arrange
        $service = new TestEngProgramService(false, $this->dbMock, $this->debugMock);

        // Act
        $query = $service->getProposalEngProgramDataQueryProxy();

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
            EngProgramService::class,
            [false, $this->dbMock, $this->debugMock],
            ['fetchDataWithQuery']
        );
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
            'semester' => '2023B',
            'program' => 992,
            // test outputs (method return values, etc)
            'successResult' => [ // Expected result for successful record retrieval
                [
                    'semesterID' => '2023B',
                    'programID' => 992,
                    'projectPI' => 'Emerson',
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
            'sql' => "SELECT semesterID, programID, projectPI FROM EngProgram WHERE semesterID = ? AND programID = ?;",
            // Query's params array
            'params' => [$data['semester'], $data['program']],
            // Query's params types string
            'types' => 'si',
            // Query's failure error message
            'errorMsg' => 'No record found for the given program.',
        ];
    }
}
