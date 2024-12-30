<?php

declare(strict_types=1);

namespace Tests\utilities\mocks;

use Mockery;

/**
 * Trait for mocking the Debug and CustomDebug classes in unit tests.
 *
 * This trait simplifies the process of mocking the `Debug` and `CustomDebug` classes
 * by dynamically generating mocks for these classes and their shared behaviors.
 * It provides methods to handle both general debugging operations and specific
 * failure scenarios using the `fail` and `failXXX` methods. By consolidating
 * shared mocking logic, this trait ensures consistency and reduces test boilerplate.
 *
 * Features:
 * - Dynamic mock generation for both `Debug` and `CustomDebug` classes.
 * - Predefined behaviors for methods like `debug`, `debugVariable`, and `debugHeading`.
 * - Support for mocking `failXXX` methods with automatic exception handling.
 * - Flexible configuration for multiple failure scenarios through batch methods.
 *
 * Usage:
 * - Mock a specific Debug class, such as `Debug` or `CustomDebug`.
 * - Define expected method calls, results, or exception behavior.
 * - Validate interactions with the mocked instance in your tests.
 *
 * Example usage:
 * ```
 * // Create a Debug mock with specific properties
 * $debugMock = $this->createDebugMock('database', true, 1, 'blue');
 *
 * // Configure a specific failure scenario
 * $this->mockFail($debugMock, 'Connection failed.', 'failDatabase');
 *
 * // Batch configure multiple failure scenarios
 * $this->mockMultipleFails($debugMock, [
 *     ['message' => 'Database error.', 'method' => 'failDatabase'],
 *     ['message' => 'Invalid email.', 'method' => 'failEmail'],
 * ]);
 * ```
 *
 * NOTE: This trait is intended exclusively for use in test classes and
 * should never be used in production code.
 */
trait MockDebugTrait
{
    /**
     * Creates a mock for the specified Debug class (e.g., Debug or CustomDebug) with
     * predefined behavior.
     *
     * This method dynamically handles the mocking of both base `Debug` and
     * extended `CustomDebug` classes. It provides default behaviors for methods like
     * `debug`, `debugVariable`, and `fail` while allowing customization through parameters.
     *
     * @param string      $className    Fully qualified class name to mock (e.g., Debug or CustomDebug).
     * @param string|null $context      The context for the instance (e.g., 'database').
     * @param bool        $debugMode    Whether debug mode is enabled.
     * @param int         $debugLevel   The debug level (e.g., verbosity level).
     * @param string      $defaultColor The default color for debug messages (e.g., 'green').
     *
     * @return Mockery\MockInterface    The mocked Debug instance.
     *
     * Example usage:
     * ```
     * $debugMock = $this->createDebugMockForClass(
     *     \App\core\common\Debug::class,
     *     'database',
     *     true,
     *     1,
     *     'blue'
     * );
     * ```
     */
    protected function createDebugMockForClass(
        string $className,
        ?string $context = null,
        bool $debugMode = false,
        int $debugLevel = 0,
        string $defaultColor = 'green'
    ): Mockery\MockInterface {
        // Create the aliased mock
        $myMock = Mockery::mock('alias:' . $className);

        // Mock the constructor behavior
        $myMock->shouldReceive('new')
            ->with($context, $debugMode, $debugLevel)
            ->andReturnSelf();

        // Mock property getters
        $myMock->shouldReceive('isDebugMode')->andReturn($debugMode);
        $myMock->shouldReceive('getDebugLevel')->andReturn($debugLevel);
        $myMock->shouldReceive('getDefaultColor')->andReturn($defaultColor);

        // Mock log() method; use a custom method mock for including color
        $myMock->shouldReceive('log')->with(Mockery::any())->andReturnNull();

        // Mock debug() method; use a custom method mock for including color
        $myMock->shouldReceive('debug')->with(Mockery::any())->andReturnNull();

        // Mock debugVariable() method; use a custom method mock for including color
        $myMock->shouldReceive('debugVariable')->with(Mockery::any(), Mockery::any())->andReturnNull();

        // Mock debugHeading() to mimic real behavior
        $myMock->shouldReceive('debugHeading')
            ->with(Mockery::any(), Mockery::any())
            ->andReturnUsing(function ($classLabel, $methodName) {
                return "{$classLabel}: {$methodName}()";
            });

        return $myMock;
    }

    /**
     * Convenience method for creating a mock of the base Debug class.
     *
     * This is a wrapper around `createDebugMockForClass` to simplify mock creation
     * when working with the base Debug class.
     *
     * @param string|null $context      The context for the Debug instance (e.g., 'database').
     * @param bool        $debugMode    Whether debug mode is enabled.
     * @param int         $debugLevel   The debug level.
     * @param string      $defaultColor The default color for debug messages.
     *
     * @return Mockery\MockInterface    The mocked Debug instance.
     *
     * Example usage:
     * ```
     * $debugMock = $this->createDebugMock('email', false, 1, 'yellow');
     * ```
     */
    protected function createDebugMock(
        ?string $context = null,
        bool $debugMode = false,
        int $debugLevel = 0,
        string $defaultColor = 'green'
    ): Mockery\MockInterface {
        return $this->createDebugMockForClass(
            \App\core\common\Debug::class,
            $context,
            $debugMode,
            $debugLevel,
            $defaultColor
        );
    }

    /**
     * Convenience method for creating a mock of the CustomDebug class.
     *
     * This is a wrapper around `createDebugMockForClass` to simplify mock creation
     * when working with the extended CustomDebug class.
     *
     * @param string|null $context      The context for the CustomDebug instance (e.g., 'database').
     * @param bool        $debugMode    Whether debug mode is enabled.
     * @param int         $debugLevel   The debug level.
     * @param string      $defaultColor The default color for debug messages.
     *
     * @return Mockery\MockInterface    The mocked CustomDebug instance.
     *
     * Example usage:
     * ```
     * $customDebugMock = $this->createCustomDebugMock('database', true, 2, 'red');
     * ```
     */
    protected function createCustomDebugMock(
        ?string $context = null,
        bool $debugMode = false,
        int $debugLevel = 0,
        string $defaultColor = 'green'
    ): Mockery\MockInterface {
        return $this->createDebugMockForClass(
            \App\core\common\CustomDebug::class,
            $context,
            $debugMode,
            $debugLevel,
            $defaultColor
        );
    }

    /**
     * Sets up a specific expectation for the `debug` method.
     *
     * This method configures the mock instance to expect the `debug` method to be called
     * with a specific message during the test. It simplifies verifying that the
     * `debug` method is used correctly in the code being tested.
     *
     * @param Mockery\MockInterface $myMock  The Debug or CustomDebug mock instance.
     * @param string                $message The expected debug message.
     *
     * @return void
     *
     * Example usage:
     * ```
     * $this->mockDebug($debugMock, 'This is a debug message.');
     * ```
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
     * Sets up a specific exception throw for `fail` or `failXXX` methods.
     *
     * This method configures the mock instance to throw a predefined exception
     * when the specified `fail` or `failXXX` method is called with a given message.
     * This is useful for simulating failure scenarios in the code under test.
     *
     * @param Mockery\MockInterface $myMock    The Debug or CustomDebug mock instance.
     * @param string                $message   The expected failure message.
     * @param string                $method    The fail method to mock (e.g., 'fail', 'failDatabase').
     * @param \Exception|null       $exception The exception to throw. Defaults to a generic Exception.
     *
     * @return void
     *
     * Example usage:
     * ```
     * $this->mockFail($debugMock, 'Database connection failed.', 'failDatabase');
     * ```
     */
    protected function mockFail(
        Mockery\MockInterface $myMock,
        string $message,
        string $method = 'fail',
        ?\Exception $exception = null
    ): void {
        // Map fail methods to their corresponding exception classes
        $methodExceptionMap = $this->getMethodExceptionMap();

        $exception = $exception ?? ( // $exception allows custom exception
            isset($methodExceptionMap[$method])
                ? new $methodExceptionMap[$method]($message)
                : new \Exception($message) // Default to generic exception
        );

        // Mock fail()/failXXX() to mimic real behavior
        $myMock->shouldReceive($method)
            ->with($message) // this will break if fail()/failXXX() has more than 1 argument passed in
            ->andThrow($exception);
    }

    /**
     * Sets up multiple `fail()`/`failXXX()` method mocks for the provided mock instance.
     *
     * This method simplifies the setup of multiple failure scenarios for tests, allowing
     * you to configure multiple fail methods and their corresponding exceptions in a single call.
     * It uses a predefined mapping of `failXXX` methods to exception classes to reduce boilerplate.
     *
     * @param Mockery\MockInterface $myMock   The mock instance to configure failXXX methods for.
     * @param array                 $failures An array of failure configurations, each with:
     *                                        - `message` (string): The expected failure message.
     *                                        - `method` (string, optional): The failXXX method name (default: 'fail').
     *                                        - `exception` (\Exception, optional): The exception to throw. If omitted,
     *                                          it will be inferred based on the method using a predefined map.
     *
     * @return void
     *
     * Example usage:
     * ```
     * $this->mockMultipleFails($debugMock, [
     *     ['message' => 'Database error.', 'method' => 'failDatabase'],
     *     ['message' => 'Invalid email address.', 'method' => 'failEmail'],
     *     ['message' => 'Custom error.', 'method' => 'failCustom', 'exception' => new CustomException('Custom error')],
     * ]);
     * ```
     */
    protected function mockMultipleFails(
        Mockery\MockInterface $myMock,
        array $failures
    ): void {
        // Map fail methods to their corresponding exception classes
        $methodExceptionMap = $this->getMethodExceptionMap();

        foreach ($failures as $failure) {
            $message = $failure['message'];
            $method = $failure['method'] ?? 'fail';
            $exception = $failure['exception'] ?? ( // $failure['exception'] allows custom exception
                isset($methodExceptionMap[$method])
                    ? new $methodExceptionMap[$method]($message)
                    : new \Exception($message) // Default to generic exception
            );

            // Mock fail()/failXXX() to mimic real behavior
            $myMock->shouldReceive($method)
                ->with($message)
                ->andThrow($exception);
        }
    }

    /**
     * Provides a mapping of fail()/failXXX() methods to their corresponding exception classes.
     *
     * This method centralizes the mapping logic to ensure consistency and ease of maintenance.
     * It can be overridden in child classes or extended as needed.
     *
     * @return array<string, string> Mapping of method names to exception class names.
     *
     * Example usage:
     * ```
     * $map = $this->getMethodExceptionMap();
     * ```
     */
    protected function getMethodExceptionMap(): array
    {
        // Map fail()/failXXX() methods to their corresponding exception classes
        return [
            'fail' => \Exception::class,
            'failDatabase' => \App\exceptions\DatabaseException::class,
            'failEmail' => \App\exceptions\EmailException::class,
            'failValidation' => \App\exceptions\ValidationException::class,
            // Add more mappings as needed
        ];
    }
}
