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
        // Create the aliased mock
        $myMock = Mockery::mock('alias:' . \App\core\common\Debug::class);

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

        // Mock log() method; use custom mock for including color
        $myMock->shouldReceive('log')
            ->with(Mockery::any())
            ->andReturnNull();

        // Mock debug() method; use custom mock for including color
        $myMock->shouldReceive('debug')
            ->with(Mockery::any())
            ->andReturnNull();

        // Mock debugVariable() method; use custom mock for including color
        $myMock->shouldReceive('debugVariable')
            ->with(Mockery::any(), Mockery::any())
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
     * @param Mockery\MockInterface $myMock The Debug mock instance.
     * @param string                $message   The expected debug message.
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
     * Sets up a specific exception throw for fail method.
     *
     * @param Mockery\MockInterface $myMock The Debug mock instance.
     * @param string                $message   The expected message for the exception.
     *
     * @return void
     */
    protected function mockFail(
        Mockery\MockInterface $myMock,
        string $message
    ): void {
        // Mock fail() to mimic real behavior
        $myMock->shouldReceive('fail')
            ->with($message) // this will break if fail() has more than 1 argument passed in
            ->andThrow(new \Exception($message));
    }
}
