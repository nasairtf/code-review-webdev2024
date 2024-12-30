<?php

declare(strict_types=1);

namespace Tests\utilities\mocks;

use Mockery;
use Tests\utilities\mocks\MockDatabaseServiceCoreTrait;

/**
 * Trait for mocking and verifying read/query operations for the `executeSelectQuery`
 * method in DatabaseService-derived classes.
 *
 * This trait provides methods to arrange expectations and assertions for unit tests
 * involving `executeSelectQuery`. It is specifically designed for mocking this
 * query method, supporting both success and failure scenarios.
 *
 * Features:
 * - Mocking of the `executeSelectQuery` method with flexible test setups.
 * - Assertions for verifying expected behavior of `executeSelectQuery`.
 *
 * NOTE: This trait is intended exclusively for use in test classes and
 * should never be used in production code.
 */
trait MockDatabaseServiceExecuteSelectQueryTrait
{
    use MockDatabaseServiceCoreTrait;

    /**
     * Arranges expectations for the `executeSelectQuery` method in a test.
     *
     * This method configures the mock object to expect and respond to calls to
     * `executeSelectQuery` with predefined parameters and results.
     *
     * @param array $data Query data including SQL, parameters, types, and results.
     *
     * @return void
     */
    protected function arrangeExecuteSelectQueryExpectations(array $data): void
    {
        $this->mockExecuteSelectQuery(
            $this->srvMock,
            $data['sql'],
            $data['params'],
            $data['types'],
            MYSQLI_ASSOC,
            $data['resultType'],
            $data['result']
        );
    }

    /**
     * Asserts expectations for the `executeSelectQuery` method in a test.
     *
     * Verifies that the method returned the expected result and was called with
     * the correct parameters during the test execution.
     *
     * @param mixed $result The actual result from the method under test.
     * @param array $data Query data including expected results.
     *
     * @return void
     */
    protected function assertExecuteSelectQueryExpectations($result, array $data): void
    {
        $this->assertSame($data['result'], $result);
        $this->srvMock->shouldHaveReceived('executeSelectQuery')
            ->once()
            ->with(
                $data['sql'],
                $data['params'],
                $data['types']
            );
    }

    /**
     * Sets up a mock expectation for the `executeSelectQuery` method.
     *
     * This method prepares the mock object to respond to `executeSelectQuery` calls
     * with a predefined result or to throw an exception in case of failure.
     *
     * @param Mockery\MockInterface $dsMock       The partially mocked derived service.
     * @param string                $query        The query string.
     * @param array                 $params       Parameters to bind to the query.
     * @param string                $types        Parameter types.
     * @param int                   $resultType   The type of result array to return (e.g., MYSQLI_ASSOC).
     * @param bool                  $isSuccess    Mock of success (return result) or failure (throw exception).
     * @param array                 $result       The result to return from the mock.
     *
     * @return void
     */
    protected function mockExecuteSelectQuery(
        Mockery\MockInterface $dsMock,
        string $query,
        array $params,
        string $types,
        int $resultType,
        bool $isSuccess,
        array $result
    ): void {
        $this->arrangeQueryExpectationBehavior(
            $dsMock->shouldReceive('executeSelectQuery')
                ->with(
                    $query,
                    $params,
                    $types
                ),
            $isSuccess,
            $result,
            "Error executing SELECT query."
        );
    }
}
