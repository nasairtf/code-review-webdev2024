<?php

declare(strict_types=1);

namespace Tests\classes\services\database\troublelog\read;

use Mockery;
use PHPUnit\Framework\TestCase;
use Tests\utilities\UnitTestTeardownTrait;
use Tests\utilities\CustomDebugMockTrait;
use Tests\utilities\DBConnectionMockTrait;
use Tests\utilities\DatabaseServiceMockTrait;
use Tests\classes\services\database\troublelog\read\TestGuestAcctsService;
use App\services\database\troublelog\read\GuestAcctsService;
use App\exceptions\DatabaseException;

/**
 * Unit tests for the GuestAcctsService read class.
 *
 * This test suite validates the behavior of the GuestAcctsService class,
 * specifically ensuring that its read operations interact with the database
 * as expected.
 *
 * List of method tests:
 *
 * - testFetchGuestAccountDataSucceeds [DONE]
 * - testFetchGuestAccountDataFails [DONE]
 * - testFetchProgramSessionDataSucceeds [DONE]
 * - testFetchProgramSessionDataFails [DONE]
 * - testFetchProgramValidationSucceeds [DONE]
 * - testFetchProgramValidationFails [DONE]
 * - testGetSemesterProgramsQuery [DONE]
 * - testGetSingleProgramSessionQuery [DONE]
 * - testGetValidateProgramQuery [DONE]
 *
 * @covers \App\services\database\troublelog\read\GuestAcctsService
 */
class GuestAcctsServiceReadTest extends TestCase
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
     * Partial mock of GuestAcctsService.
     *
     * @var GuestAcctsService
     */
    private $srvMock;

    /**
     * TEST METHOD 1: fetchGuestAccountData
     */

    /**
     * Tests that fetchGuestAccountData successfully retrieves data.
     *
     * @covers \App\services\database\troublelog\read\GuestAcctsService::fetchGuestAccountData
     *
     * @return void
     */
    public function testFetchGuestAccountDataSucceeds(): void
    {
        // Define the test data
        $data = $this->createTestData('semester');
        $data['query']['resultType'] = true;

        // Arrange
        $this->arrangeFetchDataWithQueryExpectations($data['query']);

        // Act
        $result = $this->srvMock->fetchGuestAccountData($data['semester']);

        // Assert
        $this->assertFetchDataWithQueryExpectations($result, $data['query']);
    }

    /**
     * Tests that fetchGuestAccountData fails gracefully when no data is found.
     *
     * @covers \App\services\database\troublelog\read\GuestAcctsService::fetchGuestAccountData
     *
     * @return void
     */
    public function testFetchGuestAccountDataFails(): void
    {
        // Define the test data
        $data = $this->createTestData('semester');
        $data['query']['resultType'] = true;
        $data['query']['result'] = $data['failureResult'];

        // Arrange
        $this->arrangeFetchDataWithQueryExpectations($data['query']);

        // Act
        $result = $this->srvMock->fetchGuestAccountData($data['semester']);

        // Assert
        $this->assertFetchDataWithQueryExpectations($result, $data['query']);
    }

    /**
     * TEST METHOD 2: fetchProgramSessionData
     */

    /**
     * Tests that fetchProgramSessionData successfully retrieves data.
     *
     * @covers \App\services\database\troublelog\read\GuestAcctsService::fetchProgramSessionData
     *
     * @return void
     */
    public function testFetchProgramSessionDataSucceeds(): void
    {
        // Define the test data
        $data = $this->createTestData('program');
        $data['query']['resultType'] = true;

        // Arrange
        $this->arrangeFetchDataWithQueryExpectations($data['query']);

        // Act
        $result = $this->srvMock->fetchProgramSessionData($data['program'], $data['session']);

        // Assert
        $this->assertFetchDataWithQueryExpectations($result, $data['query']);
    }

    /**
     * Tests that fetchProgramSessionData fails gracefully when no data is found.
     *
     * @covers \App\services\database\troublelog\read\GuestAcctsService::fetchProgramSessionData
     *
     * @return void
     */
    public function testFetchProgramSessionDataFails(): void
    {
        // Define the test data
        $data = $this->createTestData('program');
        $data['query']['resultType'] = true;
        $data['query']['result'] = $data['failureResult'];

        // Arrange
        $this->arrangeFetchDataWithQueryExpectations($data['query']);

        // Act
        $result = $this->srvMock->fetchProgramSessionData($data['program'], $data['session']);

        // Assert
        $this->assertFetchDataWithQueryExpectations($result, $data['query']);
    }

    /**
     * TEST METHOD 3: fetchProgramValidation
     */

    /**
     * Tests that fetchProgramValidation successfully retrieves data.
     *
     * @covers \App\services\database\troublelog\read\GuestAcctsService::fetchProgramValidation
     *
     * @return void
     */
    public function testFetchProgramValidationSucceeds(): void
    {
        // Define the test data
        $data = $this->createTestData('validate');
        $data['query']['resultType'] = true;

        // Arrange
        $this->arrangeExecuteSelectQueryExpectations($data['query']);

        // Act
        $result = $this->srvMock->fetchProgramValidation($data['program'], $data['session']);

        // Assert
        $this->assertExecuteSelectQueryExpectations($result, $data['query']);
    }

    /**
     * Tests that fetchProgramValidation fails gracefully when no data is found.
     *
     * @covers \App\services\database\troublelog\read\GuestAcctsService::fetchProgramValidation
     *
     * @return void
     */
    public function testFetchProgramValidationFails(): void
    {
        // Define the test data
        $data = $this->createTestData('validate');
        $data['query']['resultType'] = true;
        $data['query']['result'] = $data['invalid'];

        // Arrange
        $this->arrangeExecuteSelectQueryExpectations($data['query']);

        // Act
        $result = $this->srvMock->fetchProgramValidation($data['program'], $data['session']);

        // Assert
        $this->assertExecuteSelectQueryExpectations($result, $data['query']);
    }

    /**
     * TEST METHOD 4: getSemesterProgramsQuery [PROTECTED]
     */

    /**
     * Tests SQL query generation for fetching semester programs.
     *
     * @covers \App\services\database\troublelog\read\GuestAcctsService::getSemesterProgramsQuery
     *
     * @return void
     */
    public function testGetSemesterProgramsQuery(): void
    {
        // Define the test data
        $data = $this->createTestData('semester');

        // Arrange
        $service = new TestGuestAcctsService(false, $this->dbMock, $this->debugMock);

        // Act
        $query = $service->getSemesterProgramsQueryProxy();

        // Assert
        $expect = $data['query']['sql'];
        $this->assertSame($expect, $query);
    }

    /**
     * TEST METHOD 5: getSingleProgramSessionQuery [PROTECTED]
     */

    /**
     * Tests SQL query generation for fetching single program sessions.
     *
     * @covers \App\services\database\troublelog\read\GuestAcctsService::getSingleProgramSessionQuery
     *
     * @return void
     */
    public function testGetSupportAstronomersQuerySortAscStatusFalse(): void
    {
        // Define the test data
        $data = $this->createTestData('program');

        // Arrange
        $service = new TestGuestAcctsService(false, $this->dbMock, $this->debugMock);

        // Act
        $query = $service->getSingleProgramSessionQueryProxy();

        // Assert
        $expect = $data['query']['sql'];
        $this->assertSame($expect, $query);
    }

    /**
     * TEST METHOD 6: getValidateProgramQuery [PROTECTED]
     */

    /**
     * Tests SQL query generation for program validation.
     *
     * @covers \App\services\database\troublelog\read\GuestAcctsService::getValidateProgramQuery
     *
     * @return void
     */
    public function testGetValidateProgramQuery(): void
    {
        // Define the test data
        $data = $this->createTestData('validate');

        // Arrange
        $service = new TestGuestAcctsService(false, $this->dbMock, $this->debugMock);

        // Act
        $query = $service->getValidateProgramQueryProxy();

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
            GuestAcctsService::class,
            [false, $this->dbMock, $this->debugMock],
            ['fetchDataWithQuery', 'executeSelectQuery']
        );
    }

    /**
     * Creates an array containing the standard test data for this test unit suite.
     * Can be overridden locally in individual tests.
     *
     * @return array Array of test data arrays.
     */
    private function createTestData(string $type = 'validate'): array
    {
        // Set up the test data
        $data = [
            // test inputs (data values for testing)
            'semester' => '2024B',
            'program' => '2024B072',
            'session' => 'FDB699D4AR',
            'type' => $type,
            // test outputs (method return values, etc)
            'failureResult' => [], // Expected result for record retrieval failure
            'invalid' => [0],      // Expected result for record retrieval failure
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
     *
     * @return array An associative array with the following keys:
     *               - 'sql' (string): The SQL query string.
     *               - 'params' (array): The parameters to bind to the query.
     *               - 'types' (string): The types of the query parameters.
     *               - 'errorMsg' (string): The message to output for query errors.
     */
    private function createTestQueryParts(array $data): array
    {
        $result = [
            [
                'username' => '2024B072',
                'defaultpwd' => 'FDB699D4AR',
            ],
        ];
        switch ($data['type']) {
            case 'semester':
                $query = "SELECT username as program, defaultpwd as session FROM GuestAccts "
                    . "WHERE username LIKE BINARY ?;";
                $params = [$data['semester'] . '%'];
                $types = 's';
                $error = 'No programs found.';
                $result[] = [
                    'username' => '2024B074',
                    'defaultpwd' => 'AFBDHR5LKY',
                ];
                break;

            case 'program':
                $query = "SELECT username as program, defaultpwd as session FROM GuestAccts "
                    . "WHERE username = ? AND defaultpwd LIKE BINARY ?;";
                $params = [$data['program'], $data['session'] . '%'];
                $types = 'ss';
                $error = 'No programs found.';
                break;

            case 'validate':
                $query = "SELECT COUNT(*) AS count FROM GuestAccts WHERE username = ? AND defaultpwd LIKE BINARY ?;";
                $params = [$data['program'], $data['session'] . '%'];
                $types = 'ss';
                $result = [1];
                $error = '';
                break;
        }
        return [
            // Query's SQL string
            'sql' => $query,
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
