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
        // Create the aliased mock
        $myMock = Mockery::mock('alias:' . \App\core\common\CustomDebug::class);

        // Mock the constructor behavior
        $myMock->shouldReceive('new')
            ->with($context, $debugMode, $debugLevel)
            ->andReturnSelf();

        // Mock property getters
        $myMock->shouldReceive('isDebugMode')
            ->andReturn($debugMode);

        $myMock->shouldReceive('getDebugLevel')
            ->andReturn($debugLevel);

        $myMock->shouldReceive('getDefaultColor')
            ->andReturn($defaultColor);

        // Mock log(), debug(), and debugHeading() methods
        $myMock->shouldReceive('log')
            ->with(Mockery::any(), Mockery::any())
            ->andReturnNull();

        $myMock->shouldReceive('debug')
            ->with(Mockery::any(), Mockery::any())
            ->andReturnNull();

        $myMock->shouldReceive('debugVariable')
            ->with(Mockery::any(), Mockery::any(), Mockery::any())
            ->andReturnNull();

        // Mock debugHeading() to mimic real behavior
        $myMock->shouldReceive('debugHeading')
            ->with(Mockery::any(), Mockery::any())
            ->andReturnUsing(function ($classLabel, $methodName) {
                return "{$classLabel}: {$methodName}()";
            });

        return $myMock;
    }

    /**
     * Sets up a specific expectation for the debug method.
     *
     * @param Mockery\MockInterface $myMock  The Debug mock instance.
     * @param string                $message The expected debug message.
     *
     * @return void
     */
    protected function mockDebug(
        Mockery\MockInterface $myMock,
        string $message
    ): void {
        // Mock debug() to mimic real behavior
        $myMock->shouldReceive('debug')
            ->with($message) // this will break if debug() has more than 1 argument passed in
            ->andReturnNull();
    }

    /**
     * Sets up a specific exception throw for failXXX methods.
     *
     * @param Mockery\MockInterface $myMock    The CustomDebug mock instance.
     * @param string                $method    The fail method to mock (e.g., 'failDatabase').
     * @param string                $message   The expected message for the exception.
     * @param \Exception|null       $exception The exception to throw (defaults to Exception).
     *
     * @return void
     */
    protected function mockFail(
        Mockery\MockInterface $myMock,
        string $method,
        string $message,
        ?\Exception $exception = null
    ): void {
        // Mock failXXX() to mimic real behavior
        $exception = $exception ?? new \Exception($message); // Default exception type
        $myMock->shouldReceive($method)
            ->with($message) // this will break if failXXX() has more than 1 argument passed in
            ->andThrow($exception);
    }
}
