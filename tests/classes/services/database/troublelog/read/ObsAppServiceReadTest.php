<?php

declare(strict_types=1);

namespace Tests\classes\services\database\troublelog\read;

use Mockery;
use PHPUnit\Framework\TestCase;
use Tests\utilities\helpers\UnitTestSetupTrait;
use Tests\utilities\helpers\UnitTestTeardownTrait;
use Tests\utilities\mocks\MockDebugTrait;
use Tests\utilities\mocks\MockDBConnectionTrait;
use Tests\utilities\mocks\MockDatabaseServiceFetchDataWithQueryTrait;
use Tests\classes\services\database\troublelog\read\TestObsAppService;
use App\services\database\troublelog\read\ObsAppService;
use App\exceptions\DatabaseException;

/**
 * Unit tests for the ObsAppService read class.
 *
 * This test suite validates the behavior of the ObsAppService class,
 * specifically ensuring that its read operations interact with the database
 * as expected.
 *
 * List of method tests:
 *
 * - testFetchSemesterProposalListingFormDataSucceeds [DONE]
 * - testFetchSemesterProposalListingFormDataFails [DONE]
 * - testFetchProposalListingFormDataSucceeds [DONE]
 * - testFetchProposalListingFormDataFails [DONE]
 * - testFetchScheduleSemesterProgramListSucceeds [DONE]
 * - testFetchScheduleSemesterProgramListFails [DONE]
 * - testFetchProposalProgramDataSucceeds [DONE]
 * - testFetchProposalProgramDataFails [DONE]
 * - testGetScheduleSemesterProgramListQuery [DONE]
 * - testGetProposalQueryConditionProgram [DONE]
 * - testGetProposalQueryConditionSemester [DONE]
 * - testGetProposalQueryConditionSession [DONE]
 *
 * @covers \App\services\database\troublelog\read\ObsAppService
 */
class ObsAppServiceReadTest extends TestCase
{
    use UnitTestSetupTrait;
    use UnitTestTeardownTrait;
    use MockDebugTrait;
    use MockDBConnectionTrait;
    use MockDatabaseServiceFetchDataWithQueryTrait;

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
     * Partial mock of ObsAppService.
     *
     * @var ObsAppService
     */
    private $srvMock;

    /**
     * TEST METHOD 1: fetchSemesterProposalListingFormData
     */

    /**
     * Tests that fetchSemesterProposalListingFormData successfully retrieves data.
     *
     * @covers \App\services\database\troublelog\read\ObsAppService::fetchSemesterProposalListingFormData
     *
     * @return void
     */
    public function testFetchSemesterProposalListingFormDataSucceeds(): void
    {
        // Define the test data
        $data = $this->createTestData('semester');
        $data['query']['resultType'] = true;

        // Arrange
        $this->arrangeFetchDataWithQueryExpectations($data['query']);

        // Act
        $result = $this->srvMock->fetchSemesterProposalListingFormData($data['year'], $data['semester']);

        // Assert
        $this->assertFetchDataWithQueryExpectations($result, $data['query']);
    }

    /**
     * Tests that fetchSemesterProposalListingFormData fails gracefully when no data is found.
     *
     * @covers \App\services\database\troublelog\read\ObsAppService::fetchSemesterProposalListingFormData
     *
     * @return void
     */
    public function testFetchSemesterProposalListingFormDataFails(): void
    {
        // Define the test data
        $data = $this->createTestData('semester');
        $data['query']['resultType'] = true;
        $data['query']['result'] = $data['failureResult'];

        // Arrange
        $this->arrangeFetchDataWithQueryExpectations($data['query']);

        // Act
        $result = $this->srvMock->fetchSemesterProposalListingFormData($data['year'], $data['semester']);

        // Assert
        $this->assertFetchDataWithQueryExpectations($result, $data['query']);
    }

    /**
     * TEST METHOD 2: fetchProposalListingFormData
     */

    /**
     * Tests that fetchProposalListingFormData successfully retrieves data.
     *
     * @covers \App\services\database\troublelog\read\ObsAppService::fetchProposalListingFormData
     *
     * @return void
     */
    public function testFetchProposalListingFormDataSucceeds(): void
    {
        // Define the test data
        $data = $this->createTestData('session');
        $data['query']['resultType'] = true;

        // Arrange
        $this->arrangeFetchDataWithQueryExpectations($data['query']);

        // Act
        $result = $this->srvMock->fetchProposalListingFormData($data['ObsApp_id']);

        // Assert
        $this->assertFetchDataWithQueryExpectations($result, $data['query']);
    }

    /**
     * Tests that fetchProposalListingFormData fails gracefully when no data is found.
     *
     * @covers \App\services\database\troublelog\read\ObsAppService::fetchProposalListingFormData
     *
     * @return void
     */
    public function testFetchProposalListingFormDataFails(): void
    {
        // Define the test data
        $data = $this->createTestData('session');
        $data['query']['resultType'] = true;
        $data['query']['result'] = $data['failureResult'];

        // Arrange
        $this->arrangeFetchDataWithQueryExpectations($data['query']);

        // Act
        $result = $this->srvMock->fetchProposalListingFormData($data['ObsApp_id']);

        // Assert
        $this->assertFetchDataWithQueryExpectations($result, $data['query']);
    }

    /**
     * TEST METHOD 3: fetchScheduleSemesterProgramList
     */

    /**
     * Tests that fetchScheduleSemesterProgramList successfully retrieves data.
     *
     * @covers \App\services\database\troublelog\read\ObsAppService::fetchScheduleSemesterProgramList
     *
     * @return void
     */
    public function testFetchScheduleSemesterProgramListSucceeds(): void
    {
        // Define the test data
        $data = $this->createTestData('schedule');
        $data['query']['resultType'] = true;

        // Arrange
        $this->arrangeFetchDataWithQueryExpectations($data['query']);

        // Act
        $result = $this->srvMock->fetchScheduleSemesterProgramList($data['year'], $data['semester']);

        // Assert
        $this->assertFetchDataWithQueryExpectations($result, $data['query']);
    }

    /**
     * Tests that fetchScheduleSemesterProgramList fails gracefully when no data is found.
     *
     * @covers \App\services\database\troublelog\read\ObsAppService::fetchScheduleSemesterProgramList
     *
     * @return void
     */
    public function testFetchScheduleSemesterProgramListFails(): void
    {
        // Define the test data
        $data = $this->createTestData('schedule');
        $data['query']['resultType'] = true;
        $data['query']['result'] = $data['failureResult'];

        // Arrange
        $this->arrangeFetchDataWithQueryExpectations($data['query']);

        // Act
        $result = $this->srvMock->fetchScheduleSemesterProgramList($data['year'], $data['semester']);

        // Assert
        $this->assertFetchDataWithQueryExpectations($result, $data['query']);
    }

    /**
     * TEST METHOD 4: fetchProposalProgramData
     */

    /**
     * Tests that fetchProposalProgramData successfully retrieves data.
     *
     * @covers \App\services\database\troublelog\read\ObsAppService::fetchProposalProgramData
     *
     * @return void
     */
    public function testFetchProposalProgramDataSucceeds(): void
    {
        // Define the test data
        $data = $this->createTestData('program');
        $data['query']['resultType'] = true;

        // Arrange
        $this->arrangeFetchDataWithQueryExpectations($data['query']);

        // Act
        $result = $this->srvMock->fetchProposalProgramData($data['year'], $data['semester'], $data['program']);

        // Assert
        $this->assertFetchDataWithQueryExpectations($result, $data['query']);
    }

    /**
     * Tests that fetchProposalProgramData fails gracefully when no data is found.
     *
     * @covers \App\services\database\troublelog\read\ObsAppService::fetchProposalProgramData
     *
     * @return void
     */
    public function testFetchProposalProgramDataFails(): void
    {
        // Define the test data
        $data = $this->createTestData('program');
        $data['query']['resultType'] = true;
        $data['query']['result'] = $data['failureResult'];

        // Arrange
        $this->arrangeFetchDataWithQueryExpectations($data['query']);

        // Act
        $result = $this->srvMock->fetchProposalProgramData($data['year'], $data['semester'], $data['program']);

        // Assert
        $this->assertFetchDataWithQueryExpectations($result, $data['query']);
    }

    /**
     * TEST METHOD 5: getScheduleSemesterProgramListQuery [PROTECTED]
     */

    /**
     *
     * @covers \App\services\database\troublelog\read\ObsAppService::getScheduleSemesterProgramListQuery
     *
     * @return void
     */
    public function testGetScheduleSemesterProgramListQuery(): void
    {
        // Define the test data
        $data = $this->createTestData('schedule');

        // Arrange
        $service = new TestObsAppService(false, $this->dbMock, $this->debugMock);

        // Act
        $query = $service->getScheduleSemesterProgramListQueryProxy();

        // Assert
        $expect = $data['query']['sql'];
        $this->assertSame($expect, $query);
    }

    /**
     * TEST METHOD 6: getProposalQuery [PROTECTED]
     */

    /**
     *
     * @covers \App\services\database\troublelog\read\ObsAppService::getProposalQuery
     *
     * @return void
     */
    public function testGetProposalQueryConditionProgram(): void
    {
        // Define the test data
        $data = $this->createTestData('program');

        // Arrange
        $service = new TestObsAppService(false, $this->dbMock, $this->debugMock);

        // Act
        $query = $service->getProposalQueryProxy($data['type']);

        // Assert
        $expect = $data['query']['sql'];
        $this->assertSame($expect, $query);
    }

    /**
     *
     * @covers \App\services\database\troublelog\read\ObsAppService::getProposalQuery
     *
     * @return void
     */
    public function testGetProposalQueryConditionSemester(): void
    {
        // Define the test data
        $data = $this->createTestData('semester');

        // Arrange
        $service = new TestObsAppService(false, $this->dbMock, $this->debugMock);

        // Act
        $query = $service->getProposalQueryProxy($data['type']);

        // Assert
        $expect = $data['query']['sql'];
        $this->assertSame($expect, $query);
    }

    /**
     *
     * @covers \App\services\database\troublelog\read\ObsAppService::getProposalQuery
     *
     * @return void
     */
    public function testGetProposalQueryConditionSession(): void
    {
        // Define the test data
        $data = $this->createTestData('session');

        // Arrange
        $service = new TestObsAppService(false, $this->dbMock, $this->debugMock);

        // Act
        $query = $service->getProposalQueryProxy($data['type']);

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
        // Ensure the standard test setup is executed
        $this->setUpForStandardTests();
        // Build the DatabaseService partial mock
        $this->setUpForDatabaseServiceTests(
            ObsAppService::class,
            [false, $this->dbMock, $this->debugMock],
            ['fetchDataWithQuery']
        );
    }

    /**
     * Creates an array containing the standard test data for this test unit suite.
     * Can be overridden locally in individual tests.
     *
     * @param string $type The query type ('schedule', 'program', 'semester', 'session').
     *
     * @return array The generated test data, including query components.
     */
    private function createTestData(string $type = 'session'): array
    {
        // Set up the test data
        $data = [
            // test inputs (data values for testing)
            'year' => 2022,
            'semester' => 'A',
            'ObsApp_id' => 2222,
            'program' => 12,
            'type' => $type,
            // test outputs (method return values, etc)
            'failureResult' => [], // Expected result for record retrieval failure
        ];
        // Expected results for each query part
        $data['query'] = $this->createTestQueryParts($data);
        return $data;
    }

    /**
     * Generates components for a SQL query for fetching semester or program data.
     *
     * This method determines the type of query to generate based on the 'type' key in the $data array,
     * delegating the query construction to the appropriate helper method.
     *
     * @param array $data An associative array containing:
     *                    - 'type' (string): The type of query ('schedule', 'program', 'semester', 'session').
     *                    - Additional data required for the query.
     *
     * @return array An associative array with the following keys:
     *               - 'sql' (string): The SQL query string.
     *               - 'params' (array): The parameters to bind to the query.
     *               - 'types' (string): The types of the query parameters.
     *               - 'errorMsg' (string): The message to output for query errors.
     */
    private function createTestQueryParts(array $data): array
    {
        return ($data['type'] === 'schedule')
            ? $this->buildScheduleQueryParts($data)
            : $this->buildProposalQueryParts($data);
    }

    /**
     * Generates query components for a schedule-based query.
     *
     * @param array $data An associative array containing:
     *                    - 'year' (int): The year of the schedule.
     *                    - 'semester' (string): The semester code.
     *
     * @return array An associative array of query components.
     */
    private function buildScheduleQueryParts(array $data): array
    {
        return [
            // Query's SQL string
            'sql' => "SELECT "
            .       "ProgramNumber AS programID, "
            .       "CONCAT(semesterYear,semesterCode) AS semesterID, "
            .       "InvLastName1 AS projectPI, "
            .       "CONCAT_WS(' ',InvFirstName1,InvLastName1) as projectMembers1, "
            .       "CONCAT_WS(' ',InvFirstName2,InvLastName2) as projectMembers2, "
            .       "CONCAT_WS(' ',InvFirstName3,InvLastName3) as projectMembers3, "
            .       "CONCAT_WS(' ',InvFirstName4,InvLastName4) as projectMembers4, "
            .       "CONCAT_WS(' ',InvFirstName5,InvLastName5) as projectMembers5, "
            .       "AdditionalCoInvs AS projectMembers6, "
            .       "PIEmail, "
            .       "PIName, "
            .       "NULL AS otherInfo "
            .   "FROM "
            .       "ObsApp "
            .   "WHERE "
            .       "semesterYear = ? AND "
            .       "semesterCode = ? AND "
            .       "ProgramNumber > 0 "
            .   "ORDER BY "
            .       "ProgramNumber ASC;",
            // Query's params array
            'params' => [$data['year'], $data['semester']],
            // Query's params types string
            'types' => 'is',
            // Query's failure error message
            'errorMsg' => 'No proposals found for the selected semester.',
            // Query's success result
            'result' => [
                [
                    'programID' => 12,
                    'semesterID' => '2022A',
                    'projectPI' => 'Giles',
                    'projectMembers1' => 'Rohini Giles',
                    'projectMembers2' => 'Thomas Greathouse',
                    'projectMembers3' => 'Therese Encrenaz',
                    'projectMembers4' => 'Amanda Brecht',
                    'projectMembers5' => 'Kandis Lea Jessup',
                    'projectMembers6' => '',
                    'PIEmail' => 'rgiles@swri.edu',
                    'PIName' => 'Rohini Giles',
                    'otherInfo' => null,
                ],
                [
                    'programID' => 16,
                    'semesterID' => '2022A',
                    'projectPI' => 'Han',
                    'projectMembers1' => 'Eunkyu Han',
                    'projectMembers2' => '',
                    'projectMembers3' => '',
                    'projectMembers4' => '',
                    'projectMembers5' => '',
                    'projectMembers6' => '',
                    'PIEmail' => 'eunkyu.han@utexas.edu',
                    'PIName' => 'Eunkyu Han',
                    'otherInfo' => null,
                ],
            ],
        ];
    }

    /**
     * Generates query components for program, semester, or session queries.
     *
     * @param array $data An associative array containing:
     *                    - 'type' (string): The query type ('program', 'semester', 'session').
     *                    - Additional data required for the query.
     *
     * @return array An associative array of query components.
     */
    private function buildProposalQueryParts(array $data): array
    {
        // proposal(s) query parts
        $fields = 'ObsApp_id, semesterYear, semesterCode, ProgramNumber, InvLastName1, code, creationDate';
        $where = "WHERE semesterYear = ? AND semesterCode = ?";
        $params = [$data['year'], $data['semester']];
        $types = 'is';
        $result = [
            [
                'ObsApp_id' => 2673,
                'semesterYear' => 2022,
                'semesterCode' => 'A',
                'ProgramNumber' => 12,
                'InvLastName1' => 'Giles',
                'code' => '55RNN4DFD4',
                'creationDate' => 1632881050,
            ],
        ];
        switch ($data['type']) {
            case 'program':
                $where .= " AND ProgramNumber = ?";
                $params[] = $data['program'];
                $types .= 'i';
                $error = 'No proposal found for the given program.';
                break;

            case 'semester':
                $error = 'No proposals found for the selected semester.';
                $result[] = [
                    'ObsApp_id' => 2672,
                    'semesterYear' => 2022,
                    'semesterCode' => 'A',
                    'ProgramNumber' => 0,
                    'InvLastName1' => 'Menchaoui',
                    'code' => 'YY3AEJTGKL',
                    'creationDate' => 1631116423,
                ];
                break;

            case 'session':
                $where = "WHERE ObsApp_id = ?";
                $params = [$data['ObsApp_id']];
                $types = 'i';
                $error = 'No proposal found for the selected session.';
                $result = [
                    [
                        'ObsApp_id' => 2222,
                        'semesterYear' => 2020,
                        'semesterCode' => 'A',
                        'ProgramNumber' => 66,
                        'InvLastName1' => 'Grant',
                        'code' => 'RJHLF6LNF2',
                        'creationDate' => 1569965720,
                    ],
                ];
                break;
        }
        return [
            // Query's SQL string
            'sql' => "SELECT {$fields} FROM ObsApp {$where} ORDER BY creationDate ASC;",
            // Query's params array
            'params' => $params,
            // Query's params types string
            'types' => $types,
            // Query's failure error message
            'errorMsg' => $error,
            // Query's success result
            'result' => $result,
        ];
    }
}
