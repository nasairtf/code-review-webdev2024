<?php

declare(strict_types=1);

namespace Tests\classes\services\database\troublelog\read;

use Mockery;
use PHPUnit\Framework\TestCase;
use Tests\utilities\helpers\UnitTestTeardownTrait;
use Tests\utilities\mocks\MockDebugTrait;
use Tests\utilities\mocks\MockDBConnectionTrait;
use Tests\utilities\mocks\MockDatabaseServiceFetchDataWithQueryTrait;
use Tests\classes\services\database\troublelog\read\TestOperatorService;
use App\services\database\troublelog\read\OperatorService;
use App\exceptions\DatabaseException;

/**
 * Unit tests for the OperatorService read class.
 *
 * This test suite validates the behavior of the OperatorService class,
 * specifically ensuring that its read operations interact with the database
 * as expected.
 *
 * List of method tests:
 *
 * - testFetchFullOperatorDataSucceeds [DONE]
 * - testFetchFullOperatorDataFails [DONE]
 * - testFetchOperatorDataSucceeds [DONE]
 * - testFetchOperatorDataFails [DONE]
 * - testFetchAssistantDataSucceeds [DONE]
 * - testFetchAssistantDataFails [DONE]
 * - testGetAllOperatorsListQuerySortAscTrue [DONE]
 * - testGetAllOperatorsListQuerySortAscFalse [DONE]
 * - testGetTelescopeOperatorsListQuerySortAscTrue [DONE]
 * - testGetTelescopeOperatorsListQuerySortAscFalse [DONE]
 * - testGetObservatoryAssistantsListQuerySortAscTrue [DONE]
 * - testGetObservatoryAssistantsListQuerySortAscFalse [DONE]
 *
 * @covers \App\services\database\troublelog\read\OperatorService
 */
class OperatorServiceReadTest extends TestCase
{
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
     * Partial mock of OperatorService.
     *
     * @var OperatorService
     */
    private $srvMock;

    /**
     * TEST METHOD 1: fetchFullOperatorData
     */

    /**
     * Tests that fetchFullOperatorData successfully retrieves data.
     *
     * @covers \App\services\database\troublelog\read\OperatorService::fetchFullOperatorData
     *
     * @return void
     */
    public function testFetchFullOperatorDataSucceeds(): void
    {
        // Define the test data
        $data = $this->createTestData('list', true);
        $data['query']['resultType'] = true;
        $data['query']['result'] = $data['successResult'];

        // Arrange
        $this->arrangeFetchDataWithQueryExpectations($data['query']);

        // Act
        $result = $this->srvMock->fetchFullOperatorData();

        // Assert
        $this->assertFetchDataWithQueryExpectations($result, $data['query']);
    }

    /**
     * Tests that fetchFullOperatorData fails gracefully when no data is found.
     *
     * @covers \App\services\database\troublelog\read\OperatorService::fetchFullOperatorData
     *
     * @return void
     */
    public function testFetchFullOperatorDataFails(): void
    {
        // Define the test data
        $data = $this->createTestData('list', true);
        $data['query']['resultType'] = true;
        $data['query']['result'] = $data['failureResult'];

        // Arrange
        $this->arrangeFetchDataWithQueryExpectations($data['query']);

        // Act
        $result = $this->srvMock->fetchFullOperatorData();

        // Assert
        $this->assertFetchDataWithQueryExpectations($result, $data['query']);
    }

    /**
     * TEST METHOD 2: fetchOperatorData
     */

    /**
     * Tests that fetchOperatorData successfully retrieves data.
     *
     * @covers \App\services\database\troublelog\read\OperatorService::fetchOperatorData
     *
     * @return void
     */
    public function testFetchOperatorDataSucceeds(): void
    {
        // Define the test data
        $data = $this->createTestData('to', true);
        $data['query']['resultType'] = true;
        $data['query']['result'] = [$data['successResult'][1]];

        // Arrange
        $this->arrangeFetchDataWithQueryExpectations($data['query']);

        // Act
        $result = $this->srvMock->fetchOperatorData();

        // Assert
        $this->assertFetchDataWithQueryExpectations($result, $data['query']);
    }

    /**
     * Tests that fetchOperatorData fails gracefully when no data is found.
     *
     * @covers \App\services\database\troublelog\read\OperatorService::fetchOperatorData
     *
     * @return void
     */
    public function testFetchOperatorDataFails(): void
    {
        // Define the test data
        $data = $this->createTestData('to', true);
        $data['query']['resultType'] = true;
        $data['query']['result'] = $data['failureResult'];

        // Arrange
        $this->arrangeFetchDataWithQueryExpectations($data['query']);

        // Act
        $result = $this->srvMock->fetchOperatorData();

        // Assert
        $this->assertFetchDataWithQueryExpectations($result, $data['query']);
    }

    /**
     * TEST METHOD 3: fetchAssistantData
     */

    /**
     * Tests that fetchAssistantData successfully retrieves data.
     *
     * @covers \App\services\database\troublelog\read\OperatorService::fetchAssistantData
     *
     * @return void
     */
    public function testFetchAssistantDataSucceeds(): void
    {
        // Define the test data
        $data = $this->createTestData('ooa', true);
        $data['query']['resultType'] = true;
        $data['query']['result'] = [$data['successResult'][0]];

        // Arrange
        $this->arrangeFetchDataWithQueryExpectations($data['query']);

        // Act
        $result = $this->srvMock->fetchAssistantData();

        // Assert
        $this->assertFetchDataWithQueryExpectations($result, $data['query']);
    }

    /**
     * Tests that fetchAssistantData fails gracefully when no data is found.
     *
     * @covers \App\services\database\troublelog\read\OperatorService::fetchAssistantData
     *
     * @return void
     */
    public function testFetchAssistantDataFails(): void
    {
        // Define the test data
        $data = $this->createTestData('ooa', true);
        $data['query']['resultType'] = true;
        $data['query']['result'] = $data['failureResult'];

        // Arrange
        $this->arrangeFetchDataWithQueryExpectations($data['query']);

        // Act
        $result = $this->srvMock->fetchAssistantData();

        // Assert
        $this->assertFetchDataWithQueryExpectations($result, $data['query']);
    }

    /**
     * TEST METHOD 4: getAllOperatorsListQuery [PROTECTED]
     */

    /**
     * Tests SQL query generation for fetching semester programs.
     *
     * @covers \App\services\database\troublelog\read\OperatorService::getAllOperatorsListQuery
     *
     * @return void
     */
    public function testGetAllOperatorsListQuerySortAscTrue(): void
    {
        // Define the test data
        $data = $this->createTestData('list', true);

        // Arrange
        $service = new TestOperatorService(false, $this->dbMock, $this->debugMock);

        // Act
        $query = $service->getAllOperatorsListQueryProxy(true);

        // Assert
        $expect = $data['query']['sql'];
        $this->assertSame($expect, $query);
    }

    /**
     * Tests SQL query generation for fetching semester programs.
     *
     * @covers \App\services\database\troublelog\read\OperatorService::getAllOperatorsListQuery
     *
     * @return void
     */
    public function testGetAllOperatorsListQuerySortAscFalse(): void
    {
        // Define the test data
        $data = $this->createTestData('list', false);

        // Arrange
        $service = new TestOperatorService(false, $this->dbMock, $this->debugMock);

        // Act
        $query = $service->getAllOperatorsListQueryProxy(false);

        // Assert
        $expect = $data['query']['sql'];
        $this->assertSame($expect, $query);
    }

    /**
     * TEST METHOD 5: getTelescopeOperatorsListQuery [PROTECTED]
     */

    /**
     * Tests SQL query generation for fetching semester programs.
     *
     * @covers \App\services\database\troublelog\read\OperatorService::getTelescopeOperatorsListQuery
     *
     * @return void
     */
    public function testGetTelescopeOperatorsListQuerySortAscTrue(): void
    {
        // Define the test data
        $data = $this->createTestData('to', true);

        // Arrange
        $service = new TestOperatorService(false, $this->dbMock, $this->debugMock);

        // Act
        $query = $service->getTelescopeOperatorsListQueryProxy(true);

        // Assert
        $expect = $data['query']['sql'];
        $this->assertSame($expect, $query);
    }

    /**
     * Tests SQL query generation for fetching semester programs.
     *
     * @covers \App\services\database\troublelog\read\OperatorService::getTelescopeOperatorsListQuery
     *
     * @return void
     */
    public function testGetTelescopeOperatorsListQuerySortAscFalse(): void
    {
        // Define the test data
        $data = $this->createTestData('to', false);

        // Arrange
        $service = new TestOperatorService(false, $this->dbMock, $this->debugMock);

        // Act
        $query = $service->getTelescopeOperatorsListQueryProxy(false);

        // Assert
        $expect = $data['query']['sql'];
        $this->assertSame($expect, $query);
    }
    /**
     * TEST METHOD 6: getObservatoryAssistantsListQuery [PROTECTED]
     */

    /**
     * Tests SQL query generation for fetching semester programs.
     *
     * @covers \App\services\database\troublelog\read\OperatorService::getObservatoryAssistantsListQuery
     *
     * @return void
     */
    public function testGetObservatoryAssistantsListQuerySortAscTrue(): void
    {
        // Define the test data
        $data = $this->createTestData('ooa', true);

        // Arrange
        $service = new TestOperatorService(false, $this->dbMock, $this->debugMock);

        // Act
        $query = $service->getObservatoryAssistantsListQueryProxy(true);

        // Assert
        $expect = $data['query']['sql'];
        $this->assertSame($expect, $query);
    }

    /**
     * Tests SQL query generation for fetching semester programs.
     *
     * @covers \App\services\database\troublelog\read\OperatorService::getObservatoryAssistantsListQuery
     *
     * @return void
     */
    public function testGetObservatoryAssistantsListQuerySortAscFalse(): void
    {
        // Define the test data
        $data = $this->createTestData('ooa', false);

        // Arrange
        $service = new TestOperatorService(false, $this->dbMock, $this->debugMock);

        // Act
        $query = $service->getObservatoryAssistantsListQueryProxy(false);

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
            OperatorService::class,
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
    private function createTestData(string $type = 'list', bool $sort = true): array
    {
        // Set up the test data
        $data = [
            // test inputs (data values for testing)
            'type' => $type,
            'sortAsc' => $sort,
            'status' => ($type === 'ooa'), // true for ooa, false for to, ignored for list
            // test outputs (method return values, etc)
            'successResult' => [ // Expected result for successful record retrieval
                [
                    'operatorID' => 'TM',
                    'operatorCode' => 'M',
                    'firstName' => 'Tony',
                    'lastName' => 'TMatulonis',
                    'nightAttend' => 0,
                ],
                [
                    'operatorID' => 'KV',
                    'operatorCode' => '',
                    'firstName' => 'Kainalu',
                    'lastName' => 'Von Gnechten',
                    'nightAttend' => 1,
                ],
                [
                    'operatorID' => 'BC',
                    'operatorCode' => 'C',
                    'firstName' => 'Brian',
                    'lastName' => 'Cabreira',
                    'nightAttend' => -1,
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
     *
     * @return array An associative array with the following keys:
     *               - 'sql' (string): The SQL query string.
     *               - 'params' (array): The parameters to bind to the query.
     *               - 'types' (string): The types of the query parameters.
     *               - 'errorMsg' (string): The message to output for query errors.
     */
    private function createTestQueryParts(array $data): array
    {
        $where = $data['status'] ? "WHERE nightAttend = '1'" : "WHERE nightAttend = '0'";
        $sort = $data['sortAsc'] ? 'ASC' : 'DESC';
        $params = [];
        $types = '';
        switch ($data['type']) {
            case 'list':
                $query = "SELECT * FROM Operator ORDER BY lastName {$sort};";
                $error = 'No operators found.';
                break;

            case 'to':
                $query = "SELECT * FROM Operator {$where} ORDER BY lastName {$sort};";
                $error = 'No active operators found.';
                break;

            case 'ooa':
                $query = "SELECT * FROM Operator {$where} ORDER BY lastName {$sort};";
                $error = 'No active assistants found.';
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
        ];
    }
}
