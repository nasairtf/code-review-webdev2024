<?php

declare(strict_types=1);

namespace Tests\utilities\mocks;

use Mockery;

/**
 * Trait for mocking the DBConnection class in unit tests.
 *
 * This trait provides a mock for the `DBConnection` class, commonly used for managing
 * database connections. It allows the definition of default behaviors for frequently
 * used methods and supports test-specific overrides. Additionally, it includes a helper
 * method for setting expectations on `getInstance` calls.
 *
 * Features:
 * - Mocks the `DBConnection` class with configurable behaviors.
 * - Provides helper methods to set expectations for connection retrieval.
 * - Simplifies database-related unit testing.
 *
 * Example usage:
 * ```
 * $mockDBConnection = $this->createDBConnectionMock(['getError' => 'Mocked error']);
 * $this->mockGetInstance($mockDBConnection, 'test_db');
 * ```
 *
 * NOTE: This trait is intended exclusively for use in test classes and
 * should never be used in production code.
 */
trait MockDBConnectionTrait
{
    /**
     * Creates a mock of the DBConnection class with predefined behavior.
     *
     * @param array $methodResponses An array of method names mapped to their return values
     *                               or callables for custom behavior.
     *
     * @return Mockery\MockInterface The mocked DBConnection instance.
     */
    protected function createDBConnectionMock(array $methodResponses = []): Mockery\MockInterface
    {
        // Create the aliased mock
        $myMock = Mockery::mock('alias:' . \App\services\database\DBConnection::class);

        // Set default behavior for frequently used methods
        foreach ($methodResponses as $method => $response) {
            if (is_callable($response)) {
                $myMock->shouldReceive($method)->andReturnUsing($response);
            } else {
                $myMock->shouldReceive($method)->andReturn($response);
            }
        }

        return $myMock;
    }

    /**
     * Sets up a mock expectation for the `getInstance` method.
     *
     * This helper method defines expectations for retrieving a specific database instance.
     *
     * @param Mockery\MockInterface $dbMock The mocked DBConnection instance.
     * @param string                $dbName The name of the database to retrieve.
     *
     * @return void
     */
    protected function mockGetInstance(
        Mockery\MockInterface $dbMock,
        string $dbName
    ): void {
        $dbMock->shouldReceive('getInstance')
            ->with($dbName, false)
            ->andReturn($dbMock);
    }
}
