<?php

declare(strict_types=1);

namespace Tests\utilities\mocks;

use Mockery;

/**
 * Trait for mocking the MySQLiWrapper class in unit tests.
 *
 * This trait provides a reusable mock for the `MySQLiWrapper` class, which is often used
 * as a foundational component for database interactions. Default behaviors for common
 * methods such as `close`, `beginTransaction`, `commit`, and `rollback` are predefined.
 * These behaviors can be overridden in specific tests if needed.
 *
 * Features:
 * - Mocks frequently used database transaction methods.
 * - Provides default return values for common utility methods.
 * - Ensures tests can focus on higher-level logic without directly interacting with the database.
 *
 * Example usage:
 * ```
 * $mockMySQLiWrapper = $this->createMySQLiWrapperMock();
 * $mockMySQLiWrapper->shouldReceive('prepare')->with('SELECT * FROM table')->andReturn(true);
 * ```
 *
 * NOTE: This trait is intended exclusively for use in test classes and
 * should never be used in production code.
 */
trait MockMySQLiWrapperTrait
{
    /**
     * Creates a mock for the MySQLiWrapper class.
     *
     * This method defines default behaviors for commonly used methods such as `close`,
     * `beginTransaction`, `commit`, and `rollback`. These defaults can be overridden in
     * individual tests as needed.
     *
     * @return Mockery\MockInterface The mocked MySQLiWrapper instance.
     */
    protected function createMySQLiWrapperMock(): Mockery\MockInterface
    {
        // Create the aliased mock
        $myMock = Mockery::mock('alias:' . \App\services\database\MySQLiWrapper::class);

        // Default behaviors across all tests
        $myMock->shouldReceive('close')->andReturnTrue();
        $myMock->shouldReceive('beginTransaction')->andReturnTrue();
        $myMock->shouldReceive('commit')->andReturnTrue();
        $myMock->shouldReceive('rollback')->andReturnTrue();

        // Default behaviors unless specifically overridden in a given test
        $myMock->shouldReceive('getAffectedRows')->andReturnNull()->byDefault();
        $myMock->shouldReceive('getLastInsertId')->andReturnNull()->byDefault();
        $myMock->shouldReceive('getConnectError')->andReturnNull()->byDefault();
        $myMock->shouldReceive('getError')->andReturnNull()->byDefault();
        $myMock->shouldReceive('bindParams')->andReturnTrue()->byDefault();
        $myMock->shouldReceive('prepare')->andReturnTrue()->byDefault();
        $myMock->shouldReceive('query')->andReturnTrue()->byDefault();

        return $myMock;
    }
}
