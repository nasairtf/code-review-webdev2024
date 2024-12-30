<?php

declare(strict_types=1);

namespace Tests\utilities\mocks;

use Mockery;
use Tests\utilities\mocks\MockDatabaseServiceCoreTrait;

/**
 * Trait for mocking and verifying write/update operations for the `modifyDataWithQuery`
 * method in DatabaseService-derived classes.
 *
 * This trait provides methods to arrange expectations and assertions for unit tests
 * involving `modifyDataWithQuery`. It is specifically designed for mocking this
 * query method, supporting both success and failure scenarios.
 *
 * Features:
 * - Mocking of the `modifyDataWithQuery` method with flexible test setups.
 * - Assertions for verifying expected behavior of `modifyDataWithQuery`.
 *
 * NOTE: This trait is intended exclusively for use in test classes and
 * should never be used in production code.
 */
trait MockDatabaseServiceModifyDataWithQueryTrait
{
    use MockDatabaseServiceCoreTrait;

    /**
     * Arranges expectations for the `modifyDataWithQuery` method in a test.
     *
     * This method configures the mock object to expect and respond to calls to
     * `modifyDataWithQuery` with predefined parameters and results.
     *
     * @param array $data Query data including SQL, parameters, types, expected and affected rows.
     *
     * @return void
     */
    protected function arrangeModifyDataWithQueryExpectations(array $data): void
    {
        $this->mockModifyDataWithQuery(
            $this->srvMock,
            $data['sql'],
            $data['params'],
            $data['types'],
            $data['expectedRows'],
            $data['errorMsg'],
            $data['resultType'],
            $data['result']
        );
    }

    /**
     * Asserts expectations for the `modifyDataWithQuery` method in a test.
     *
     * Verifies that the method returned the expected result and was called with
     * the correct parameters during the test execution.
     *
     * @param mixed $result The actual result from the method under test.
     * @param array $data Query data including expected results.
     *
     * @return void
     */
    protected function assertModifyDataWithQueryExpectations($result, array $data): void
    {
        $this->assertSame($data['result'], $result);
        $this->srvMock->shouldHaveReceived('modifyDataWithQuery')
            ->once()
            ->with(
                $data['sql'],
                $data['params'],
                $data['types'],
                $data['expectedRows'],
                $data['errorMsg']
            );
    }

    /**
     * Sets up a mock expectation for the `modifyDataWithQuery` method.
     *
     * This method prepares the mock object to respond to `modifyDataWithQuery` calls
     * with a predefined result or to throw an exception in case of failure.
     *
     * @param Mockery\MockInterface $dsMock       The partially mocked derived service.
     * @param string                $query        The query string.
     * @param array                 $params       Parameters to bind to the query.
     * @param string                $types        Parameter types.
     * @param int                   $expectedRows The expected number of affected rows.
     * @param string                $errorMsg     Error message if modification fails.
     * @param bool                  $isSuccess    Mock of success (return result) or failure (throw exception).
     * @param int|null              $affectedRows The number of affected rows to return.
     *
     * @return void
     */
    protected function mockModifyDataWithQuery(
        Mockery\MockInterface $dsMock,
        string $query,
        array $params,
        string $types,
        int $expectedRows,
        string $errorMsg,
        bool $isSuccess,
        ?int $affectedRows = null
    ): void {
        $this->arrangeQueryExpectationBehavior(
            $dsMock->shouldReceive('modifyDataWithQuery')
                ->with(
                    $query,
                    $params,
                    $types,
                    $expectedRows,
                    $errorMsg
                ),
            $isSuccess,
            $affectedRows,
            $errorMsg
        );
    }
}
