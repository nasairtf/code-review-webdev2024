<?php

declare(strict_types=1);

namespace Tests\utilities;

use Mockery;

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
     * Sets up a mock expectation for the `fetchDataWithQuery` method.
     *
     * @param Mockery\MockInterface $dsMock       The partially mocked derived service.
     * @param string                $query        The query string.
     * @param array                 $params       Parameters to bind to the query.
     * @param string                $types        Parameter types.
     * @param array                 $result       The result to return from the mock.
     * @param string|null           $errorMessage Error message if no data found.
     *
     * @return void
     */
    protected function mockFetchDataWithQuery(
        Mockery\MockInterface $dsMock,
        string $query,
        array $params,
        string $types,
        array $result,
        ?string $errorMessage = null
    ): void {
        $dsMock->shouldReceive('fetchDataWithQuery')
            ->with(
                $query,
                $params,
                $types,
                $errorMessage ?? Mockery::any()
            )
            ->andReturn($result);
    }

    /**
     * Sets up a mock expectation for the `modifyDataWithQuery` method.
     *
     * @param Mockery\MockInterface $dsMock       The partially mocked derived service.
     * @param string                $query        The query string.
     * @param array                 $params       Parameters to bind to the query.
     * @param string                $types        Parameter types.
     * @param int                   $affectedRows The number of affected rows to return.
     * @param string|null           $errorMessage Error message if modification fails.
     *
     * @return void
     */
    protected function mockModifyDataWithQuery(
        Mockery\MockInterface $dsMock,
        string $query,
        array $params,
        string $types,
        int $affectedRows,
        ?string $errorMessage = null
    ): void {
        $dsMock->shouldReceive('modifyDataWithQuery')
            ->with(
                $query,
                $params,
                $types,
                $errorMessage ?? Mockery::any()
            )
            ->andReturn($affectedRows);
    }

    /**
     * Sets up a mock expectation for the `executeSelectQuery` method.
     *
     * @param Mockery\MockInterface $dsMock       The partially mocked derived service.
     * @param string                $query        The query string.
     * @param array                 $params       Parameters to bind to the query.
     * @param string                $types        Parameter types.
     * @param int                   $resultType   The type of result array to return (e.g., MYSQLI_ASSOC).
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
        array $result
    ): void {
        $dsMock->shouldReceive('executeSelectQuery')
            ->with(
                $query,
                $params,
                $types
            )
            ->andReturn($result);
    }

    /**
     * Sets up a mock expectation for the `executeUpdateQuery` method.
     *
     * @param Mockery\MockInterface $dsMock       The partially mocked derived service.
     * @param string                $query        The query string.
     * @param array                 $params       Parameters to bind to the query.
     * @param string                $types        Parameter types.
     * @param int                   $affectedRows The number of affected rows to return.
     *
     * @return void
     */
    protected function mockExecuteUpdateQuery(
        Mockery\MockInterface $dsMock,
        string $query,
        array $params,
        string $types,
        int $affectedRows
    ): void {
        $dsMock->shouldReceive('executeUpdateQuery')
            ->with(
                $query,
                $params,
                $types
            )
            ->andReturn($affectedRows);
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
}
