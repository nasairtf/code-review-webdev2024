<?php

declare(strict_types=1);

namespace Tests\classes\services\database\troublelog\write;

use Mockery;
use PHPUnit\Framework\TestCase;
use Tests\utilities\UnitTestTeardownTrait;
use Tests\utilities\CustomDebugMockTrait;
use Tests\utilities\DBConnectionMockTrait;
use Tests\utilities\DatabaseServiceMockTrait;
use Tests\classes\services\database\troublelog\write\TestObsAppService;
use App\services\database\troublelog\write\ObsAppService;
use App\exceptions\DatabaseException;

/**
 * Unit tests for the ObsAppService write class.
 *
 * This test suite validates the behavior of the ObsAppService class,
 * specifically ensuring that its write operations interact with the database
 * as expected.
 *
 * List of method tests:
 *
 * - testModifyProposalCreationDateSucceeds [DONE]
 * - testModifyProposalCreationDateFails [DONE]
 * - testGetUpdateProposalCreationDateQuery [DONE]
 *
 * @covers \App\services\database\troublelog\write\ObsAppService
 */
class ObsAppServiceWriteTest extends TestCase
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
     * Partial mock of ObsAppService.
     *
     * @var ObsAppService
     */
    private $srvMock;

    /**
     * TEST METHOD 1: modifyProposalCreationDate
     */

    /**
     * @covers \App\services\database\troublelog\write\ObsAppService::modifyProposalCreationDate
     *
     * @return void
     */
    public function testModifyProposalCreationDateSucceeds(): void
    {
        // Define the test data
        $data = $this->createTestData();
        $data['query']['resultType'] = true;

        // Arrange
        $this->arrangeModifyDataWithQueryExpectations($data['query']);

        // Act
        $result = $this->srvMock->modifyProposalCreationDate($data['obsAppId'], $data['timestamp']);

        // Assert
        $this->assertModifyDataWithQueryExpectations($result, $data['query']);
    }

    /**
     * @covers \App\services\database\troublelog\write\ObsAppService::modifyProposalCreationDate
     *
     * @return void
     */
    public function testModifyProposalCreationDateFails(): void
    {
        // Define the test data
        $data = $this->createTestData();
        $data['query']['resultType'] = false;

        // Arrange
        $this->arrangeModifyDataWithQueryExpectations($data['query']);

        // Act
        $result = $this->srvMock->modifyProposalCreationDate($data['obsAppId'], $data['timestamp']);
    }

    /**
     * TEST METHOD 2: getUpdateProposalCreationDateQuery [PROTECTED]
     */

    /**
     * @covers \App\services\database\troublelog\write\ObsAppService::getUpdateProposalCreationDateQuery
     *
     * @return void
     */
    public function testGetUpdateProposalCreationDateQuery(): void
    {
        // Define the test data
        $data = $this->createTestData();

        // Arrange
        $service = new TestObsAppService(false, $this->dbMock, $this->debugMock);

        // Act
        $result = $service->getUpdateProposalCreationDateQueryProxy();

        // Assert
        $expect = $data['query']['sql'];
        $this->assertSame($expect, $result);
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
            ObsAppService::class,
            [false, $this->dbMock, $this->debugMock],
            ['modifyDataWithQuery']
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
    private function createTestData(): array
    {
        // Set up the test data
        $data = [
            // test inputs (data arrays for testing)
            'obsAppId' => 3288,
            'timestamp' => 1728315304,
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
            'sql' => "UPDATE ObsApp SET creationDate = ? WHERE ObsApp_id = ?;",
            // Query's params array
            'params' => [$data['timestamp'], $data['obsAppId']],
            // Query's params types string
            'types' => 'ii',
            // Query's expected row count
            'expectedRows' => 1,
            // Query's failure error message
            'errorMsg' => 'Timestamp update failed.',
            // Query's affected row count
            'result' => 1,
        ];
    }
}
