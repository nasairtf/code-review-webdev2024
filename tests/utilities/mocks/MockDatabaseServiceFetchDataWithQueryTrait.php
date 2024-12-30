<?php

declare(strict_types=1);

namespace Tests\utilities\mocks;

use Mockery;
use Tests\utilities\mocks\MockDatabaseServiceCoreTrait;

/**
 * Trait for mocking and verifying read/query operations for the `fetchDataWithQuery`
 * method in DatabaseService-derived classes.
 *
 * This trait provides methods to arrange expectations and assertions for unit tests
 * involving `fetchDataWithQuery`. It is specifically designed for mocking this
 * query method, supporting both success and failure scenarios.
 *
 * Features:
 * - Mocking of the `fetchDataWithQuery` method with flexible test setups.
 * - Assertions for verifying expected behavior of `fetchDataWithQuery`.
 *
 * NOTE: This trait is intended exclusively for use in test classes and
 * should never be used in production code.
 */
trait MockDatabaseServiceFetchDataWithQueryTrait
{
    use MockDatabaseServiceCoreTrait;

    /**
     * Arranges expectations for the `fetchDataWithQuery` method in a test.
     *
     * This method configures the mock object to expect and respond to calls to
     * `fetchDataWithQuery` with predefined parameters and results.
     *
     * @param array $data Query data including SQL, parameters, types, and results.
     *
     * @return void
     */
    protected function arrangeFetchDataWithQueryExpectations(array $data): void
    {
        $this->mockFetchDataWithQuery(
            $this->srvMock,
            $data['sql'],
            $data['params'],
            $data['types'],
            $data['errorMsg'],
            $data['resultType'],
            $data['result']
        );
    }

    /**
     * Asserts expectations for the `fetchDataWithQuery` method in a test.
     *
     * Verifies that the method returned the expected result and was called with
     * the correct parameters during the test execution.
     *
     * @param mixed $result The actual result from the method under test.
     * @param array $data Query data including expected results.
     *
     * @return void
     */
    protected function assertFetchDataWithQueryExpectations($result, array $data): void
    {
        $this->assertSame($data['result'], $result);
        $this->srvMock->shouldHaveReceived('fetchDataWithQuery')
            ->once()
            ->with(
                $data['sql'],
                $data['params'],
                $data['types'],
                $data['errorMsg']
            );
    }

    /**
     * Sets up a mock expectation for the `fetchDataWithQuery` method.
     *
     * This method prepares the mock object to respond to `fetchDataWithQuery` calls
     * with a predefined result or to throw an exception in case of failure.
     *
     * @param Mockery\MockInterface $dsMock    The partially mocked derived service.
     * @param string                $query     The query string.
     * @param array                 $params    Parameters to bind to the query.
     * @param string                $types     Parameter types.
     * @param string                $errorMsg  Error message if no data found.
     * @param bool                  $isSuccess Mock of success (return result) or failure (throw exception).
     * @param array|null            $result    The result to return from the mock.
     *
     * @return void
     */
    protected function mockFetchDataWithQuery(
        Mockery\MockInterface $dsMock,
        string $query,
        array $params,
        string $types,
        string $errorMsg,
        bool $isSuccess,
        ?array $result = null
    ): void {
        $this->arrangeQueryExpectationBehavior(
            $dsMock->shouldReceive('fetchDataWithQuery')
                ->with(
                    $query,
                    $params,
                    $types,
                    $errorMsg
                ),
            $isSuccess,
            $result,
            $errorMsg
        );
    }
}
