<?php

declare(strict_types=1);

namespace Tests\classes\services\database\troublelog\write;

use Mockery;
use PHPUnit\Framework\TestCase;
use Tests\utilities\helpers\UnitTestSetupTrait;
use Tests\utilities\helpers\UnitTestTeardownTrait;
use Tests\utilities\mocks\MockDebugTrait;
use Tests\utilities\mocks\MockDBConnectionTrait;
use Tests\utilities\mocks\MockDatabaseServiceExecuteUpdateQueryTrait;
use Tests\classes\services\database\troublelog\write\TestDailyOperatorService;
use App\services\database\troublelog\write\DailyOperatorService;
use App\exceptions\DatabaseException;

/**
 * Unit tests for the DailyOperatorService write class.
 *
 * This test suite validates the behavior of the DailyOperatorService class,
 * specifically ensuring that its write operations interact with the database
 * as expected.
 *
 * List of method tests:
 *
 * - testDeleteOperatorsSucceeds [DONE]
 * - testDeleteOperatorsFails [DONE]
 * - testUpdateOperatorsInfileSucceeds [DONE]
 * - testUpdateOperatorsInfileFails [DONE]
 *
 * @covers \App\services\database\troublelog\write\DailyOperatorService
 */
class DailyOperatorServiceWriteTest extends TestCase
{
    use UnitTestSetupTrait;
    use UnitTestTeardownTrait;
    use MockDebugTrait;
    use MockDBConnectionTrait;
    use MockDatabaseServiceExecuteUpdateQueryTrait;

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
     * Partial mock of DailyOperatorService.
     *
     * @var DailyOperatorService
     */
    private $srvMock;

    /**
     * TEST METHOD 1: deleteOperators
     */

    /**
     * @covers \App\services\database\troublelog\write\DailyOperatorService::deleteOperators
     *
     * @return void
     */
    public function testDeleteOperatorsSucceeds(): void
    {
        // Define the test data
        $data = $this->createTestData('delete');
        $data['query']['resultType'] = true;

        // Arrange
        $this->arrangeExecuteUpdateQueryExpectations($data['query']);

        // Act
        $result = $this->srvMock->deleteOperators($data['delete']);

        // Assert
        $this->assertExecuteUpdateQueryExpectations($result, $data['query']);
    }

    /**
     * @covers \App\services\database\troublelog\write\DailyOperatorService::deleteOperators
     *
     * @return void
     */
    public function testDeleteOperatorsFails(): void
    {
        // Define the test data
        $data = $this->createTestData('delete');
        $data['query']['resultType'] = false;

        // Arrange
        $this->arrangeExecuteUpdateQueryExpectations($data['query']);

        // Act
        $result = $this->srvMock->deleteOperators($data['delete']);
    }

    /**
     * TEST METHOD 2: updateOperatorsInfile
     */

    /**
     * @covers \App\services\database\troublelog\write\DailyOperatorService::updateOperatorsInfile
     *
     * @return void
     */
    public function testUpdateOperatorsInfileSucceeds(): void
    {
        // Define the test data
        $data = $this->createTestData('infile');
        $data['query']['resultType'] = true;

        // Arrange
        $this->arrangeExecuteUpdateQueryExpectations($data['query']);

        // Act
        $result = $this->srvMock->updateOperatorsInfile($data['infile']);

        // Assert
        $this->assertExecuteUpdateQueryExpectations($result, $data['query']);
    }

    /**
     * @covers \App\services\database\troublelog\write\DailyOperatorService::updateOperatorsInfile
     *
     * @return void
     */
    public function testUpdateOperatorsInfileFails(): void
    {
        // Define the test data
        $data = $this->createTestData('infile');
        $data['query']['resultType'] = false;

        // Arrange
        $this->arrangeExecuteUpdateQueryExpectations($data['query']);

        // Act
        $result = $this->srvMock->updateOperatorsInfile($data['infile']);
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
            DailyOperatorService::class,
            [false, $this->dbMock, $this->debugMock],
            ['executeUpdateQuery']
        );
    }

    /**
     * Creates an array containing the standard test data for 'feedback', 'instruments',
     * 'operators', and 'support'. Can be overridden locally in individual tests.
     *
     * The array indices are 'feedback', 'instruments', 'operators', and 'support'.
     *
     * @return array Array of test data arrays.
     */
    private function createTestData(string $type = 'delete'): array
    {
        // Set up the test data
        $data = [
            // test inputs (data arrays for testing)
            'delete' => "DELETE;",
            'infile' => "INFILE",
            'type' => $type,
        ];
        // Expected result for each query part
        $data['query'] = $this->createTestQueryParts($data);
        return $data;
    }

    /**
     * Generates query components for inserting an operator record.
     *
     * This method creates the SQL query string, parameter array, parameter types string,
     * expected affected row count, and error message for inserting an operator record
     * into the `operator` table.
     *
     * @param int   $feedbackId The ID of the feedback record associated with this operator.
     * @param array $data       The data to be inserted, containing the operator ID as the first element.
     *
     * @return array Associative array containing:
     *               - 'sql' (string): The SQL query string.
     *               - 'params' (array): The parameters to bind to the query.
     *               - 'types' (string): The types of the parameters.
     *               - 'expectedRows' (int): The expected number of affected rows.
     *               - 'errorMsg' (string): The error message to throw on failure.
     */
    private function createTestQueryParts($data): array
    {
        return [
            // Query's SQL string
            'sql' => ($data['type'] === 'delete')
                ? "DELETE;"
                : "INFILE",
            // Query's params array
            'params' => [],
            // Query's params types string
            'types' => '',
            // Query's expected row count
            'expectedRows' => 1,
            // Query's failure error message
            'errorMsg' => 'Error executing INSERT/UPDATE/DELETE query.',
            // Query's affected row count
            'result' => 1,
        ];
    }
}
