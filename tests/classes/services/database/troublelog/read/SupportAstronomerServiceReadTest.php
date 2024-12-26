<?php

declare(strict_types=1);

namespace Tests\classes\services\database\troublelog\read;

use Mockery;
use PHPUnit\Framework\TestCase;
use Tests\utilities\CustomDebugMockTrait;
use Tests\utilities\DBConnectionMockTrait;
use Tests\utilities\DatabaseServiceMockTrait;
use Tests\classes\services\database\troublelog\read\TestSupportAstronomerService;
use App\services\database\troublelog\read\SupportAstronomerService;
use App\exceptions\DatabaseException;

/**
 * Unit tests for the SupportAstronomerService read class.
 *
 * This test suite validates the behavior of the SupportAstronomerService class,
 * specifically ensuring that its read operations interact with the database
 * as expected.
 *
 * List of method tests:
 *
 * - testFetchFullSupportAstronomerDataSucceeds [DONE]
 * - testFetchFullSupportAstronomerDataFails [DONE]
 * - testFetchSupportAstronomerDataSucceeds [DONE]
 * - testFetchSupportAstronomerDataFails [DONE]
 * - testGetSupportAstronomersQuerySortAscStatusTrue [DONE]
 * - testGetSupportAstronomersQuerySortAscStatusFalse [DONE]
 * - testGetSupportAstronomersQuerySortDescStatusTrue [DONE]
 * - testGetSupportAstronomersQuerySortDescStatusFalse [DONE]
 *
 * @covers \App\services\database\troublelog\read\SupportAstronomerService
 */
class SupportAstronomerServiceReadTest extends TestCase
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
     * Partial mock of SupportAstronomerService.
     *
     * @var SupportAstronomerService
     */
    private $srvMock;

    /**
     * TEST METHOD 1: fetchFullSupportAstronomerData
     */

    /**
     * Tests that fetchFullSupportAstronomerData successfully retrieves data.
     *
     * @covers \App\services\database\troublelog\read\SupportAstronomerService::fetchFullSupportAstronomerData
     *
     * @return void
     */
    public function testFetchFullSupportAstronomerDataSucceeds(): void
    {
        // Define the test data
        $data = $this->createTestData(true, false);
        $data['query']['resultType'] = true;
        $data['query']['result'] = $data['successResult'];

        // Arrange
        $this->arrangeFetchDataWithQueryExpectations($data['query']);

        // Act
        $result = $this->srvMock->fetchFullSupportAstronomerData();

        // Assert
        $this->assertFetchDataWithQueryExpectations($result, $data['query']);
    }

    /**
     * Tests that fetchFullSupportAstronomerData fails gracefully when no data is found.
     *
     * @covers \App\services\database\troublelog\read\SupportAstronomerService::fetchFullSupportAstronomerData
     *
     * @return void
     */
    public function testFetchFullSupportAstronomerDataFails(): void
    {
        // Define the test data
        $data = $this->createTestData(true, false);
        $data['query']['resultType'] = true;
        $data['query']['result'] = $data['failureResult'];

        // Arrange
        $this->arrangeFetchDataWithQueryExpectations($data['query']);

        // Act
        $result = $this->srvMock->fetchFullSupportAstronomerData();

        // Assert
        $this->assertFetchDataWithQueryExpectations($result, $data['query']);
    }

    /**
     * TEST METHOD 2: fetchSupportAstronomerData
     */

    /**
     * Tests that fetchSupportAstronomerData successfully retrieves data.
     *
     * @covers \App\services\database\troublelog\read\SupportAstronomerService::fetchSupportAstronomerData
     *
     * @return void
     */
    public function testFetchSupportAstronomerDataSucceeds(): void
    {
        // Define the test data
        $data = $this->createTestData(true, true);
        $data['query']['resultType'] = true;
        $data['query']['result'] = [$data['successResult'][1]];

        // Arrange
        $this->arrangeFetchDataWithQueryExpectations($data['query']);

        // Act
        $result = $this->srvMock->fetchSupportAstronomerData();

        // Assert
        $this->assertFetchDataWithQueryExpectations($result, $data['query']);
    }

    /**
     * Tests that fetchSupportAstronomerData fails gracefully when no data is found.
     *
     * @covers \App\services\database\troublelog\read\SupportAstronomerService::fetchSupportAstronomerData
     *
     * @return void
     */
    public function testFetchSupportAstronomerDataFails(): void
    {
        // Define the test data
        $data = $this->createTestData(true, true);
        $data['query']['resultType'] = true;
        $data['query']['result'] = $data['failureResult'];

        // Arrange
        $this->arrangeFetchDataWithQueryExpectations($data['query']);

        // Act
        $result = $this->srvMock->fetchSupportAstronomerData();

        // Assert
        $this->assertFetchDataWithQueryExpectations($result, $data['query']);
    }

    /**
     * TEST METHOD 3: getSupportAstronomersQuery [PROTECTED]
     */

    /**
     *
     * @covers \App\services\database\troublelog\read\SupportAstronomerService::getSupportAstronomersQuery
     *
     * @return void
     */
    public function testGetSupportAstronomersQuerySortAscStatusTrue(): void
    {
        // Define the test data
        $data = $this->createTestData(true, true);

        // Arrange
        $service = new TestSupportAstronomerService(false, $this->dbMock, $this->debugMock);

        // Act
        $query = $service->getSupportAstronomersQueryProxy(true, true);

        // Assert
        $expect = $data['query']['sql'];
        $this->assertSame($expect, $query);
    }

    /**
     *
     * @covers \App\services\database\troublelog\read\SupportAstronomerService::getSupportAstronomersQuery
     *
     * @return void
     */
    public function testGetSupportAstronomersQuerySortAscStatusFalse(): void
    {
        // Define the test data
        $data = $this->createTestData(true, false);

        // Arrange
        $service = new TestSupportAstronomerService(false, $this->dbMock, $this->debugMock);

        // Act
        $query = $service->getSupportAstronomersQueryProxy(true, false);

        // Assert
        $expect = $data['query']['sql'];
        $this->assertSame($expect, $query);
    }

    /**
     *
     * @covers \App\services\database\troublelog\read\SupportAstronomerService::getSupportAstronomersQuery
     *
     * @return void
     */
    public function testGetSupportAstronomersQuerySortDescStatusTrue(): void
    {
        // Define the test data
        $data = $this->createTestData(false, true);

        // Arrange
        $service = new TestSupportAstronomerService(false, $this->dbMock, $this->debugMock);

        // Act
        $query = $service->getSupportAstronomersQueryProxy(false, true);

        // Assert
        $expect = $data['query']['sql'];
        $this->assertSame($expect, $query);
    }

    /**
     *
     * @covers \App\services\database\troublelog\read\SupportAstronomerService::getSupportAstronomersQuery
     *
     * @return void
     */
    public function testGetSupportAstronomersQuerySortDescStatusFalse(): void
    {
        // Define the test data
        $data = $this->createTestData(false, false);

        // Arrange
        $service = new TestSupportAstronomerService(false, $this->dbMock, $this->debugMock);

        // Act
        $query = $service->getSupportAstronomersQueryProxy(false, false);

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
            SupportAstronomerService::class,
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
    private function createTestData(bool $sortAsc, bool $status): array
    {
        // Set up the test data
        $data = [
            // test inputs (data values for testing)
            'sortAsc' => $sortAsc,
            'status' => $status,
            // test outputs (method return values, etc)
            'successResult' => [ // Expected result for successful record retrieval
                [
                    'saRecordID' => 1,
                    'SupportAstronomerID' => 'ATT',
                    'firstName' => 'Alan',
                    'lastName' => 'Tokunaga',
                    'status' => 0,
                    'supportCode' => 'AT',
                    'pulldownIndex' => null,
                ],
                [
                    'saRecordID' => 2,
                    'SupportAstronomerID' => 'JTR',
                    'firstName' => 'John',
                    'lastName' => 'Rayner',
                    'status' => 1,
                    'supportCode' => 'JR',
                    'pulldownIndex' => 3,
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
        $where = $data['status'] ? "WHERE status = '1' " : '';
        $sort = $data['sortAsc'] ? 'ASC' : 'DESC';
        return [
            // Query's SQL string
            'sql' => "SELECT * FROM SupportAstronomer {$where}ORDER BY lastName {$sort};",
            // Query's params array
            'params' => [],
            // Query's params types string
            'types' => '',
            // Query's failure error message
            'errorMsg' => 'No support astronomers found.',
        ];
    }
}
