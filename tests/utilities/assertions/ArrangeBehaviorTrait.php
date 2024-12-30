<?php

declare(strict_types=1);

namespace Tests\utilities\assertions;

use Mockery;

/**
 * Trait for arranging and asserting mocked behavior in unit tests.
 *
 * This trait provides helper methods to simplify the setup and verification of
 * mock behaviors in unit tests. It supports a standardized expectations array format
 * for configuring which methods mocks should or should not receive during a test.
 *
 * Additionally, it includes support for transactional systems, allowing you to arrange
 * and assert transactional method calls (`beginTransaction`, `commit`, `rollback`) on any
 * mock object representing a system that requires atomic operations.
 *
 * Features:
 * - Configures mock expectations for methods that should or should not be called.
 * - Asserts that mock methods were called as expected.
 * - Abstracts transactional method setup and verification.
 * - Designed for use across various testing contexts, including databases, APIs, and more.
 *
 * Example usage:
 * ```
 * use Tests\utilities\assertions\ArrangeBehaviorTrait;
 *
 * class MyTest extends TestCase
 * {
 *     use ArrangeBehaviorTrait;
 *
 *     public function testSomething()
 *     {
 *         $expectations = [
 *             'receive' => [
 *                 [
 *                     'mock' => $mockObject,
 *                     'method' => 'someMethod',
 *                     'args' => ['arg1', 'arg2'],
 *                     'return' => 'result',
 *                     'invocations' => someMethodInvocationsCount,
 *                 ],
 *             ],
 *             'shouldnot' => [
 *                 ['mock' => $mockObject, 'method' => 'anotherMethod'],
 *             ],
 *         ];
 *
 *         $this->arrangeMockBehavior($expectations);
 *         // Execute code under test
 *         $this->assertMockBehavior($expectations);
 *     }
 * }
 * ```
 *
 * **Important**: This trait is intended exclusively for use in test classes and
 * should never be used in production code.
 */
trait ArrangeBehaviorTrait
{
    /**
     * Configures the behavior of mocked objects based on the provided expectations array.
     *
     * This method sets up the mock objects to either receive specific method calls
     * with defined arguments and return values or to ensure that specific methods
     * are not called. The expectations array must include two keys:
     *
     * - `receive`: An array of expectations for methods that should be called on mocks.
     *   Each element in the array is an associative array with the following keys:
     *     - `mock`: The mock object on which the method is expected to be called.
     *     - `method`: The name of the method to be called.
     *     - `args`: (Optional) An array of arguments that the method is expected to receive.
     *     - `return`: (Optional) The value to be returned by the mocked method.
     *
     * - `shouldnot`: An array of expectations for methods that should not be called on mocks.
     *   Each element in the array is an associative array with the following keys:
     *     - `mock`: The mock object on which the method is not expected to be called.
     *     - `method`: The name of the method that should not be called.
     *
     * Example usage:
     * ```
     * $expectations = [
     *     'receive' => [
     *         [
     *             'mock' => $mockObject,
     *             'method' => 'someMethod',
     *             'args' => ['arg1', 'arg2'],
     *             'return' => 'result',
     *         ],
     *     ],
     *     'shouldnot' => [
     *         [
     *             'mock' => $mockObject,
     *             'method' => 'anotherMethod',
     *         ],
     *     ],
     * ];
     * $this->arrangeMockBehavior($expectations);
     * ```
     *
     * @param array $expectations The expectations array defining mock behaviors.
     *
     * @return void
     */
    private function arrangeMockBehavior(array $expectations): void
    {
        foreach ($expectations['receive'] as $expectation) {
            $mock   = $expectation['mock'];
            $method = $expectation['method'];
            $args   = $expectation['args'] ?? [];
            $return = $expectation['return'] ?? null;
            if (!empty($args)) {
                $mock->shouldReceive($method)->with(...$args)->andReturn($return)->once();
            } else {
                $mock->shouldReceive($method)->andReturn($return)->once();
            }
        }
        foreach ($expectations['shouldnot'] as $expectation) {
            $mock   = $expectation['mock'];
            $method = $expectation['method'];
            $mock->shouldNotReceive($method);
        }
    }

    /**
     * Verifies that mocked objects behaved as expected based on the provided expectations array.
     *
     * This method checks whether the mocked objects:
     * - Received specific method calls (as defined in the `receive` key of the expectations array).
     * - Did not receive specific method calls (as defined in the `shouldnot` key of the expectations array).
     *
     * The structure of the `expectations` array is the same as used in `arrangeMockBehavior`.
     *
     * - `receive`: An array of expectations for methods that should have been called on mocks.
     *   Each element in the array is an associative array with the following keys:
     *     - `mock`: The mock object on which the method was expected to be called.
     *     - `method`: The name of the method that should have been called.
     *     - `invocations`: The number of times the method should have been called.
     *
     * - `shouldnot`: An array of expectations for methods that should not have been called on mocks.
     *   Each element in the array is an associative array with the following keys:
     *     - `mock`: The mock object on which the method was not expected to be called.
     *     - `method`: The name of the method that should not have been called.
     *
     * Example usage:
     * ```
     * $expectations = [
     *     'receive' => [
     *         [
     *             'mock' => $mockObject,
     *             'method' => 'someMethod',
     *             'invocations' => someMethodInvocationsCount,
     *         ],
     *     ],
     *     'shouldnot' => [
     *         [
     *             'mock' => $mockObject,
     *             'method' => 'anotherMethod',
     *         ],
     *     ],
     * ];
     * $this->assertMockBehavior($expectations);
     * ```
     *
     * @param array $expectations The expectations array defining expected mock behaviors.
     *
     * @return void
     */
    private function assertMockBehavior(array $expectations): void
    {
        foreach ($expectations['receive'] as $expectation) {
            $mock = $expectation['mock'];
            $method = $expectation['method'];
            $invocations = $expectation['invocations'] ?? 1;
            $mock->shouldHaveReceived($method)->times($invocations);
        }
        foreach ($expectations['shouldnot'] as $expectation) {
            $mock   = $expectation['mock'];
            $method = $expectation['method'];
            $mock->shouldNotHaveReceived($method);
        }
    }

    /**
     * Sets up expectations for transactional methods (beginTransaction, commit, rollback).
     *
     * This method can be used for mocks representing any transactional system, such as
     * databases, file systems, APIs, or state managers, where atomic operations
     * are staged and either committed or rolled back.
     *
     * Example usage:
     * ```
     * $this->arrangeTransactions($dbMock, true); // Expect commit
     * $this->arrangeTransactions($dbMock, false); // Expect rollback
     * ```
     *
     * @param Mockery\MockInterface $mock         The mock object with transactional methods.
     * @param bool                  $shouldCommit Indicates if the transaction should succeed (commit)
     *                                            or fail (rollback).
     * @return void
     */
    private function arrangeTransactions(Mockery\MockInterface $mock, bool $shouldCommit): void
    {
        $mock->shouldReceive('beginTransaction')->andReturnNull()->once();
        if ($shouldCommit) {
            $mock->shouldReceive('commit')->andReturnNull()->once();
            $mock->shouldReceive('rollback')->never();
        } else {
            $mock->shouldReceive('commit')->never();
            $mock->shouldReceive('rollback')->andReturnNull()->once();
        }
    }

    /**
     * Asserts that transactional methods (beginTransaction, commit, rollback) were
     * called as expected during the test.
     *
     * This method can be used for mocks representing any transactional system, such as
     * databases, file systems, APIs, or state managers, where atomic operations
     * are staged and either committed or rolled back.
     *
     * Example usage:
     * ```
     * $this->assertTransactions($dbMock, true); // Assert commit happened
     * $this->assertTransactions($dbMock, false); // Assert rollback happened
     * ```
     *
     * @param Mockery\MockInterface $mock         The mock object with transactional methods.
     * @param bool                  $shouldCommit Indicates if the transaction should have committed (true)
     *                                            or rolled back (false).
     * @return void
     */
    private function assertTransactions(Mockery\MockInterface $mock, bool $shouldCommit): void
    {
        $mock->shouldHaveReceived('beginTransaction')->once();
        if ($shouldCommit) {
            $mock->shouldHaveReceived('commit')->once();
            $mock->shouldNotHaveReceived('rollback');
        } else {
            $mock->shouldNotHaveReceived('commit');
            $mock->shouldHaveReceived('rollback')->once();
        }
    }
}
