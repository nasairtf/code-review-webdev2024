<?php

declare(strict_types=1);

namespace Tests\utilities;

use Mockery;

/**
 * Trait for mocking the DBConnection class in unit tests.
 *
 * NOTE: This trait is intended exclusively for use in test classes and
 * should never be used in production code.
 */
trait DBConnectionMockTrait
{
    /**
     * Creates a mock of the DBConnection class with predefined behavior.
     *
     * @param array $methodResponses Array of methods and their return values.
     * @return Mockery\MockInterface Mocked DBConnection instance.
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
     * Mocks `DBConnection::getInstance` to return a mocked instance.
     *
     * @param Mockery\MockInterface $mockInstance The mocked DBConnection instance to return.
     * @return void
     */
    protected function mockDBConnectionGetInstance(Mockery\MockInterface $mockInstance): void
    {
        Mockery::mock('alias:' . \App\services\database\DBConnection::class)
            ->shouldReceive('getInstance')
            ->andReturn($mockInstance);
    }
}
