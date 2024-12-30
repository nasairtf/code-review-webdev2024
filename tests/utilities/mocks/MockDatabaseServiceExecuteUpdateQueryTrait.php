<?php

declare(strict_types=1);

namespace Tests\utilities\mocks;

use Mockery;
use Tests\utilities\mocks\MockDatabaseServiceCoreTrait;

/**
 * Trait for mocking and verifying write/update operations for the `executeUpdateQuery`
 * method in DatabaseService-derived classes.
 *
 * This trait provides methods to arrange expectations and assertions for unit tests
 * involving `executeUpdateQuery`. It is specifically designed for mocking this
 * query method, supporting both success and failure scenarios.
 *
 * Features:
 * - Mocking of the `executeUpdateQuery` method with flexible test setups.
 * - Assertions for verifying expected behavior of `executeUpdateQuery`.
 *
 * NOTE: This trait is intended exclusively for use in test classes and
 * should never be used in production code.
 */
trait MockDatabaseServiceExecuteUpdateQueryTrait
{
    use MockDatabaseServiceCoreTrait;

    /**
     * Arranges expectations for the `executeUpdateQuery` method in a test.
     *
     * This method configures the mock object to expect and respond to calls to
     * `executeUpdateQuery` with predefined parameters and results.
     *
     * @param array $data Query data including SQL, parameters, types, and results.
     *
     * @return void
     */
    protected function arrangeExecuteUpdateQueryExpectations(array $data): void
    {
        $this->mockExecuteUpdateQuery(
            $this->srvMock,
            $data['sql'],
            $data['params'],
            $data['types'],
            $data['resultType'],
            $data['result']
        );
    }

    /**
     * Asserts expectations for the `executeUpdateQuery` method in a test.
     *
     * Verifies that the method returned the expected result and was called with
     * the correct parameters during the test execution.
     *
     * @param mixed $result The actual result from the method under test.
     * @param array $data Query data including expected results.
     *
     * @return void
     */
    protected function assertExecuteUpdateQueryExpectations($result, array $data): void
    {
        $this->assertSame($data['result'], $result);
        $this->srvMock->shouldHaveReceived('executeUpdateQuery')
            ->once()
            ->with(
                $data['sql'],
                $data['params'],
                $data['types']
            );
    }

    /**
     * Sets up a mock expectation for the `executeUpdateQuery` method.
     *
     * This method prepares the mock object to respond to `executeUpdateQuery` calls
     * with a predefined result or to throw an exception in case of failure.
     *
     * @param Mockery\MockInterface $dsMock       The partially mocked derived service.
     * @param string                $query        The query string.
     * @param array                 $params       Parameters to bind to the query.
     * @param string                $types        Parameter types.
     * @param bool                  $isSuccess    Mock of success (return result) or failure (throw exception).
     * @param int                   $affectedRows The number of affected rows to return.
     *
     * @return void
     */
    protected function mockExecuteUpdateQuery(
        Mockery\MockInterface $dsMock,
        string $query,
        array $params,
        string $types,
        bool $isSuccess,
        int $affectedRows
    ): void {
        $this->arrangeQueryExpectationBehavior(
            $dsMock->shouldReceive('executeUpdateQuery')
                ->with(
                    $query,
                    $params,
                    $types
                ),
            $isSuccess,
            $affectedRows,
            "Error executing INSERT/UPDATE/DELETE query."
        );
    }
}
