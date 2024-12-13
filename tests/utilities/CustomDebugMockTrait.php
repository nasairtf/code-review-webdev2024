<?php

declare(strict_types=1);

namespace Tests\utilities;

use Mockery;

/**
 * Trait for mocking the CustomDebug class in unit tests.
 *
 * NOTE: This trait is intended exclusively for use in test classes and
 * should never be used in production code.
 */
trait CustomDebugMockTrait
{
    /**
     * Creates a mock of the CustomDebug class with predefined behavior.
     *
     * @param string|null $context      The context for the CustomDebug instance (e.g., 'database').
     * @param bool        $debugMode    Whether debug mode is enabled.
     * @param int         $debugLevel   The debug level.
     * @param string      $defaultColor The default color for debug messages.
     * @return Mockery\MockInterface    The mocked CustomDebug instance.
     */
    protected function createCustomDebugMock(
        ?string $context = null,
        bool $debugMode = false,
        int $debugLevel = 0,
        string $defaultColor = 'green'
    ): Mockery\MockInterface {
        $debugMock = Mockery::mock('alias:' . \App\core\common\CustomDebug::class);

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
     * Sets up a specific exception throw for failXXX methods.
     *
     * @param Mockery\MockInterface $debugMock The CustomDebug mock instance.
     * @param string                $method    The fail method to mock (e.g., 'failDatabase').
     * @param string                $message   The expected message for the exception.
     * @param \Exception|null       $exception The exception to throw (defaults to Exception).
     *
     * @return void
     */
    protected function mockDebugFail(
        Mockery\MockInterface $debugMock,
        string $method,
        string $message,
        ?\Exception $exception = null
    ): void {
        $exception = $exception ?? new \Exception($message); // Default exception type
        $debugMock->shouldReceive($method)
            ->with($message, Mockery::any(), Mockery::any())
            ->andThrow($exception);
    }
}
