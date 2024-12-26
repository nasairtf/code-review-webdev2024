<?php

declare(strict_types=1);

namespace Tests\utilities;

use Mockery;
use App\exceptions\DatabaseException;

/**
 * Trait for mocking the DatabaseService class and its methods in unit tests.
 *
 * DatabaseService method usage list: [as of 2024/12/19]
 *
 * __construct         [PUBLIC]    // used  3 times by child classes
 * startTransaction    [PROTECTED] // used  0 times by grandchild classes [mocked in MockBehaviorTrait]
 * commitTransaction   [PROTECTED] // used  0 times by grandchild classes [mocked in MockBehaviorTrait]
 * rollbackTransaction [PROTECTED] // used  0 times by grandchild classes [mocked in MockBehaviorTrait]
 * fetchDataWithQuery  [PROTECTED] // used 24 times by grandchild classes
 * executeSelectQuery  [PROTECTED] // used  1 times by grandchild classes
 * ensureNotEmpty      [PROTECTED] // used  0 times by grandchild classes
 * modifyDataWithQuery [PROTECTED] // used  5 times by grandchild classes
 * executeUpdateQuery  [PROTECTED] // used  8 times by grandchild classes
 * ensureValidRowCount [PROTECTED] // used  0 times by grandchild classes
 * getSortString       [PROTECTED] // used 15 times by grandchild classes
 *
 * NOTE: This trait is intended exclusively for use in test classes and
 * should never be used in production code.
 */
trait DatabaseServiceMockTrait
{
    /**
     * Creates a partial mock of a DatabaseService-derived class for testing.
     *
     * This method is useful for mocking protected methods like `fetchDataWithQuery`,
     * while still allowing other methods of the service to function normally.
     *
     * @param string $serviceClass    Fully qualified class name of the service to mock.
     * @param array  $constructorArgs Constructor arguments for the service being mocked.
     * @param array  $methodsToMock   Methods in the class to mock.
     *
     * @return Mockery\MockInterface Partial mock of the service class.
     */
    protected function createPartialDatabaseServiceMock(
        string $serviceClass,
        array $constructorArgs,
        array $methodsToMock
    ): Mockery\MockInterface {
        return Mockery::mock(
            "{$serviceClass}[" . implode(',', $methodsToMock) . "]",
            $constructorArgs
        )->shouldAllowMockingProtectedMethods();
    }

    /**
     * Sets up a mock expectation for the `getSortString` method.
     *
     * @param Mockery\MockInterface $mock    The mock instance to add behavior to.
     * @param bool                  $sortAsc True (default) for 'ASC', false for 'DESC'.
     *
     * @return void
     */
    protected function mockGetSortString(
        Mockery\MockInterface $mock,
        bool $sortAsc = true
    ): void {
        $sortString = $sortAsc ? 'ASC' : 'DESC';
        $mock->shouldReceive('getSortString')
            ->with($sortAsc)
            ->andReturn($sortString);
    }

    /**
     * Applies success or exception behavior to a mock expectation.
     *
     * @param Mockery\CompositeExpectation
     *                            $dsMockExp   The mock expectation to modify.
     * @param bool                $isSuccess   Determines whether to return a result or throw an exception.
     * @param mixed|null          $result      The result to return on success (ignored if $isSuccess is false).
     * @param string              $errorMsg    The error message for the exception on failure.
     *
     * @return void
     */
    protected function arrangeQueryExpectationBehavior(
        Mockery\CompositeExpectation $dsMockExp,
        bool $isSuccess,
        $result,
        string $errorMsg
    ): void {
        if ($isSuccess) {
            $dsMockExp->once()->andReturn($result);
        } else {
            $dsMockExp->once()->andThrow(new DatabaseException($errorMsg));
            // Expect exception
            $this->expectException(DatabaseException::class);
            $this->expectExceptionMessage($errorMsg);
        }
    }

    /**
     * Sets up a mock expectation for the `fetchDataWithQuery` method.
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

    /**
     * Sets up a mock expectation for the `modifyDataWithQuery` method.
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

    /**
     * Sets up a mock expectation for the `executeSelectQuery` method.
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

    /**
     * Sets up a mock expectation for the `executeUpdateQuery` method.
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

    /**
     * Arranges expectations for mock objects in the test.
     *
     * @param array $data Query data including SQL, parameters, types, and results.
     *
     * @return void
     */
    private function arrangeFetchDataWithQueryExpectations(array $data): void
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
     * Arranges expectations for mock objects in the test.
     *
     * @param array $data Query data including SQL, parameters, types, expected and affected rows.
     *
     * @return void
     */
    private function arrangeModifyDataWithQueryExpectations(array $data): void
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
     * Arranges expectations for mock objects in the test.
     *
     * @param array $data Query data including SQL, parameters, types, and results.
     *
     * @return void
     */
    private function arrangeExecuteSelectQueryExpectations(array $data): void
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
     * Arranges expectations for mock objects in the test.
     *
     * @param array $data Query data including SQL, parameters, types, and results.
     *
     * @return void
     */
    private function arrangeExecuteUpdateQueryExpectations(array $data): void
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
     * Asserts expectations for mock objects and test results.
     *
     * @param mixed $result The actual result from the method under test.
     * @param array $data Query data including expected results.
     *
     * @return void
     */
    private function assertFetchDataWithQueryExpectations($result, array $data): void
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
     * Asserts expectations for mock objects and test results.
     *
     * @param mixed $result The actual result from the method under test.
     * @param array $data Query data including expected results.
     *
     * @return void
     */
    private function assertModifyDataWithQueryExpectations($result, array $data): void
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
     * Asserts expectations for mock objects and test results.
     *
     * @param mixed $result The actual result from the method under test.
     * @param array $data Query data including expected results.
     *
     * @return void
     */
    private function assertExecuteSelectQueryExpectations($result, array $data): void
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
     * Asserts expectations for mock objects and test results.
     *
     * @param mixed $result The actual result from the method under test.
     * @param array $data Query data including expected results.
     *
     * @return void
     */
    private function assertExecuteUpdateQueryExpectations($result, array $data): void
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
}
