<?php

declare(strict_types=1);

namespace Tests\utilities\mocks;

use Mockery;
use App\exceptions\DatabaseException;

/**
 * Core utilities for mocking DatabaseService-derived classes.
 *
 * This trait provides foundational methods for creating and configuring mocks of
 * `DatabaseService` and its derivatives. It enables precise control over the behavior
 * of protected methods during testing, while maintaining normal functionality for
 * other methods in the mocked service.
 *
 * Key Features:
 * - Partial mock creation for `DatabaseService` derivatives.
 * - Flexible arrangement of success or failure scenarios for query-related methods.
 * - Simplified mocking of the `getSortString` method.
 *
 * Example Usage:
 * ```
 * $partialMock = $this->createPartialDatabaseServiceMock(
 *     MyDatabaseService::class,
 *     [$dbMock, $debugMock],
 *     ['fetchDataWithQuery']
 * );
 *
 * $this->arrangeQueryExpectationBehavior(
 *     $partialMock->shouldReceive('fetchDataWithQuery'),
 *     true,
 *     ['result' => 'success'],
 *     'Query error'
 * );
 * ```
 *
 * DatabaseService method usage list: [as of 2024/12/19]
 *
 * __construct         [PUBLIC]    //  3x in child classes
 * startTransaction    [PROTECTED] //  0x in grandchild classes [mocked in ArrangeBehaviorTrait]
 * commitTransaction   [PROTECTED] //  0x in grandchild classes [mocked in ArrangeBehaviorTrait]
 * rollbackTransaction [PROTECTED] //  0x in grandchild classes [mocked in ArrangeBehaviorTrait]
 * fetchDataWithQuery  [PROTECTED] // 24x in grandchild classes [mocked in MockDatabaseServiceFetchDataWithQueryTrait]
 * executeSelectQuery  [PROTECTED] //  1x in grandchild classes [mocked in MockDatabaseServiceExecuteSelectQueryTrait]
 * ensureNotEmpty      [PROTECTED] //  0x in grandchild classes [mocked in MockDatabaseServiceFetchDataWithQueryTrait]
 * modifyDataWithQuery [PROTECTED] //  5x in grandchild classes [mocked in MockDatabaseServiceModifyDataWithQueryTrait]
 * executeUpdateQuery  [PROTECTED] //  8x in grandchild classes [mocked in MockDatabaseServiceExecuteUpdateQueryTrait]
 * ensureValidRowCount [PROTECTED] //  0x in grandchild classes [mocked in MockDatabaseServiceModifyDataWithQueryTrait]
 * getSortString       [PROTECTED] // 15x in grandchild classes
 *
 * NOTE: This trait is intended exclusively for use in test classes and
 * should never be used in production code.
 */
trait MockDatabaseServiceCoreTrait
{
    /**
     * Creates a partial mock of a DatabaseService-derived class for testing.
     *
     * This method is ideal for mocking protected methods, such as
     * `fetchDataWithQuery` or `modifyDataWithQuery`, while keeping the
     * default behavior of other methods intact. It allows test cases
     * to isolate specific behaviors in derived service classes.
     *
     * @param string $serviceClass    Fully qualified class name of the service to mock.
     * @param array  $constructorArgs Constructor arguments for the service being mocked.
     * @param array  $methodsToMock   Methods in the class to mock (protected or public).
     *
     * @return Mockery\MockInterface Partial mock of the service class.
     *
     * Example:
     * ```
     * $mock = $this->createPartialDatabaseServiceMock(
     *     \App\services\MyService::class,
     *     [$dbMock, $debugMock],
     *     ['fetchDataWithQuery', 'getSortString']
     * );
     * ```
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
     * Configures a mock expectation with success or failure behavior.
     *
     * This method allows dynamic configuration of a mock expectation, specifying
     * whether it should return a result (success) or throw an exception (failure).
     *
     * @param Mockery\CompositeExpectation
     *                    $dsMockExp   The mock expectation to modify.
     * @param bool        $isSuccess   Whether the mock should succeed (return a result) or fail (throw).
     * @param mixed|null  $result      The result to return on success (ignored if `$isSuccess` is false).
     * @param string      $errorMsg    The error message for the exception on failure.
     *
     * @return void
     *
     * Example:
     * ```
     * $this->arrangeQueryExpectationBehavior(
     *     $mock->shouldReceive('fetchDataWithQuery'),
     *     true,
     *     ['id' => 1, 'name' => 'test'],
     *     'Query failed'
     * );
     * ```
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
     * Sets up a mock expectation for the `getSortString` method.
     *
     * This method simplifies mocking of the `getSortString` protected method,
     * which determines the sort order for database queries. It ensures that
     * the correct sort direction ('ASC' or 'DESC') is returned based on the
     * provided boolean flag.
     *
     * @param Mockery\MockInterface $mock    The mock instance to add behavior to.
     * @param bool                  $sortAsc Whether to return 'ASC' (true, default) or 'DESC' (false).
     *
     * @return void
     *
     * Example:
     * ```
     * $this->mockGetSortString($mock, true); // Mock to return 'ASC'
     * $this->mockGetSortString($mock, false); // Mock to return 'DESC'
     * ```
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
