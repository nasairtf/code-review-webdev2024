<?php

declare(strict_types=1);

namespace Tests\utilities;

use Mockery;

/**
 * Trait for mocking the MySQLiWrapper class in unit tests.
 *
 * NOTE: This trait is intended exclusively for use in test classes and
 * should never be used in production code.
 */
trait MySQLiWrapperMockTrait
{
    /**
     * Creates a mock for the MySQLiWrapper class.
     *
     * @return Mockery\MockInterface
     */
    protected function createMySQLiWrapperMock(): Mockery\MockInterface
    {
        // Create the aliased mock
        $myMock = Mockery::mock('alias:' . \App\services\database\MySQLiWrapper::class);

        // Default behaviors across all tests
        $myMock->shouldReceive('close')->andReturnTrue();
        $myMock->shouldReceive('begin_transaction')->andReturnTrue();
        $myMock->shouldReceive('commit')->andReturnTrue();
        $myMock->shouldReceive('rollback')->andReturnTrue();

        // Default behaviors unless specifically overridden in a given test
        $myMock->shouldReceive('getConnectError')->andReturnNull()->byDefault();

        return $myMock;
    }
}
