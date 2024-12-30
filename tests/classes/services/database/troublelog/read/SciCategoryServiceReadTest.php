<?php

declare(strict_types=1);

namespace Tests\classes\services\database\troublelog\read;

use Mockery;
use PHPUnit\Framework\TestCase;
use Tests\utilities\helpers\UnitTestTeardownTrait;
use Tests\utilities\mocks\MockDebugTrait;
use Tests\utilities\mocks\MockDBConnectionTrait;
use Tests\utilities\mocks\MockDatabaseServiceFetchDataWithQueryTrait;
use Tests\classes\services\database\troublelog\read\TestSciCategoryService;
use App\services\database\troublelog\read\SciCategoryService;
use App\exceptions\DatabaseException;

/**
 * Unit tests for the SciCategoryService read class.
 *
 * This test suite validates the behavior of the SciCategoryService class,
 * specifically ensuring that its read operations interact with the database
 * as expected.
 *
 * List of method tests:
 *
 * - testFetchScientificCategoryDataSucceeds [DONE]
 * - testFetchScientificCategoryDataFails [DONE]
 * - testFetchScientificCategoryIdSucceeds [DONE]
 * - testFetchScientificCategoryIdFails [DONE]
 * - testFetchScientificCategoryNameSucceeds [DONE]
 * - testFetchScientificCategoryNameFails [DONE]
 * - testGetScientificCategoryListQuerySortAscTrue [DONE]
 * - testGetScientificCategoryListQuerySortAscFalse [DONE]
 * - testGetScientificCategoryIdQuery [DONE]
 * - testGetScientificCategoryNameQuery [DONE]
 *
 * @covers \App\services\database\troublelog\read\SciCategoryService
 */
class SciCategoryServiceReadTest extends TestCase
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
     * Partial mock of SciCategoryService.
     *
     * @var SciCategoryService
     */
    private $srvMock;

    /**
     * TEST METHOD 1: fetchScientificCategoryData
     */

    /**
     * Tests that fetchScientificCategoryData successfully retrieves data.
     *
     * @covers \App\services\database\troublelog\read\SciCategoryService::fetchScientificCategoryData
     *
     * @return void
     */
    public function testFetchScientificCategoryDataSucceeds(): void
    {
        // Define the test data
        $data = $this->createTestData('list');
        $data['query']['resultType'] = true;

        // Arrange
        $this->arrangeFetchDataWithQueryExpectations($data['query']);

        // Act
        $result = $this->srvMock->fetchScientificCategoryData();

        // Assert
        $this->assertFetchDataWithQueryExpectations($result, $data['query']);
    }

    /**
     * Tests that fetchScientificCategoryData fails gracefully when no data is found.
     *
     * @covers \App\services\database\troublelog\read\SciCategoryService::fetchScientificCategoryData
     *
     * @return void
     */
    public function testFetchScientificCategoryDataFails(): void
    {
        // Define the test data
        $data = $this->createTestData('list');
        $data['query']['resultType'] = true;
        $data['query']['result'] = $data['failureResult'];

        // Arrange
        $this->arrangeFetchDataWithQueryExpectations($data['query']);

        // Act
        $result = $this->srvMock->fetchScientificCategoryData();

        // Assert
        $this->assertFetchDataWithQueryExpectations($result, $data['query']);
    }

    /**
     * TEST METHOD 2: fetchScientificCategoryId
     */

    /**
     * Tests that fetchScientificCategoryId successfully retrieves data.
     *
     * @covers \App\services\database\troublelog\read\SciCategoryService::fetchScientificCategoryId
     *
     * @return void
     */
    public function testFetchScientificCategoryIdSucceeds(): void
    {
        // Define the test data
        $data = $this->createTestData('catid');
        $data['query']['resultType'] = true;

        // Arrange
        $this->arrangeFetchDataWithQueryExpectations($data['query']);

        // Act
        $result = $this->srvMock->fetchScientificCategoryId($data['catname']);

        // Assert
        $this->assertFetchDataWithQueryExpectations($result, $data['query']);
    }

    /**
     * Tests that fetchScientificCategoryId fails gracefully when no data is found.
     *
     * @covers \App\services\database\troublelog\read\SciCategoryService::fetchScientificCategoryId
     *
     * @return void
     */
    public function testFetchScientificCategoryIdFails(): void
    {
        // Define the test data
        $data = $this->createTestData('catid');
        $data['query']['resultType'] = true;
        $data['query']['result'] = $data['failureResult'];

        // Arrange
        $this->arrangeFetchDataWithQueryExpectations($data['query']);

        // Act
        $result = $this->srvMock->fetchScientificCategoryId($data['catname']);

        // Assert
        $this->assertFetchDataWithQueryExpectations($result, $data['query']);
    }

    /**
     * TEST METHOD 3: fetchScientificCategoryName
     */

    /**
     * Tests that fetchScientificCategoryName successfully retrieves data.
     *
     * @covers \App\services\database\troublelog\read\SciCategoryService::fetchScientificCategoryName
     *
     * @return void
     */
    public function testFetchScientificCategoryNameSucceeds(): void
    {
        // Define the test data
        $data = $this->createTestData('catname');
        $data['query']['resultType'] = true;

        // Arrange
        $this->arrangeFetchDataWithQueryExpectations($data['query']);

        // Act
        $result = $this->srvMock->fetchScientificCategoryName($data['catid']);

        // Assert
        $this->assertFetchDataWithQueryExpectations($result, $data['query']);
    }

    /**
     * Tests that fetchScientificCategoryName fails gracefully when no data is found.
     *
     * @covers \App\services\database\troublelog\read\SciCategoryService::fetchScientificCategoryName
     *
     * @return void
     */
    public function testFetchScientificCategoryNameFails(): void
    {
        // Define the test data
        $data = $this->createTestData('catname');
        $data['query']['resultType'] = true;
        $data['query']['result'] = $data['failureResult'];

        // Arrange
        $this->arrangeFetchDataWithQueryExpectations($data['query']);

        // Act
        $result = $this->srvMock->fetchScientificCategoryName($data['catid']);

        // Assert
        $this->assertFetchDataWithQueryExpectations($result, $data['query']);
    }

    /**
     * TEST METHOD 4: getScientificCategoryListQuery [PROTECTED]
     */

    /**
     * Tests SQL query generation for fetching semester programs.
     *
     * @covers \App\services\database\troublelog\read\SciCategoryService::getScientificCategoryListQuery
     *
     * @return void
     */
    public function testGetScientificCategoryListQuerySortAscTrue(): void
    {
        // Define the test data
        $data = $this->createTestData('list');

        // Arrange
        $service = new TestSciCategoryService(false, $this->dbMock, $this->debugMock);

        // Act
        $query = $service->getScientificCategoryListQueryProxy(true);

        // Assert
        $expect = $data['query']['sql'];
        $this->assertSame($expect, $query);
    }

    /**
     * Tests SQL query generation for fetching semester programs.
     *
     * @covers \App\services\database\troublelog\read\SciCategoryService::getScientificCategoryListQuery
     *
     * @return void
     */
    public function testGetScientificCategoryListQuerySortAscFalse(): void
    {
        // Define the test data
        $data = $this->createTestData('list', false);

        // Arrange
        $service = new TestSciCategoryService(false, $this->dbMock, $this->debugMock);

        // Act
        $query = $service->getScientificCategoryListQueryProxy(false);

        // Assert
        $expect = $data['query']['sql'];
        $this->assertSame($expect, $query);
    }

    /**
     * TEST METHOD 5: getScientificCategoryIdQuery [PROTECTED]
     */

    /**
     * Tests SQL query generation for fetching single program sessions.
     *
     * @covers \App\services\database\troublelog\read\SciCategoryService::getScientificCategoryIdQuery
     *
     * @return void
     */
    public function testGetScientificCategoryIdQuery(): void
    {
        // Define the test data
        $data = $this->createTestData('catid');

        // Arrange
        $service = new TestSciCategoryService(false, $this->dbMock, $this->debugMock);

        // Act
        $query = $service->getScientificCategoryIdQueryProxy();

        // Assert
        $expect = $data['query']['sql'];
        $this->assertSame($expect, $query);
    }

    /**
     * TEST METHOD 6: getScientificCategoryNameQuery [PROTECTED]
     */

    /**
     * Tests SQL query generation for program validation.
     *
     * @covers \App\services\database\troublelog\read\SciCategoryService::getScientificCategoryNameQuery
     *
     * @return void
     */
    public function testGetScientificCategoryNameQuery(): void
    {
        // Define the test data
        $data = $this->createTestData('catname');

        // Arrange
        $service = new TestSciCategoryService(false, $this->dbMock, $this->debugMock);

        // Act
        $query = $service->getScientificCategoryNameQueryProxy();

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
            SciCategoryService::class,
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
    private function createTestData(string $type = 'catid', bool $sort = true): array
    {
        // Set up the test data
        $data = [
            // test inputs (data values for testing)
            'catname' => 'Centaurs / TNOs / KBOs',
            'catid' => 3,
            'type' => $type,
            'sortAsc' => $sort,
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
        switch ($data['type']) {
            case 'list':
                $sort = $data['sortAsc'] ? 'ASC' : 'DESC';
                $query = "SELECT SciCategory, SciCategoryText FROM SciCategory ORDER BY SciCategory {$sort};";
                $params = [];
                $types = '';
                $result = [
                    [
                        'SciCategory' => 2,
                        'SciCategoryText' => 'extra-solar planets',
                    ],
                    [
                        'SciCategory' => 9,
                        'SciCategoryText' => 'near-Earth objects',
                    ],
                ];
                $error = 'No scientific categories found.';
                break;

            case 'catid':
                $query = "SELECT SciCategory FROM SciCategory WHERE SciCategoryText = ?";
                $params = [$data['catname']];
                $types = 's';
                $result = [['SciCategory' => 11]];
                $error = 'No scientific category found for the given name.';
                break;

            case 'catname':
                $query = "SELECT SciCategoryText FROM SciCategory WHERE SciCategory = ?";
                $params = [$data['catid']];
                $types = 'i';
                $result = [['SciCategoryText' => 'galactic/interstellar medium']];
                $error = 'No scientific category found for the given ID.';
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
