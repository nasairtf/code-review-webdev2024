<?php

declare(strict_types=1);

namespace Tests\classes\services\database\ishell\read;

use Mockery;
use PHPUnit\Framework\TestCase;
use Tests\utilities\CustomDebugMockTrait;
use Tests\utilities\DBConnectionMockTrait;
use Tests\utilities\DatabaseServiceMockTrait;
use App\services\database\ishell\read\TemperaturesService;
use App\exceptions\DatabaseException;

/**
 * Unit tests for the TemperaturesService read class.
 *
 * This test suite validates the behavior of the TemperaturesService class,
 * specifically ensuring that its read operations interact with the database
 * as expected.
 *
 * List of method tests:
 *
 * - testQueryWithSensorIdOnly [DONE]
 * - testQueryWithSystemFilter [DONE]
 * - testQueryWithTimestampFilter [DONE]
 * - testQueryWithSystemAndTimestampFilters [DONE]
 * - testQueryWithLimitToOne [DONE]
 * - testQueryWithSortDescending [DONE]
 * - testQueryWithAllFilters [DONE]
 * - testQueryWithNoResults [DONE]
 * - testQueryErrorHandling [DONE]
 *
 * @covers \App\services\database\ishell\read\TemperaturesService
 *
 *------------------------------------------------------------------------------
 * Test Plan For TemperaturesService (Read Class)
 *
 * This class contains one public method to test:
 *
 * - fetchTemperatureData
 *
 * Test Cases
 *
 * 1. testQueryWithSensorIdOnly():
 *    - Default query setup
 * 2. testQueryWithSystemFilter():
 *    - Adds system filter
 * 3. testQueryWithTimestampFilter():
 *    - Adds timestamp filter
 * 4. testQueryWithSystemAndTimestampFilters():
 *    - Adds all filters
 * 5. testQueryWithLimitToOne():
 *    - Adds LIMIT 1
 * 6. testQueryWithSortDescending():
 *    - Changes sort order
 * 7. testQueryWithAllFilters():
 *    - Combines all cases
 * 8. testQueryWithNoResults():
 *    - Simulates no results
 * 9. testQueryErrorHandling():
 *    - Simulates query error
 *
 * Mocking
 *
 * Mock fetchDataWithQuery:
 *  Validate that the correct query, parameters, and types are passed.
 *
 * Mock getSortString:
 *  Ensure sorting logic is applied correctly.
 *
 * Use createTestQueryParts:
 *  Generate SQL strings, parameters, and types dynamically to avoid hardcoding them in multiple places.
 */
class TemperaturesServiceReadTest extends TestCase
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
     * Partial mock of TemperaturesService.
     *
     * @var TemperaturesService
     */
    private $srvMock;

    /**
     * TEST METHOD 1: fetchTemperatureData
     */

    /**
     * Tests that fetchTemperatureData constructs the query correctly
     * when only the sensor_id is provided.
     *
     * Validates the default query without optional filters like system,
     * timestamp, sorting, or limiting results.
     *
     * @covers \App\services\database\ishell\read\TemperaturesService::fetchTemperatureData
     *
     * @return void
     */
    public function testQueryWithSensorIdOnly(): void
    {
        // Define the test data
        $data = $this->createTestData();
        $data['system'] = null;
        $data['timestamp'] = null;
        $data['query'] = $this->createTestQueryParts($data);

        // Arrange
        $this->mockFetchDataWithQuery(
            $this->srvMock,
            $data['query']['sql'],
            $data['query']['params'],
            $data['query']['types'],
            $data['successResult'],
            $data['query']['errorMsg']
        );
        $this->mockGetSortString(
            $this->srvMock,
            $data['sortAsc']
        );

        // Act
        $result = $this->srvMock->fetchTemperatureData(
            $data['sensor_id'],
            $data['system'],
            $data['timestamp'],
            $data['limitToOne'],
            $data['sortAsc']
        );

        // Assert
        $this->assertSame($data['successResult'], $result);
        $this->srvMock->shouldHaveReceived('fetchDataWithQuery')
            ->once()
            ->with(
                $data['query']['sql'],
                $data['query']['params'],
                $data['query']['types'],
                $data['query']['errorMsg']
            );
    }

    /**
     * Tests that fetchTemperatureData constructs the query correctly
     * when a system filter is added to the query.
     *
     * Ensures that the system field is included in the WHERE clause
     * and properly binds the parameter.
     *
     * @covers \App\services\database\ishell\read\TemperaturesService::fetchTemperatureData
     *
     * @return void
     */
    public function testQueryWithSystemFilter(): void
    {
        // Define the test data
        $data = $this->createTestData();
        $data['timestamp'] = null;
        $data['query'] = $this->createTestQueryParts($data);

        // Arrange
        $this->mockFetchDataWithQuery(
            $this->srvMock,
            $data['query']['sql'],
            $data['query']['params'],
            $data['query']['types'],
            $data['successResult'],
            $data['query']['errorMsg']
        );
        $this->mockGetSortString(
            $this->srvMock,
            $data['sortAsc']
        );

        // Act
        $result = $this->srvMock->fetchTemperatureData(
            $data['sensor_id'],
            $data['system'],
            $data['timestamp'],
            $data['limitToOne'],
            $data['sortAsc']
        );

        // Assert
        $this->assertSame($data['successResult'], $result);
        $this->srvMock->shouldHaveReceived('fetchDataWithQuery')
            ->once()
            ->with(
                $data['query']['sql'],
                $data['query']['params'],
                $data['query']['types'],
                $data['query']['errorMsg']
            );
    }

    /**
     * Tests that fetchTemperatureData constructs the query correctly
     * when a timestamp filter is added to the query.
     *
     * Ensures that the timestamp field is included in the WHERE clause
     * and binds the appropriate parameter for filtering.
     *
     * @covers \App\services\database\ishell\read\TemperaturesService::fetchTemperatureData
     *
     * @return void
     */
    public function testQueryWithTimestampFilter(): void
    {
        // Define the test data
        $data = $this->createTestData();
        $data['system'] = null;
        $data['query'] = $this->createTestQueryParts($data);

        // Arrange
        $this->mockFetchDataWithQuery(
            $this->srvMock,
            $data['query']['sql'],
            $data['query']['params'],
            $data['query']['types'],
            $data['successResult'],
            $data['query']['errorMsg']
        );
        $this->mockGetSortString(
            $this->srvMock,
            $data['sortAsc']
        );

        // Act
        $result = $this->srvMock->fetchTemperatureData(
            $data['sensor_id'],
            $data['system'],
            $data['timestamp'],
            $data['limitToOne'],
            $data['sortAsc']
        );

        // Assert
        $this->assertSame($data['successResult'], $result);
        $this->srvMock->shouldHaveReceived('fetchDataWithQuery')
            ->once()
            ->with(
                $data['query']['sql'],
                $data['query']['params'],
                $data['query']['types'],
                $data['query']['errorMsg']
            );
    }

    /**
     * Tests that fetchTemperatureData constructs the query correctly
     * when both system and timestamp filters are applied.
     *
     * Validates the query structure with multiple filters in the WHERE clause
     * and ensures correct parameter binding.
     *
     * @covers \App\services\database\ishell\read\TemperaturesService::fetchTemperatureData
     *
     * @return void
     */
    public function testQueryWithSystemAndTimestampFilters(): void
    {
        // Define the test data
        $data = $this->createTestData();
        $data['query'] = $this->createTestQueryParts($data);

        // Arrange
        $this->mockFetchDataWithQuery(
            $this->srvMock,
            $data['query']['sql'],
            $data['query']['params'],
            $data['query']['types'],
            $data['successResult'],
            $data['query']['errorMsg']
        );
        $this->mockGetSortString(
            $this->srvMock,
            $data['sortAsc']
        );

        // Act
        $result = $this->srvMock->fetchTemperatureData(
            $data['sensor_id'],
            $data['system'],
            $data['timestamp'],
            $data['limitToOne'],
            $data['sortAsc']
        );

        // Assert
        $this->assertSame($data['successResult'], $result);
        $this->srvMock->shouldHaveReceived('fetchDataWithQuery')
            ->once()
            ->with(
                $data['query']['sql'],
                $data['query']['params'],
                $data['query']['types'],
                $data['query']['errorMsg']
            );
    }

    /**
     * Tests that fetchTemperatureData constructs the query correctly
     * when limiting results to the most recent record.
     *
     * Ensures that the LIMIT 1 clause is added to the query
     * and works with other filters or sorting logic.
     *
     * @covers \App\services\database\ishell\read\TemperaturesService::fetchTemperatureData
     *
     * @return void
     */
    public function testQueryWithLimitToOne(): void
    {
        // Define the test data
        $data = $this->createTestData();
        $data['system'] = null;
        $data['timestamp'] = null;
        $data['limitToOne'] = true;
        $data['query'] = $this->createTestQueryParts($data);

        // Arrange
        $this->mockFetchDataWithQuery(
            $this->srvMock,
            $data['query']['sql'],
            $data['query']['params'],
            $data['query']['types'],
            $data['successResult'][0],
            $data['query']['errorMsg']
        );
        $this->mockGetSortString(
            $this->srvMock,
            $data['sortAsc']
        );

        // Act
        $result = $this->srvMock->fetchTemperatureData(
            $data['sensor_id'],
            $data['system'],
            $data['timestamp'],
            $data['limitToOne'],
            $data['sortAsc']
        );

        // Assert
        $this->assertSame($data['successResult'][0], $result);
        $this->srvMock->shouldHaveReceived('fetchDataWithQuery')
            ->once()
            ->with(
                $data['query']['sql'],
                $data['query']['params'],
                $data['query']['types'],
                $data['query']['errorMsg']
            );
    }

    /**
     * Tests that fetchTemperatureData constructs the query correctly
     * when sorting results in descending order.
     *
     * Verifies that the ORDER BY clause reflects descending sort order
     * and integrates with other filters like sensor_id or timestamp.
     *
     * @covers \App\services\database\ishell\read\TemperaturesService::fetchTemperatureData
     *
     * @return void
     */
    public function testQueryWithSortDescending(): void
    {
        // Define the test data
        $data = $this->createTestData(false);
        $data['system'] = null;
        $data['timestamp'] = null;
        $data['query'] = $this->createTestQueryParts($data);

        // Arrange
        $this->mockFetchDataWithQuery(
            $this->srvMock,
            $data['query']['sql'],
            $data['query']['params'],
            $data['query']['types'],
            $data['successResult'],
            $data['query']['errorMsg']
        );
        $this->mockGetSortString(
            $this->srvMock,
            $data['sortAsc']
        );

        // Act
        $result = $this->srvMock->fetchTemperatureData(
            $data['sensor_id'],
            $data['system'],
            $data['timestamp'],
            $data['limitToOne'],
            $data['sortAsc']
        );

        // Assert
        $this->assertSame($data['successResult'], $result);
        $this->srvMock->shouldHaveReceived('fetchDataWithQuery')
            ->once()
            ->with(
                $data['query']['sql'],
                $data['query']['params'],
                $data['query']['types'],
                $data['query']['errorMsg']
            );
    }

    /**
     * Tests that fetchTemperatureData constructs the query correctly
     * when all optional filters are applied.
     *
     * Validates the query with sensor_id, system, timestamp, sorting,
     * and limiting results to the most recent record.
     *
     * @covers \App\services\database\ishell\read\TemperaturesService::fetchTemperatureData
     *
     * @return void
     */
    public function testQueryWithAllFilters(): void
    {
        // Define the test data
        $data = $this->createTestData(false);
        $data['limitToOne'] = true;
        $data['query'] = $this->createTestQueryParts($data);

        // Arrange
        $this->mockFetchDataWithQuery(
            $this->srvMock,
            $data['query']['sql'],
            $data['query']['params'],
            $data['query']['types'],
            $data['successResult'][0],
            $data['query']['errorMsg']
        );
        $this->mockGetSortString(
            $this->srvMock,
            $data['sortAsc']
        );

        // Act
        $result = $this->srvMock->fetchTemperatureData(
            $data['sensor_id'],
            $data['system'],
            $data['timestamp'],
            $data['limitToOne'],
            $data['sortAsc']
        );

        // Assert
        $this->assertSame($data['successResult'][0], $result);
        $this->srvMock->shouldHaveReceived('fetchDataWithQuery')
            ->once()
            ->with(
                $data['query']['sql'],
                $data['query']['params'],
                $data['query']['types'],
                $data['query']['errorMsg']
            );
    }

    /**
     * Tests that fetchTemperatureData handles cases where no results are returned.
     *
     * Simulates a scenario where the query matches no records and validates
     * that the method returns an empty array as expected.
     *
     * @covers \App\services\database\ishell\read\TemperaturesService::fetchTemperatureData
     *
     * @return void
     */
    public function testQueryWithNoResults(): void
    {
        // Define the test data
        $data = $this->createTestData();
        $data['query'] = $this->createTestQueryParts($data);

        // Arrange
        $this->mockFetchDataWithQuery(
            $this->srvMock,
            $data['query']['sql'],
            $data['query']['params'],
            $data['query']['types'],
            $data['failureResult'],
            $data['query']['errorMsg']
        );
        $this->mockGetSortString(
            $this->srvMock,
            $data['sortAsc']
        );

        // Act
        $result = $this->srvMock->fetchTemperatureData(
            $data['sensor_id'],
            $data['system'],
            $data['timestamp'],
            $data['limitToOne'],
            $data['sortAsc']
        );

        // Assert
        $this->assertSame($data['failureResult'], $result);
        $this->srvMock->shouldHaveReceived('fetchDataWithQuery')
            ->once()
            ->with(
                $data['query']['sql'],
                $data['query']['params'],
                $data['query']['types'],
                $data['query']['errorMsg']
            );
    }

    /**
     * Tests that fetchTemperatureData handles database query errors gracefully.
     *
     * Simulates an exception thrown during query execution and validates
     * that the exception is correctly propagated with the expected message.
     *
     * @covers \App\services\database\ishell\read\TemperaturesService::fetchTemperatureData
     *
     * @return void
     */
    public function testQueryErrorHandling(): void
    {
        // Define the test data
        $data = $this->createTestData();
        $data['query'] = $this->createTestQueryParts($data);

        // Arrange
        $this->srvMock->shouldReceive('fetchDataWithQuery')
            ->with(
                $data['query']['sql'],
                $data['query']['params'],
                $data['query']['types'],
                $data['query']['errorMsg']
            )
            ->andThrow(new DatabaseException($data['query']['errorMsg']))
            ->once();
        $this->mockGetSortString(
            $this->srvMock,
            $data['sortAsc']
        );

        // Expect exception
        $this->expectException(DatabaseException::class);
        $this->expectExceptionMessage($data['query']['errorMsg']);

        // Act
        $result = $this->srvMock->fetchTemperatureData(
            $data['sensor_id'],
            $data['system'],
            $data['timestamp'],
            $data['limitToOne'],
            $data['sortAsc']
        );

        // Assert
        $this->srvMock->shouldHaveReceived('fetchDataWithQuery')
            ->once()
            ->with(
                $data['query']['sql'],
                $data['query']['params'],
                $data['query']['types'],
                $data['query']['errorMsg']
            );
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
            TemperaturesService::class,
            [false, $this->dbMock, $this->debugMock],
            ['fetchDataWithQuery', 'getSortString']
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
    private function createTestData(bool $sortAsc = true): array
    {
        // Set up the test data
        $data = [
            // test inputs (data values for testing)
            'sensor_id' => 'Ch A',
            'system' => 'iSHELL guider',
            'timestamp' => 432000,
            'limitToOne' => false,
            'sortAsc' => $sortAsc,
            // test outputs (method return values, etc)
            'successResult' => [ // Expected result for successful record retrieval
                [
                    'sensor_id' => 'Ch A',
                    'system' => 'iSHELL guider',
                    'unit_sn' => '335A20M',
                    'unit_type' => 'LM335',
                    'timestamp' => '2024-12-17 23:17:34',
                    'ktemp' => 29.999,
                    'comment' => '',
                ],
                [
                    'sensor_id' => 'Ch A',
                    'system' => 'iSHELL guider',
                    'unit_sn' => '335A20M',
                    'unit_type' => 'LM335',
                    'timestamp' => '2024-12-17 23:19:52',
                    'ktemp' => 30.003,
                    'comment' => '',
                ],
            ],
            'failureResult' => [], // Expected result for record retrieval failure
        ];
        $data['sortOrder'] = $sortAsc ? 'ASC' : 'DESC';
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
     *               - 'types' (string): The types of the query parameters.
     *               - 'params' (array): The parameters to bind to the query.
     *               - 'errorMsg' (string): The message to output for query errors.
     */
    private function createTestQueryParts(array $data): array
    {
        $sql = "SELECT * FROM temperatures WHERE sensor_id = ?";
        $params = [$data['sensor_id']];
        $types = 's';
        $errorMsg = 'No temperatures found.';

        if ($data['system'] !== null) {
            $sql .= " AND system = ?";
            $params[] = $data['system'];
            $types .= "s";
        }

        if ($data['timestamp'] !== null) {
            $sql .= " AND timestamp > FROM_UNIXTIME(UNIX_TIMESTAMP() - ?)";
            $params[] = $data['timestamp'];
            $types .= "i";
        }

        // Determine sort order using helper method
        $sql .= " ORDER BY timestamp {$data['sortOrder']}";

        if ($data['limitToOne']) {
            $sql .= " LIMIT 1";
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
