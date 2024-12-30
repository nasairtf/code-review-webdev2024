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
use Tests\classes\services\database\troublelog\read\TestHardwareService;
use App\services\database\troublelog\read\HardwareService;
use App\exceptions\DatabaseException;

/**
 * Unit tests for the HardwareService read class.
 *
 * This test suite validates the behavior of the HardwareService class,
 * specifically ensuring that its read operations interact with the database
 * as expected.
 *
 * List of method tests:
 *
 * - testFetchFullNotObsoleteInstrumentsListSucceeds [DONE]
 * - testFetchFullNotObsoleteInstrumentsListFails [DONE]
 * - testFetchFullInstrumentDataSucceeds [DONE]
 * - testFetchFullInstrumentDataFails [DONE]
 * - testFetchSecondariesDataSucceeds [DONE]
 * - testFetchSecondariesDataFails [DONE]
 * - testFetchFacilityInstrumentsDataSucceeds [DONE]
 * - testFetchFacilityInstrumentsDataFails [DONE]
 * - testFetchInstrumentsListDataByIndexSucceeds [DONE]
 * - testFetchInstrumentsListDataByIndexFails [DONE]
 * - testFetchInstrumentsListDataByNameSucceeds [DONE]
 * - testFetchInstrumentsListDataByNameFails [DONE]
 * - testFetchVisitorInstrumentsDataSucceeds [DONE]
 * - testFetchVisitorInstrumentsDataFails [DONE]
 *
 * - testGetAllInstrumentsListQuerySortAscTrue [DONE]
 * - testGetAllInstrumentsListQuerySortAscFalse [DONE]
 * - testGetActiveSecondaryInstrumentsListSortAscTrue [DONE]
 * - testGetActiveSecondaryInstrumentsListSortAscFalse [DONE]
 * - testGetAllActiveFacilityInstrumentsListByIndexQuerySortAscTrue [DONE]
 * - testGetAllActiveFacilityInstrumentsListByIndexQuerySortAscFalse [DONE]
 * - testGetAllActiveInstrumentsListByIndexQuerySortAscTrue [DONE]
 * - testGetAllActiveInstrumentsListByIndexQuerySortAscFalse [DONE]
 * - testGetAllActiveInstrumentsListByNameQuerySortAscTrue [DONE]
 * - testGetAllActiveInstrumentsListByNameQuerySortAscFalse [DONE]
 * - testGetAllNotObsoleteInstrumentsListByNameQuerySortAscTrue [DONE]
 * - testGetAllNotObsoleteInstrumentsListByNameQuerySortAscFalse [DONE]
 * - testGetAllActiveVisitorInstrumentListQuerySortAscTrue [DONE]
 * - testGetAllActiveVisitorInstrumentListQuerySortAscFalse [DONE]
 *
 * @covers \App\services\database\troublelog\read\HardwareService
 */
class HardwareServiceReadTest extends TestCase
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
     * Partial mock of HardwareService.
     *
     * @var HardwareService
     */
    private $srvMock;

    /**
     * TEST METHOD 1: fetchFullNotObsoleteInstrumentsList
     */

    /**
     * Tests that fetchFullNotObsoleteInstrumentsList successfully retrieves data.
     *
     * @covers \App\services\database\troublelog\read\HardwareService::fetchFullNotObsoleteInstrumentsList
     *
     * @return void
     */
    public function testFetchFullNotObsoleteInstrumentsListSucceeds(): void
    {
        // Define the test data
        $data = $this->createTestData('notobsolete', true);
        $data['query']['resultType'] = true;
        $data['query']['result'] = [$data['successResult'][1]];

        // Arrange
        $this->arrangeFetchDataWithQueryExpectations($data['query']);

        // Act [$this->getAllNotObsoleteInstrumentsListByNameQuery(true)]
        $result = $this->srvMock->fetchFullNotObsoleteInstrumentsList();

        // Assert
        $this->assertFetchDataWithQueryExpectations($result, $data['query']);
    }

    /**
     * Tests that fetchFullNotObsoleteInstrumentsList fails gracefully when no data is found.
     *
     * @covers \App\services\database\troublelog\read\HardwareService::fetchFullNotObsoleteInstrumentsList
     *
     * @return void
     */
    public function testFetchFullNotObsoleteInstrumentsListFails(): void
    {
        // Define the test data
        $data = $this->createTestData('notobsolete', true);
        $data['query']['resultType'] = true;
        $data['query']['result'] = $data['failureResult'];

        // Arrange
        $this->arrangeFetchDataWithQueryExpectations($data['query']);

        // Act [$this->getAllNotObsoleteInstrumentsListByNameQuery(true)]
        $result = $this->srvMock->fetchFullNotObsoleteInstrumentsList();

        // Assert
        $this->assertFetchDataWithQueryExpectations($result, $data['query']);
    }

    /**
     * TEST METHOD 2: fetchFullInstrumentData
     */

    /**
     * Tests that fetchFullInstrumentData successfully retrieves data.
     *
     * @covers \App\services\database\troublelog\read\HardwareService::fetchFullInstrumentData
     *
     * @return void
     */
    public function testFetchFullInstrumentDataSucceeds(): void
    {
        // Define the test data
        $data = $this->createTestData('list', true);
        $data['query']['resultType'] = true;
        $data['query']['result'] = [$data['successResult'][0]];

        // Arrange
        $this->arrangeFetchDataWithQueryExpectations($data['query']);

        // Act [$this->getAllInstrumentsListQuery(true)]
        $result = $this->srvMock->fetchFullInstrumentData();

        // Assert
        $this->assertFetchDataWithQueryExpectations($result, $data['query']);
    }

    /**
     * Tests that fetchFullInstrumentData fails gracefully when no data is found.
     *
     * @covers \App\services\database\troublelog\read\HardwareService::fetchFullInstrumentData
     *
     * @return void
     */
    public function testFetchFullInstrumentDataFails(): void
    {
        // Define the test data
        $data = $this->createTestData('list', true);
        $data['query']['resultType'] = true;
        $data['query']['result'] = $data['failureResult'];

        // Arrange
        $this->arrangeFetchDataWithQueryExpectations($data['query']);

        // Act [$this->getAllInstrumentsListQuery(true)]
        $result = $this->srvMock->fetchFullInstrumentData();

        // Assert
        $this->assertFetchDataWithQueryExpectations($result, $data['query']);
    }

    /**
     * TEST METHOD 3: fetchSecondariesData
     */

    /**
     * Tests that fetchSecondariesData successfully retrieves data.
     *
     * @covers \App\services\database\troublelog\read\HardwareService::fetchSecondariesData
     *
     * @return void
     */
    public function testFetchSecondariesDataSucceeds(): void
    {
        // Define the test data
        $data = $this->createTestData('seconactive', true);
        $data['query']['resultType'] = true;
        $data['query']['result'] = [$data['successResult'][1]];

        // Arrange
        $this->arrangeFetchDataWithQueryExpectations($data['query']);

        // Act [$this->getActiveSecondaryInstrumentsListQuery(true)]
        $result = $this->srvMock->fetchSecondariesData();

        // Assert
        $this->assertFetchDataWithQueryExpectations($result, $data['query']);
    }

    /**
     * Tests that fetchSecondariesData fails gracefully when no data is found.
     *
     * @covers \App\services\database\troublelog\read\HardwareService::fetchSecondariesData
     *
     * @return void
     */
    public function testFetchSecondariesDataFails(): void
    {
        // Define the test data
        $data = $this->createTestData('seconactive', true);
        $data['query']['resultType'] = true;
        $data['query']['result'] = $data['failureResult'];

        // Arrange
        $this->arrangeFetchDataWithQueryExpectations($data['query']);

        // Act [$this->getActiveSecondaryInstrumentsListQuery(true)]
        $result = $this->srvMock->fetchSecondariesData();

        // Assert
        $this->assertFetchDataWithQueryExpectations($result, $data['query']);
    }

    /**
     * TEST METHOD 4: fetchFacilityInstrumentsData
     */

    /**
     * Tests that fetchFacilityInstrumentsData by-index successfully retrieves data.
     *
     * @covers \App\services\database\troublelog\read\HardwareService::fetchFacilityInstrumentsData
     *
     * @return void
     */
    public function testFetchFacilityInstrumentsDataSucceeds(): void
    {
        // Define the test data
        $data = $this->createTestData('facactiveindex', true);
        $data['query']['resultType'] = true;
        $data['query']['result'] = [$data['successResult'][2]];

        // Arrange
        $this->arrangeFetchDataWithQueryExpectations($data['query']);

        // Act [$this->getAllActiveFacilityInstrumentsListByIndexQuery(true)]
        $result = $this->srvMock->fetchFacilityInstrumentsData();

        // Assert
        $this->assertFetchDataWithQueryExpectations($result, $data['query']);
    }

    /**
     * Tests that fetchFacilityInstrumentsData by-index fails gracefully when no data is found.
     *
     * @covers \App\services\database\troublelog\read\HardwareService::fetchFacilityInstrumentsData
     *
     * @return void
     */
    public function testFetchFacilityInstrumentsDataFails(): void
    {
        // Define the test data
        $data = $this->createTestData('facactiveindex', true);
        $data['query']['resultType'] = true;
        $data['query']['result'] = $data['failureResult'];

        // Arrange
        $this->arrangeFetchDataWithQueryExpectations($data['query']);

        // Act [$this->getAllActiveFacilityInstrumentsListByIndexQuery(true)]
        $result = $this->srvMock->fetchFacilityInstrumentsData();

        // Assert
        $this->assertFetchDataWithQueryExpectations($result, $data['query']);
    }

    /**
     * TEST METHOD 5: fetchInstrumentsListData
     */

    /**
     * Tests that fetchInstrumentsListData successfully retrieves data.
     *
     * @covers \App\services\database\troublelog\read\HardwareService::fetchInstrumentsListData
     *
     * @return void
     */
    public function testFetchFacilityInstrumentsDataByIndexSucceeds(): void
    {
        // Define the test data
        $data = $this->createTestData('allactiveindex', true);
        $data['query']['resultType'] = true;
        $data['query']['result'] = $data['successResult'];

        // Arrange
        $this->arrangeFetchDataWithQueryExpectations($data['query']);

        // Act [$this->getAllActiveInstrumentsListByIndexQuery(true)]
        $result = $this->srvMock->fetchInstrumentsListData(true);

        // Assert
        $this->assertFetchDataWithQueryExpectations($result, $data['query']);
    }

    /**
     * Tests that fetchInstrumentsListData by-name fails gracefully when no data is found.
     *
     * @covers \App\services\database\troublelog\read\HardwareService::fetchInstrumentsListData
     *
     * @return void
     */
    public function testFetchFacilityInstrumentsDataByIndexFails(): void
    {
        // Define the test data
        $data = $this->createTestData('allactiveindex', true);
        $data['query']['resultType'] = true;
        $data['query']['result'] = $data['failureResult'];

        // Arrange
        $this->arrangeFetchDataWithQueryExpectations($data['query']);

        // Act [$this->getAllActiveInstrumentsListByIndexQuery(true)]
        $result = $this->srvMock->fetchInstrumentsListData(true);

        // Assert
        $this->assertFetchDataWithQueryExpectations($result, $data['query']);
    }

    /**
     * Tests that fetchInstrumentsListData by-name successfully retrieves data.
     *
     * @covers \App\services\database\troublelog\read\HardwareService::fetchInstrumentsListData
     *
     * @return void
     */
    public function testFetchInstrumentsListDataByNameSucceeds(): void
    {
        // Define the test data
        $data = $this->createTestData('allactivename', true);
        $data['query']['resultType'] = true;
        $data['query']['result'] = $data['successResult'];

        // Arrange
        $this->arrangeFetchDataWithQueryExpectations($data['query']);

        // Act [$this->getAllActiveInstrumentsListByNameQuery(true)]
        $result = $this->srvMock->fetchInstrumentsListData(false);

        // Assert
        $this->assertFetchDataWithQueryExpectations($result, $data['query']);
    }

    /**
     * Tests that fetchInstrumentsListData by-name fails gracefully when no data is found.
     *
     * @covers \App\services\database\troublelog\read\HardwareService::fetchInstrumentsListData
     *
     * @return void
     */
    public function testFetchInstrumentsListDataByNameFails(): void
    {
        // Define the test data
        $data = $this->createTestData('allactivename', true);
        $data['query']['resultType'] = true;
        $data['query']['result'] = $data['failureResult'];

        // Arrange
        $this->arrangeFetchDataWithQueryExpectations($data['query']);

        // Act [$this->getAllActiveInstrumentsListByNameQuery(true)]
        $result = $this->srvMock->fetchInstrumentsListData(false);

        // Assert
        $this->assertFetchDataWithQueryExpectations($result, $data['query']);
    }

    /**
     * TEST METHOD 6: fetchVisitorInstrumentsData
     */

    /**
     * Tests that fetchVisitorInstrumentsData successfully retrieves data.
     *
     * @covers \App\services\database\troublelog\read\HardwareService::fetchVisitorInstrumentsData
     *
     * @return void
     */
    public function testFetchVisitorInstrumentsDataSucceeds(): void
    {
        // Define the test data
        $data = $this->createTestData('visitor', true);
        $data['query']['resultType'] = true;
        $data['query']['result'] = [$data['successResult'][3]];

        // Arrange
        $this->arrangeFetchDataWithQueryExpectations($data['query']);

        // Act [$this->getAllActiveVisitorInstrumentListQuery(true)]
        $result = $this->srvMock->fetchVisitorInstrumentsData();

        // Assert
        $this->assertFetchDataWithQueryExpectations($result, $data['query']);
    }

    /**
     * Tests that fetchVisitorInstrumentsData fails gracefully when no data is found.
     *
     * @covers \App\services\database\troublelog\read\HardwareService::fetchVisitorInstrumentsData
     *
     * @return void
     */
    public function testFetchVisitorInstrumentsDataFails(): void
    {
        // Define the test data
        $data = $this->createTestData('visitor', true);
        $data['query']['resultType'] = true;
        $data['query']['result'] = $data['failureResult'];

        // Arrange
        $this->arrangeFetchDataWithQueryExpectations($data['query']);

        // Act [$this->getAllActiveVisitorInstrumentListQuery(true)]
        $result = $this->srvMock->fetchVisitorInstrumentsData();

        // Assert
        $this->assertFetchDataWithQueryExpectations($result, $data['query']);
    }

    /**
     * TEST METHOD 7: getAllInstrumentsListQuery [PROTECTED]
     */

    /**
     * Tests SQL query generation for fetching semester programs.
     *
     * @covers \App\services\database\troublelog\read\HardwareService::getAllInstrumentsListQuery
     *
     * @return void
     */
    public function testGetAllInstrumentsListQuerySortAscTrue(): void
    {
        // Define the test data
        $data = $this->createTestData('list', true);

        // Arrange
        $service = new TestHardwareService(false, $this->dbMock, $this->debugMock);

        // Act []
        $query = $service->getAllInstrumentsListQueryProxy(true);

        // Assert
        $expect = $data['query']['sql'];
        $this->assertSame($expect, $query);
    }

    /**
     * Tests SQL query generation for fetching semester programs.
     *
     * @covers \App\services\database\troublelog\read\HardwareService::getAllInstrumentsListQuery
     *
     * @return void
     */
    public function testGetAllInstrumentsListQuerySortAscFalse(): void
    {
        // Define the test data
        $data = $this->createTestData('list', false);

        // Arrange
        $service = new TestHardwareService(false, $this->dbMock, $this->debugMock);

        // Act []
        $query = $service->getAllInstrumentsListQueryProxy(false);

        // Assert
        $expect = $data['query']['sql'];
        $this->assertSame($expect, $query);
    }

    /**
     * TEST METHOD 8: getActiveSecondaryInstrumentsListQuery [PROTECTED]
     */

    /**
     * Tests SQL query generation for fetching semester programs.
     *
     * @covers \App\services\database\troublelog\read\HardwareService::getActiveSecondaryInstrumentsListQuery
     *
     * @return void
     */
    public function testGetActiveSecondaryInstrumentsListQuerySortAscTrue(): void
    {
        // Define the test data
        $data = $this->createTestData('seconactive', true);

        // Arrange
        $service = new TestHardwareService(false, $this->dbMock, $this->debugMock);

        // Act
        $query = $service->getActiveSecondaryInstrumentsListQueryProxy(true);

        // Assert
        $expect = $data['query']['sql'];
        $this->assertSame($expect, $query);
    }

    /**
     * Tests SQL query generation for fetching semester programs.
     *
     * @covers \App\services\database\troublelog\read\HardwareService::getActiveSecondaryInstrumentsListQuery
     *
     * @return void
     */
    public function testGetActiveSecondaryInstrumentsListQuerySortAscFalse(): void
    {
        // Define the test data
        $data = $this->createTestData('seconactive', false);

        // Arrange
        $service = new TestHardwareService(false, $this->dbMock, $this->debugMock);

        // Act
        $query = $service->getActiveSecondaryInstrumentsListQueryProxy(false);

        // Assert
        $expect = $data['query']['sql'];
        $this->assertSame($expect, $query);
    }

    /**
     * TEST METHOD 9: getAllActiveFacilityInstrumentsListByIndexQuery [PROTECTED]
     */

    /**
     * Tests SQL query generation for fetching semester programs.
     *
     * @covers \App\services\database\troublelog\read\HardwareService::getAllActiveFacilityInstrumentsListByIndexQuery
     *
     * @return void
     */
    public function testGetAllActiveFacilityInstrumentsListByIndexQuerySortAscTrue(): void
    {
        // Define the test data
        $data = $this->createTestData('facactiveindex', true);

        // Arrange
        $service = new TestHardwareService(false, $this->dbMock, $this->debugMock);

        // Act
        $query = $service->getAllActiveFacilityInstrumentsListByIndexQueryProxy(true);

        // Assert
        $expect = $data['query']['sql'];
        $this->assertSame($expect, $query);
    }

    /**
     * Tests SQL query generation for fetching semester programs.
     *
     * @covers \App\services\database\troublelog\read\HardwareService::getAllActiveFacilityInstrumentsListByIndexQuery
     *
     * @return void
     */
    public function testGetAllActiveFacilityInstrumentsListByIndexQuerySortAscFalse(): void
    {
        // Define the test data
        $data = $this->createTestData('facactiveindex', false);

        // Arrange
        $service = new TestHardwareService(false, $this->dbMock, $this->debugMock);

        // Act
        $query = $service->getAllActiveFacilityInstrumentsListByIndexQueryProxy(false);

        // Assert
        $expect = $data['query']['sql'];
        $this->assertSame($expect, $query);
    }

    /**
     * TEST METHOD 10: getAllActiveInstrumentsListByIndexQuery [PROTECTED]
     */

    /**
     * Tests SQL query generation for fetching semester programs.
     *
     * @covers \App\services\database\troublelog\read\HardwareService::getAllActiveInstrumentsListByIndexQuery
     *
     * @return void
     */
    public function testGetAllActiveInstrumentsListByIndexQuerySortAscTrue(): void
    {
        // Define the test data
        $data = $this->createTestData('allactiveindex', true);

        // Arrange
        $service = new TestHardwareService(false, $this->dbMock, $this->debugMock);

        // Act
        $query = $service->getAllActiveInstrumentsListByIndexQueryProxy(true);

        // Assert
        $expect = $data['query']['sql'];
        $this->assertSame($expect, $query);
    }

    /**
     * Tests SQL query generation for fetching semester programs.
     *
     * @covers \App\services\database\troublelog\read\HardwareService::getAllActiveInstrumentsListByIndexQuery
     *
     * @return void
     */
    public function testGetAllActiveInstrumentsListByIndexQuerySortAscFalse(): void
    {
        // Define the test data
        $data = $this->createTestData('allactiveindex', false);

        // Arrange
        $service = new TestHardwareService(false, $this->dbMock, $this->debugMock);

        // Act
        $query = $service->getAllActiveInstrumentsListByIndexQueryProxy(false);

        // Assert
        $expect = $data['query']['sql'];
        $this->assertSame($expect, $query);
    }

    /**
     * TEST METHOD 11: getAllActiveInstrumentsListByNameQuery [PROTECTED]
     */

    /**
     * Tests SQL query generation for fetching semester programs.
     *
     * @covers \App\services\database\troublelog\read\HardwareService::getAllActiveInstrumentsListByNameQuery
     *
     * @return void
     */
    public function testGetAllActiveInstrumentsListByNameQuerySortAscTrue(): void
    {
        // Define the test data
        $data = $this->createTestData('allactivename', true);

        // Arrange
        $service = new TestHardwareService(false, $this->dbMock, $this->debugMock);

        // Act
        $query = $service->getAllActiveInstrumentsListByNameQueryProxy(true);

        // Assert
        $expect = $data['query']['sql'];
        $this->assertSame($expect, $query);
    }

    /**
     * Tests SQL query generation for fetching semester programs.
     *
     * @covers \App\services\database\troublelog\read\HardwareService::getAllActiveInstrumentsListByNameQuery
     *
     * @return void
     */
    public function testGetAllActiveInstrumentsListByNameQuerySortAscFalse(): void
    {
        // Define the test data
        $data = $this->createTestData('allactivename', false);

        // Arrange
        $service = new TestHardwareService(false, $this->dbMock, $this->debugMock);

        // Act
        $query = $service->getAllActiveInstrumentsListByNameQueryProxy(false);

        // Assert
        $expect = $data['query']['sql'];
        $this->assertSame($expect, $query);
    }

    /**
     * TEST METHOD 12: getAllNotObsoleteInstrumentsListByNameQuery [PROTECTED]
     */

    /**
     * Tests SQL query generation for fetching semester programs.
     *
     * @covers \App\services\database\troublelog\read\HardwareService::getAllNotObsoleteInstrumentsListByNameQuery
     *
     * @return void
     */
    public function testGetAllNotObsoleteInstrumentsListByNameQuerySortAscTrue(): void
    {
        // Define the test data
        $data = $this->createTestData('notobsolete', true);

        // Arrange
        $service = new TestHardwareService(false, $this->dbMock, $this->debugMock);

        // Act
        $query = $service->getAllNotObsoleteInstrumentsListByNameQueryProxy(true);

        // Assert
        $expect = $data['query']['sql'];
        $this->assertSame($expect, $query);
    }

    /**
     * Tests SQL query generation for fetching semester programs.
     *
     * @covers \App\services\database\troublelog\read\HardwareService::getAllNotObsoleteInstrumentsListByNameQuery
     *
     * @return void
     */
    public function testGetAllNotObsoleteInstrumentsListByNameQuerySortAscFalse(): void
    {
        // Define the test data
        $data = $this->createTestData('notobsolete', false);

        // Arrange
        $service = new TestHardwareService(false, $this->dbMock, $this->debugMock);

        // Act
        $query = $service->getAllNotObsoleteInstrumentsListByNameQueryProxy(false);

        // Assert
        $expect = $data['query']['sql'];
        $this->assertSame($expect, $query);
    }

    /**
     * TEST METHOD 13: getAllActiveVisitorInstrumentListQuery [PROTECTED]
     */

    /**
     * Tests SQL query generation for fetching semester programs.
     *
     * @covers \App\services\database\troublelog\read\HardwareService::getAllActiveVisitorInstrumentListQuery
     *
     * @return void
     */
    public function testGetAllActiveVisitorInstrumentListQuerySortAscTrue(): void
    {
        // Define the test data
        $data = $this->createTestData('visitor', true);

        // Arrange
        $service = new TestHardwareService(false, $this->dbMock, $this->debugMock);

        // Act
        $query = $service->getAllActiveVisitorInstrumentListQueryProxy(true);

        // Assert
        $expect = $data['query']['sql'];
        $this->assertSame($expect, $query);
    }

    /**
     * Tests SQL query generation for fetching semester programs.
     *
     * @covers \App\services\database\troublelog\read\HardwareService::getAllActiveVisitorInstrumentListQuery
     *
     * @return void
     */
    public function testGetAllActiveVisitorInstrumentListQuerySortAscFalse(): void
    {
        // Define the test data
        $data = $this->createTestData('visitor', false);

        // Arrange
        $service = new TestHardwareService(false, $this->dbMock, $this->debugMock);

        // Act
        $query = $service->getAllActiveVisitorInstrumentListQueryProxy(false);

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
            HardwareService::class,
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
            // test outputs (method return values, etc)
            'successResult' => [ // Expected result for successful record retrieval
                [
                    'hardwareID' => 'ao',
                    'itemName' => 'AO',
                    'type' => 'instr',
                    'description' => null,
                    'notes' => 'obsolete',
                    'notAvailableStart' => null,
                    'notAvailableEnd' => null,
                    'pulldownIndex' => null,
                ],
                [
                    'hardwareID' => 'hexe',
                    'itemName' => 'Hexepod',
                    'type' => 'secon',
                    'description' => null,
                    'notes' => 'active',
                    'notAvailableStart' => null,
                    'notAvailableEnd' => null,
                    'pulldownIndex' => null,
                ],
                [
                    'hardwareID' => 'ishel',
                    'itemName' => 'iSHELL',
                    'type' => 'instr',
                    'description' => '1.06 - 5.3 micron cross-dispersed echelle spectrograph and imager',
                    'notes' => 'active',
                    'notAvailableStart' => null,
                    'notAvailableEnd' => null,
                    'pulldownIndex' => null,
                ],
                [
                    'hardwareID' => 'texes',
                    'itemName' => 'TEXES',
                    'type' => 'instr',
                    'description' => null,
                    'notes' => 'visitor',
                    'notAvailableStart' => null,
                    'notAvailableEnd' => null,
                    'pulldownIndex' => null,
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
        $sort = $data['sortAsc'] ? 'ASC' : 'DESC';
        $select = 'SELECT *';
        $order = "ORDER BY itemName {$sort}";
        $params = [];
        $types = '';
        $result = [];
        switch ($data['type']) {
            // getAllInstrumentsListQuery
            case 'list':
                $where = "";
                $error = 'No hardware found.';
                break;

            // getActiveSecondaryInstrumentsListQuery
            case 'seconactive':
                $where = "WHERE notes = 'active' AND type = 'secon' AND "
                    . "hardwareID <> 'unk' AND hardwareID <> 'ic' ";
                $error = 'No secondaries found.';
                break;

            // getAllActiveFacilityInstrumentsListByIndexQuery
            case 'facactiveindex':
                $where = "WHERE notes = 'active' AND type = 'instr' AND "
                    . "hardwareID <> 'unk' AND hardwareID <> 'ic' ";
                $order = "ORDER BY pulldownIndex {$sort}";
                $error = 'No active facility instruments found.';
                break;

            // getAllActiveInstrumentsListByIndexQuery
            case 'allactiveindex':
                $where = "WHERE notes IN ('active','visitor') AND type = 'instr' AND "
                    . "hardwareID <> 'unk' AND hardwareID <> 'ic' ";
                $order = "ORDER BY pulldownIndex {$sort}";
                $error = 'No active instruments found.';
                break;

            // getAllActiveInstrumentsListByNameQuery
            case 'allactivename':
                $where = "WHERE notes IN ('active','visitor') AND type = 'instr' AND "
                    . "hardwareID <> 'unk' AND hardwareID <> 'ic' ";
                $error = 'No active instruments found.';
                break;

            // getAllNotObsoleteInstrumentsListByNameQuery
            case 'notobsolete':
                $where = "WHERE notes <> 'obsolete' ";
                $error = 'No non-obsolete instruments found.';
                break;

            // getAllActiveVisitorInstrumentListQuery
            case 'visitor':
                $select = 'SELECT hardwareID, itemName';
                $where = "WHERE notes = 'visitor' AND type = 'instr' ";
                $error = 'No visitor instruments found.';
                $result = [
                    ['hardwareID' => 'texes', 'itemName' => 'TEXES'],
                ];
                break;
        }
        return [
            // Query's SQL string
            'sql' => "{$select} FROM Hardware {$where}{$order};",
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
