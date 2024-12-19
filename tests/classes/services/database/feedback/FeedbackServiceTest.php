<?php

declare(strict_types=1);

namespace Tests\classes\services\database\feedback;

use Mockery;
use PHPUnit\Framework\TestCase;
use Tests\utilities\CustomDebugMockTrait;
use Tests\utilities\DBConnectionMockTrait;
use Tests\utilities\PrivatePropertyTrait;
use Tests\utilities\MockBehaviorTrait;
use App\services\database\feedback\FeedbackService;
use App\services\database\DatabaseService;
use App\exceptions\DatabaseException;

/**
 * Unit tests for the FeedbackService class.
 *
 * This test suite validates the behavior of the FeedbackService class,
 * specifically ensuring that its constructor initializes the parent
 * DatabaseService with the correct parameters.
 *
 * List of method tests:
 *
 * Constructor Tests:
 * - testFeedbackServiceConstructorInitializesBaseService [DONE]
 * - testFeedbackServiceConstructorInitializesBaseServiceAndDependencies [DONE]
 * Method Tests:
 * - testInsertFeedbackWithDependenciesSucceeds [DONE]
 * - testInsertFeedbackWithDependenciesFailsWithoutFeedbackWrite [DONE]
 * - testInsertFeedbackWithDependenciesHandlesFeedbackInsertFailure [DONE]
 * - testInsertFeedbackWithDependenciesHandlesInstrumentInsertFailure [DONE]
 * - testInsertFeedbackWithDependenciesHandlesOperatorInsertFailure [DONE]
 * - testInsertFeedbackWithDependenciesHandlesSupportInsertFailure [DONE]
 * - testInsertFeedbackWithDependenciesHandlesEmptyDependencyArrays [DONE]
 *
 * @covers \App\services\database\feedback\FeedbackService
 */
class FeedbackServiceTest extends TestCase
{
    use PrivatePropertyTrait;
    use CustomDebugMockTrait;
    use DBConnectionMockTrait;
    use MockBehaviorTrait;

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
     * Mock instance of FeedbackServiceWrite.
     *
     * @var Mockery\MockInterface
     */
    protected $feedbackWriteMock;

    /**
     * Mock instance of InstrumentServiceWrite.
     *
     * @var Mockery\MockInterface
     */
    protected $instrumentWriteMock;

    /**
     * Mock instance of OperatorServiceWrite.
     *
     * @var Mockery\MockInterface
     */
    protected $operatorWriteMock;

    /**
     * Mock instance of SupportServiceWrite.
     *
     * @var Mockery\MockInterface
     */
    protected $supportWriteMock;

    /**
     * TEST METHOD 1: __construct
     */

    /**
     * Tests the constructor initializes DatabaseService with the correct database name and debug mode.
     *
     * @covers \App\services\database\feedback\FeedbackService::__construct
     */
    public function testFeedbackServiceConstructorInitializesBaseService(): void
    {
        // Act
        $service = new FeedbackService(false);

        // Assert
        $this->assertInstanceOf(DatabaseService::class, $service);
        $this->assertInstanceOf(FeedbackService::class, $service);
    }


    /**
     * Tests the constructor initializes FeedbackService with additional dependencies.
     *
     * @covers \App\services\database\feedback\FeedbackService::__construct
     */
    public function testFeedbackServiceConstructorInitializesBaseServiceAndDependencies(): void
    {
        // Act
        $service = new FeedbackService(
            false,
            $this->feedbackWriteMock,
            $this->instrumentWriteMock,
            $this->operatorWriteMock,
            $this->supportWriteMock,
            $this->dbMock,
            $this->debugMock
        );

        // Assert
        $this->assertInstanceOf(DatabaseService::class, $service);
        $this->assertInstanceOf(FeedbackService::class, $service);
        $this->assertDependency($this->feedbackWriteMock, 'feedbackWrite', $service);
        $this->assertDependency($this->instrumentWriteMock, 'instrumentWrite', $service);
        $this->assertDependency($this->operatorWriteMock, 'operatorWrite', $service);
        $this->assertDependency($this->supportWriteMock, 'supportWrite', $service);
        $this->assertDependency($this->dbMock, 'db', $service);
        $this->assertDependency($this->debugMock, 'debug', $service);
    }

    /**
     * TEST METHOD 2: insertFeedbackWithDependencies
     */

    /**
     * Tests that insertFeedbackWithDependencies successfully inserts feedback and dependencies.
     *
     * This test verifies that all dependent insert operations (feedback, instruments,
     * operators, support astronomers) succeed in a transactional workflow. It ensures
     * that the database commit is called and rollback is not triggered.
     *
     * @covers \App\services\database\feedback\FeedbackService::insertFeedbackWithDependencies
     *
     * @return void
     */
    public function testInsertFeedbackWithDependenciesSucceeds(): void
    {
        // Define the test data
        $data = $this->createTestData();

        // Define the mock expectation data
        $expectations = [
            'receive'   => [
                [
                    'mock' => $this->feedbackWriteMock,
                    'method' => 'insertFeedbackRecord',
                    'args' => [$data['feedback']],
                    'return' => $data['affectedRows'],
                ],
                [
                    'mock' => $this->feedbackWriteMock,
                    'method' => 'returnFeedbackRecordId',
                    'args' => [],
                    'return' => $data['feedbackId'],
                ],
            ],
            'shouldnot' => [],
        ];
        // Add dynamic expectations for instruments
        foreach ($data['instruments'] as $hardwareID) {
            $expectations['receive'][] = [
                'mock' => $this->instrumentWriteMock,
                'method' => 'insertInstrumentRecord',
                'args' => [$data['feedbackId'], $hardwareID],
                'return' => $data['affectedRows'],
                'invocations' => count($data['instruments']),
            ];
        }
        // Add dynamic expectations for operators
        foreach ($data['operators'] as $operatorID) {
            $expectations['receive'][] = [
                'mock' => $this->operatorWriteMock,
                'method' => 'insertOperatorRecord',
                'args' => [$data['feedbackId'], $operatorID],
                'return' => $data['affectedRows'],
                'invocations' => count($data['operators']),
            ];
        }
        // Add dynamic expectations for support astronomers
        foreach ($data['support'] as $supportID) {
            $expectations['receive'][] = [
                'mock' => $this->supportWriteMock,
                'method' => 'insertSupportAstronomerRecord',
                'args' => [$data['feedbackId'], $supportID],
                'return' => $data['affectedRows'],
                'invocations' => count($data['support']),
            ];
        }

        // Arrange

        // Mock the DBConnection method(s) and expected return(s)
        $this->arrangeTransactions($this->dbMock, $data['successResult']);

        // Mock the expected *ServiceWrite behavior
        $this->arrangeMockBehavior($expectations);

        // Act
        $service = new FeedbackService(
            false,
            $this->feedbackWriteMock,
            $this->instrumentWriteMock,
            $this->operatorWriteMock,
            $this->supportWriteMock,
            $this->dbMock,
            $this->debugMock
        );

        // Call the method under test
        $result = $service->insertFeedbackWithDependencies(
            $data['feedback'],
            $data['instruments'],
            $data['operators'],
            $data['support']
        );

        // Assertions

        // Assert the success results match
        $this->assertSame($data['successResult'], $result);

        // Verify *ServiceWrite behavior
        $this->assertMockBehavior($expectations);

        // Verify transaction behavior
        $this->assertTransactions($this->dbMock, $data['successResult']);
    }

    /**
     * Tests that insertFeedbackWithDependencies fails when FeedbackServiceWrite is missing.
     *
     * This test ensures that the method throws a DatabaseException when the FeedbackServiceWrite
     * dependency is null. It also verifies that the transaction is rolled back in such a case,
     * and no commit is attempted.
     *
     * @covers \App\services\database\feedback\FeedbackService::insertFeedbackWithDependencies
     *
     * @return void
     */
    public function testInsertFeedbackWithDependenciesFailsWithoutFeedbackWrite(): void
    {
        // Define the test data
        $data = $this->createTestData();

        // Define the mock expectation data
        $expectations = [
            'receive'   => [],
            'shouldnot' => [
                ['mock' => $this->feedbackWriteMock, 'method' => 'insertFeedbackRecord'],
                ['mock' => $this->feedbackWriteMock, 'method' => 'returnFeedbackRecordId'],
                ['mock' => $this->instrumentWriteMock, 'method' => 'insertInstrumentRecord'],
                ['mock' => $this->operatorWriteMock, 'method' => 'insertOperatorRecord'],
                ['mock' => $this->supportWriteMock, 'method' => 'insertSupportAstronomerRecord'],
            ],
        ];

        // Arrange

        // Mock the DBConnection method(s) and expected return(s)
        $this->arrangeTransactions($this->dbMock, $data['failureResult']);

        // Mock the expected *ServiceWrite behavior
        $this->arrangeMockBehavior($expectations);

        // Mock the expected CustomDebug/DatabaseException behavior
        $error = 'FeedbackServiceWrite is required for insert operations.';
        $this->arrangeFailureExpectations($error);

        // Act
        $service = new FeedbackService(
            false,
            null,
            $this->instrumentWriteMock,
            $this->operatorWriteMock,
            $this->supportWriteMock,
            $this->dbMock,
            $this->debugMock
        );

        // Call the method under test
        $result = $service->insertFeedbackWithDependencies(
            $data['feedback'],
            $data['instruments'],
            $data['operators'],
            $data['support']
        );

        // Assertions

        // Assert the failure results match
        $this->assertSame($data['failureResult'], $result);

        // Verify *ServiceWrite behavior
        $this->assertMockBehavior($expectations);

        // Verify transaction behavior
        $this->assertTransactions($this->dbMock, $data['failureResult']);
    }

    /**
     * Tests that insertFeedbackWithDependencies correctly handles a failure during
     * the feedback record insertion process.
     *
     * This test ensures that if the `insertFeedbackRecord` method throws a
     * `DatabaseException`, the transaction is rolled back, and the method
     * returns the failure result. Additionally, the exception message is
     * validated to confirm the appropriate error formatting.
     *
     * Mock behavior:
     * - `insertFeedbackRecord` throws a `DatabaseException`.
     * - No subsequent insert operations (e.g., instruments, operators, support) are executed.
     * - The transaction is rolled back, and no commit is attempted.
     *
     * Assertions:
     * - The result matches the expected failure result.
     * - All expected mocks are called/not called as per the expectations.
     * - Transactional behavior is verified (rollback triggered, no commit).
     *
     * @covers \App\services\database\feedback\FeedbackService::insertFeedbackWithDependencies
     *
     * @return void
     */
    public function testInsertFeedbackWithDependenciesHandlesFeedbackInsertFailure(): void
    {
        // Define the test data
        $data = $this->createTestData();

        // Define the mock expectation data
        $expectations = [
            'receive'   => [],
            'shouldnot' => [
                ['mock' => $this->instrumentWriteMock, 'method' => 'insertInstrumentRecord'],
                ['mock' => $this->operatorWriteMock, 'method' => 'insertOperatorRecord'],
                ['mock' => $this->supportWriteMock, 'method' => 'insertSupportAstronomerRecord'],
            ],
        ];

        // Arrange

        // Mock the DBConnection method(s) and expected return(s)
        $this->arrangeTransactions($this->dbMock, $data['failureResult']);

        // Mock the FeedbackServiceWrite to throw an exception
        $insertError = 'Feedback insert failed.';
        $this->feedbackWriteMock->shouldReceive('insertFeedbackRecord')
            ->with($data['feedback'])
            ->andThrow(new DatabaseException($insertError))
            ->once();

        // Mock the expected *ServiceWrite behavior
        $this->arrangeMockBehavior($expectations);

        // Mock the expected CustomDebug/DatabaseException behavior
        $this->arrangeFailureExpectations($insertError);

        // Act
        $service = new FeedbackService(
            false,
            $this->feedbackWriteMock,
            $this->instrumentWriteMock,
            $this->operatorWriteMock,
            $this->supportWriteMock,
            $this->dbMock,
            $this->debugMock
        );

        // Call the method under test
        $result = $service->insertFeedbackWithDependencies(
            $data['feedback'],
            $data['instruments'],
            $data['operators'],
            $data['support']
        );

        // Assertions

        // Assert the failure results match
        $this->assertSame($data['failureResult'], $result);

        // Verify *ServiceWrite behavior
        $this->assertMockBehavior($expectations);

        // Verify transaction behavior
        $this->assertTransactions($this->dbMock, $data['failureResult']);
    }

    /**
     * Tests that insertFeedbackWithDependencies correctly handles a failure during
     * the instrument record insertion process.
     *
     * This test ensures that if the `insertInstrumentRecord` method throws a
     * `DatabaseException`, the transaction is rolled back, and the method
     * returns the failure result. Additionally, the exception message is
     * validated to confirm the appropriate error formatting.
     *
     * Mock behavior:
     * - `insertFeedbackRecord` and `returnFeedbackRecordId` execute successfully.
     * - The first call to `insertInstrumentRecord` throws a `DatabaseException`.
     * - No subsequent insert operations (e.g., operators, support) are executed.
     * - The transaction is rolled back, and no commit is attempted.
     *
     * Assertions:
     * - The result matches the expected failure result.
     * - All expected mocks are called/not called as per the expectations.
     * - Transactional behavior is verified (rollback triggered, no commit).
     *
     * @covers \App\services\database\feedback\FeedbackService::insertFeedbackWithDependencies
     *
     * @return void
     */
    public function testInsertFeedbackWithDependenciesHandlesInstrumentInsertFailure(): void
    {
        // Define the test data
        $data = $this->createTestData();

        // Define the mock expectation data
        $expectations = [
            'receive'   => [
                [
                    'mock' => $this->feedbackWriteMock,
                    'method' => 'insertFeedbackRecord',
                    'args' => [$data['feedback']],
                    'return' => $data['affectedRows'],
                ],
                [
                    'mock' => $this->feedbackWriteMock,
                    'method' => 'returnFeedbackRecordId',
                    'args' => [],
                    'return' => $data['feedbackId'],
                ],
            ],
            'shouldnot' => [
                ['mock' => $this->operatorWriteMock, 'method' => 'insertOperatorRecord'],
                ['mock' => $this->supportWriteMock, 'method' => 'insertSupportAstronomerRecord'],
            ],
        ];

        // Arrange

        // Mock the DBConnection method(s) and expected return(s)
        $this->arrangeTransactions($this->dbMock, $data['failureResult']);

        // Mock the InstrumentServiceWrite to throw an exception
        $insertError = 'Instrument insert failed.';
        $this->instrumentWriteMock->shouldReceive('insertInstrumentRecord')
            ->with($data['feedbackId'], $data['instruments'][0])
            ->andThrow(new DatabaseException($insertError))
            ->once();

        // Mock the expected *ServiceWrite behavior
        $this->arrangeMockBehavior($expectations);

        // Mock the expected CustomDebug/DatabaseException behavior
        $this->arrangeFailureExpectations($insertError);

        // Act
        $service = new FeedbackService(
            false,
            $this->feedbackWriteMock,
            $this->instrumentWriteMock,
            $this->operatorWriteMock,
            $this->supportWriteMock,
            $this->dbMock,
            $this->debugMock
        );

        // Call the method under test
        $result = $service->insertFeedbackWithDependencies(
            $data['feedback'],
            $data['instruments'],
            $data['operators'],
            $data['support']
        );

        // Assertions

        // Assert the failure results match
        $this->assertSame($data['failureResult'], $result);

        // Verify *ServiceWrite behavior
        $this->assertMockBehavior($expectations);

        // Verify transaction behavior
        $this->assertTransactions($this->dbMock, $data['failureResult']);
    }

    /**
     * Tests that insertFeedbackWithDependencies correctly handles a failure during
     * the operator record insertion process.
     *
     * This test ensures that if the `insertOperatorRecord` method throws a
     * `DatabaseException`, the transaction is rolled back, and the method
     * returns the failure result. Additionally, the exception message is
     * validated to confirm the appropriate error formatting.
     *
     * Mock behavior:
     * - `insertFeedbackRecord` and `returnFeedbackRecordId` execute successfully.
     * - All `insertInstrumentRecord` calls execute successfully.
     * - The first call to `insertOperatorRecord` throws a `DatabaseException`.
     * - No subsequent insert operations (e.g., support) are executed.
     * - The transaction is rolled back, and no commit is attempted.
     *
     * Assertions:
     * - The result matches the expected failure result.
     * - All expected mocks are called/not called as per the expectations.
     * - Transactional behavior is verified (rollback triggered, no commit).
     *
     * @covers \App\services\database\feedback\FeedbackService::insertFeedbackWithDependencies
     *
     * @return void
     */
    public function testInsertFeedbackWithDependenciesHandlesOperatorInsertFailure(): void
    {
        // Define the test data
        $data = $this->createTestData();

        // Define the mock expectation data
        $expectations = [
            'receive'   => [
                [
                    'mock' => $this->feedbackWriteMock,
                    'method' => 'insertFeedbackRecord',
                    'args' => [$data['feedback']],
                    'return' => $data['affectedRows'],
                ],
                [
                    'mock' => $this->feedbackWriteMock,
                    'method' => 'returnFeedbackRecordId',
                    'args' => [],
                    'return' => $data['feedbackId'],
                ],
            ],
            'shouldnot' => [
                ['mock' => $this->supportWriteMock, 'method' => 'insertSupportAstronomerRecord'],
            ],
        ];
        // Add dynamic expectations for instruments
        foreach ($data['instruments'] as $hardwareID) {
            $expectations['receive'][] = [
                'mock' => $this->instrumentWriteMock,
                'method' => 'insertInstrumentRecord',
                'args' => [$data['feedbackId'], $hardwareID],
                'return' => $data['affectedRows'],
                'invocations' => count($data['instruments']),
            ];
        }

        // Arrange

        // Mock the DBConnection method(s) and expected return(s)
        $this->arrangeTransactions($this->dbMock, $data['failureResult']);

        // Mock the InstrumentServiceWrite to throw an exception
        $insertError = 'Telescope operator insert failed.';
        $this->operatorWriteMock->shouldReceive('insertOperatorRecord')
            ->with($data['feedbackId'], $data['operators'][0])
            ->andThrow(new DatabaseException($insertError))
            ->once();

        // Mock the expected *ServiceWrite behavior
        $this->arrangeMockBehavior($expectations);

        // Mock the expected CustomDebug/DatabaseException behavior
        $this->arrangeFailureExpectations($insertError);

        // Act
        $service = new FeedbackService(
            false,
            $this->feedbackWriteMock,
            $this->instrumentWriteMock,
            $this->operatorWriteMock,
            $this->supportWriteMock,
            $this->dbMock,
            $this->debugMock
        );

        // Call the method under test
        $result = $service->insertFeedbackWithDependencies(
            $data['feedback'],
            $data['instruments'],
            $data['operators'],
            $data['support']
        );

        // Assertions

        // Assert the failure results match
        $this->assertSame($data['failureResult'], $result);

        // Verify *ServiceWrite behavior
        $this->assertMockBehavior($expectations);

        // Verify transaction behavior
        $this->assertTransactions($this->dbMock, $data['failureResult']);
    }

    /**
     * Tests that insertFeedbackWithDependencies correctly handles a failure during
     * the support record insertion process.
     *
     * This test ensures that if the `insertSupportAstronomerRecord` method throws a
     * `DatabaseException`, the transaction is rolled back, and the method
     * returns the failure result. Additionally, the exception message is
     * validated to confirm the appropriate error formatting.
     *
     * Mock behavior:
     * - `insertFeedbackRecord` and `returnFeedbackRecordId` execute successfully.
     * - All `insertInstrumentRecord` and `insertOperatorRecord` calls execute successfully.
     * - The first call to `insertSupportAstronomerRecord` throws a `DatabaseException`.
     * - The transaction is rolled back, and no commit is attempted.
     *
     * Assertions:
     * - The result matches the expected failure result.
     * - All expected mocks are called/not called as per the expectations.
     * - Transactional behavior is verified (rollback triggered, no commit).
     *
     * @covers \App\services\database\feedback\FeedbackService::insertFeedbackWithDependencies
     *
     * @return void
     */
    public function testInsertFeedbackWithDependenciesHandlesSupportInsertFailure(): void
    {
        // Define the test data
        $data = $this->createTestData();

        // Define the mock expectation data
        $expectations = [
            'receive'   => [
                [
                    'mock' => $this->feedbackWriteMock,
                    'method' => 'insertFeedbackRecord',
                    'args' => [$data['feedback']],
                    'return' => $data['affectedRows'],
                ],
                [
                    'mock' => $this->feedbackWriteMock,
                    'method' => 'returnFeedbackRecordId',
                    'args' => [],
                    'return' => $data['feedbackId'],
                ],
            ],
            'shouldnot' => [],
        ];
        // Add dynamic expectations for instruments
        foreach ($data['instruments'] as $hardwareID) {
            $expectations['receive'][] = [
                'mock' => $this->instrumentWriteMock,
                'method' => 'insertInstrumentRecord',
                'args' => [$data['feedbackId'], $hardwareID],
                'return' => $data['affectedRows'],
                'invocations' => count($data['instruments']),
            ];
        }
        // Add dynamic expectations for operators
        foreach ($data['operators'] as $operatorID) {
            $expectations['receive'][] = [
                'mock' => $this->operatorWriteMock,
                'method' => 'insertOperatorRecord',
                'args' => [$data['feedbackId'], $operatorID],
                'return' => $data['affectedRows'],
                'invocations' => count($data['operators']),
            ];
        }

        // Arrange

        // Mock the DBConnection method(s) and expected return(s)
        $this->arrangeTransactions($this->dbMock, $data['failureResult']);

        // Mock the InstrumentServiceWrite to throw an exception
        $insertError = 'Support astronomer insert failed.';
        $this->supportWriteMock->shouldReceive('insertSupportAstronomerRecord')
            ->with($data['feedbackId'], $data['support'][0])
            ->andThrow(new DatabaseException($insertError))
            ->once();

        // Mock the expected *ServiceWrite behavior
        $this->arrangeMockBehavior($expectations);

        // Mock the expected CustomDebug/DatabaseException behavior
        $this->arrangeFailureExpectations($insertError);

        // Act
        $service = new FeedbackService(
            false,
            $this->feedbackWriteMock,
            $this->instrumentWriteMock,
            $this->operatorWriteMock,
            $this->supportWriteMock,
            $this->dbMock,
            $this->debugMock
        );

        // Call the method under test
        $result = $service->insertFeedbackWithDependencies(
            $data['feedback'],
            $data['instruments'],
            $data['operators'],
            $data['support']
        );

        // Assertions

        // Assert the failure results match
        $this->assertSame($data['failureResult'], $result);

        // Verify *ServiceWrite behavior
        $this->assertMockBehavior($expectations);

        // Verify transaction behavior
        $this->assertTransactions($this->dbMock, $data['failureResult']);
    }

    /**
     * Tests that insertFeedbackWithDependencies handles empty dependency lists gracefully.
     *
     * This test ensures that the method successfully inserts a feedback record even when
     * the instrument, operator, and support astronomer data arrays are empty. It verifies
     * that the transaction is committed and no unnecessary operations are attempted.
     *
     * @covers \App\services\database\feedback\FeedbackService::insertFeedbackWithDependencies
     *
     * @return void
     */
    public function testInsertFeedbackWithDependenciesHandlesEmptyDependencyArrays(): void
    {
        // Define the test data
        $data = $this->createTestData();
        $data['instruments'] = [];
        $data['operators'] = [];
        $data['support'] = [];

        // Define the mock expectation data
        $expectations = [
            'receive'   => [
                [
                    'mock' => $this->feedbackWriteMock,
                    'method' => 'insertFeedbackRecord',
                    'args' => [$data['feedback']],
                    'return' => $data['affectedRows'],
                ],
                [
                    'mock' => $this->feedbackWriteMock,
                    'method' => 'returnFeedbackRecordId',
                    'args' => [],
                    'return' => $data['feedbackId'],
                ],
            ],
            'shouldnot' => [
                ['mock' => $this->instrumentWriteMock, 'method' => 'insertInstrumentRecord'],
                ['mock' => $this->operatorWriteMock, 'method' => 'insertOperatorRecord'],
                ['mock' => $this->supportWriteMock, 'method' => 'insertSupportAstronomerRecord'],
            ],
        ];

        // Arrange

        // Mock the DBConnection method(s) and expected return(s)
        $this->arrangeTransactions($this->dbMock, $data['successResult']);

        // Mock the expected *ServiceWrite behavior
        $this->arrangeMockBehavior($expectations);

        // Act
        $service = new FeedbackService(
            false,
            $this->feedbackWriteMock,
            $this->instrumentWriteMock,
            $this->operatorWriteMock,
            $this->supportWriteMock,
            $this->dbMock,
            $this->debugMock
        );

        // Call the method under test
        $result = $service->insertFeedbackWithDependencies(
            $data['feedback'],
            $data['instruments'],
            $data['operators'],
            $data['support']
        );

        // Assertions

        // Assert the success results match
        $this->assertSame($data['successResult'], $result);

        // Verify *ServiceWrite behavior
        $this->assertMockBehavior($expectations);

        // Verify transaction behavior
        $this->assertTransactions($this->dbMock, $data['successResult']);
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
        // Ensure parent setup runs if necessary
        parent::setUp();

        // Set up CustomDebug Mock
        $this->debugMock = $this->createCustomDebugMock();

        // Set up DBConnection Mock
        $this->dbMock = $this->createDBConnectionMock();

        // Mock the DBConnection getInstance method
        $this->dbMock->shouldReceive('getInstance')
            ->with('feedback', false)
            ->andReturn($this->dbMock);

        // Mock the database table write classes
        $this->feedbackWriteMock = Mockery::mock(\App\services\database\feedback\write\FeedbackService::class);
        $this->instrumentWriteMock = Mockery::mock(\App\services\database\feedback\write\InstrumentService::class);
        $this->operatorWriteMock = Mockery::mock(\App\services\database\feedback\write\OperatorService::class);
        $this->supportWriteMock = Mockery::mock(\App\services\database\feedback\write\SupportService::class);
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
        return [
            // test inputs (data arrays for testing)
            'feedback' => [
                'start_date' => 1732528800,
                'end_date' => 1734084000,
                'technical_rating' => 4,
                'technical_comments' => 'Dithering issues',
                'scientific_staff_rating' => 5,
                'TO_rating' => 5,
                'daycrew_rating' => null,
                'personnel_comment' => 'Great effort overall.',
                'scientific_results' => 'The shift went smoothly.',
                'suggestions' => 'Please put in the full name of the operators.',
                'name' => 'John Doe',
                'email' => 'johndoe@example.com',
                'location' => 1,
                'programID' => 31,
                'semesterID' => '2024B',
            ],
            'instruments' => ['moris', 'spex', 'texes'],
            'operators' => ['BM', 'CM', 'BW', 'TM'],
            'support' => ['MC', 'AB'],
            // test outputs (method return values)
            'feedbackId' => 6432,     // Mocked ID returned from FeedbackWrite
            'affectedRows' => 1,      // Expected affectedRow count for each insert method call
            'successResult' => true,  // Expected result for successful record insertion
            'failureResult' => false, // Expected result for record insertion failure
        ];
    }

    /**
     * Sets up mock expectations for handling failure scenarios within transactional workflows.
     *
     * This helper method configures the `debugMock` to simulate and validate the behavior
     * of the system when an error occurs during a transactional operation. It ensures that:
     * - Two calls to `failDatabase` are mocked:
     *   - The first call handles the original error message.
     *   - The second call handles the formatted transaction error message.
     * - A `DatabaseException` with the formatted transaction error message is expected.
     *
     * Example behavior:
     * - When a failure occurs in any of the insert operations, this method ensures
     *   that both the original and transaction error messages are logged.
     * - The test then asserts that the transaction is rolled back and the correct
     *   `DatabaseException` is thrown.
     *
     * @param string $error The error message associated with the failure.
     *
     * @return void
     */
    private function arrangeFailureExpectations(string $error): void
    {
        // Mock the CustomDebug method(s) and expected return(s)
        $transactionError = "Transaction failed: {$error}";
        $this->mockFail(
            $this->debugMock,
            'failDatabase',
            $error,
            new DatabaseException($error)
        );
        $this->mockFail(
            $this->debugMock,
            'failDatabase',
            $transactionError,
            new DatabaseException($transactionError)
        );

        // Expect exception
        $this->expectException(DatabaseException::class);
        $this->expectExceptionMessage($transactionError);
    }
}
