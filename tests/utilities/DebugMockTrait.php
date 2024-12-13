<?php

declare(strict_types=1);

namespace Tests\utilities;

use Mockery;

/**
 * Trait for mocking the Debug class in unit tests.
 *
 * NOTE: This trait is intended exclusively for use in test classes and
 * should never be used in production code.
 */
trait DebugMockTrait
{
    /**
     * Creates a mock of the Debug class with predefined behavior.
     *
     * @param string|null $context      The context for the CustomDebug instance (e.g., 'database').
     * @param bool        $debugMode    Whether debug mode is enabled.
     * @param int         $debugLevel   The debug level.
     * @param string      $defaultColor The default color for debug messages.
     * @return Mockery\MockInterface    The mocked Debug instance.
     */
    protected function createDebugMock(
        ?string $context = null,
        bool $debugMode = false,
        int $debugLevel = 0,
        string $defaultColor = 'green'
    ): Mockery\MockInterface {
        $debugMock = Mockery::mock('alias:' . \App\core\common\Debug::class);

        // Mock the constructor behavior
        $debugMock->shouldReceive('new')
            ->with($context, $debugMode, $debugLevel)
            ->andReturnSelf();

        // Mock property getters
        $debugMock->shouldReceive('isDebugMode')
            ->andReturn($debugMode);

        $debugMock->shouldReceive('getDebugLevel')
            ->andReturn($debugLevel);

        $debugMock->shouldReceive('getDefaultColor')
            ->andReturn($defaultColor);

        // Mock logging and debugging methods
        $debugMock->shouldReceive('log')
            ->with(Mockery::any(), Mockery::any())
            ->andReturnNull();

        $debugMock->shouldReceive('debug')
            ->with(Mockery::any(), Mockery::any())
            ->andReturnNull();

        $debugMock->shouldReceive('debugVariable')
            ->with(Mockery::any(), Mockery::any(), Mockery::any())
            ->andReturnNull();

        // Mock debugHeading to mimic real behavior
        $debugMock->shouldReceive('debugHeading')
            ->with(Mockery::any(), Mockery::any())
            ->andReturnUsing(function ($classLabel, $methodName) {
                return "{$classLabel}: {$methodName}()";
            });

        return $debugMock;
    }

    /**
     * Sets up a specific exception throw for fail method.
     *
     * @param Mockery\MockInterface $debugMock The Debug mock instance.
     * @param string                $message   The expected message for the exception.
     *
     * @return void
     */
    protected function mockDebugFail(
        Mockery\MockInterface $debugMock,
        string $message
    ): void {
        $debugMock->shouldReceive('fail')
            ->with($message, Mockery::any(), Mockery::any())
            ->andThrow(new \Exception($message));
    }
}
