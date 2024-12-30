<?php

declare(strict_types=1);

namespace Tests\utilities\helpers;

use Mockery;

/**
 * Trait for standardising the setUp() method in unit tests.
 *
 * This trait simplifies the setup process for unit tests by detecting and
 * configuring mock objects dynamically based on the test class's context.
 * It also ensures that the required properties are declared in the test class,
 * throwing exceptions if they are not found.
 *
 * Features:
 * - Dynamically invokes parent `setUp()` logic if present.
 * - Configures `CustomDebug` and `DBConnection` mocks if methods and properties are available.
 * - Provides utility for setting up `DatabaseService`-derived partial mocks.
 *
 * Example usage in a test class:
 * ```php
 * use Tests\utilities\helpers\UnitTestSetupTrait;
 *
 * class MyTest extends TestCase
 * {
 *     use UnitTestSetupTrait;
 *
 *     protected $debugMock;
 *     protected $dbMock;
 *     protected $srvMock;
 *
 *     protected function setUp(): void
 *     {
 *         $this->setUpForStandardTests();
 *         $this->setUpForDatabaseServiceTests(
 *             \App\services\database\MyService::class,
 *             [false, $this->dbMock, $this->debugMock],
 *             ['fetchDataWithQuery']
 *         );
 *     }
 * }
 * ```
 *
 * NOTE: This trait is intended exclusively for use in test classes and
 * should never be used in production code.
 */
trait UnitTestSetupTrait
{
    /**
     * The private/protected properties used in the methods below are deliberately
     * not declared in this trait. Should the class using this trait include the additional
     * traits, the methods will be detected via the `method_exists` verifications and
     * assigned to the relevant properties.
     */

    /**
     * Sets up standard test dependencies such as `CustomDebug` and `DBConnection` mocks.
     *
     * - If a parent `setUp()` method exists, it will be called first.
     * - Dynamically detects and configures the following:
     *   - `CustomDebug` mock, assigned to `$debugMock`.
     *   - `DBConnection` mock, assigned to `$dbMock`.
     *
     * Throws:
     * - `\LogicException` if the required properties (`debugMock` or `dbMock`) are not declared
     *   in the test class when the respective mock methods are present.
     *
     * @return void
     *
     * Example:
     * ```php
     * $this->setUpForStandardTests();
     * ```
     */
    protected function setUpForStandardTests(): void
    {
        // Check if the parent class has any setUp logic to run
        if (method_exists(parent::class, 'setUp')) {
            parent::setUp();
        }

        // Set up CustomDebug Mock if the property exists and the method is available
        if (method_exists($this, 'createCustomDebugMock')) {
            if (property_exists($this, 'debugMock')) {
                $this->debugMock = $this->createCustomDebugMock();
            } else {
                throw new \LogicException(
                    sprintf("Declare property 'debugMock' in %s to use 'createCustomDebugMock'.", static::class)
                );
            }
        }

        // Set up DBConnection Mock if possible
        if (method_exists($this, 'createDBConnectionMock')) {
            if (property_exists($this, 'dbMock')) {
                $this->dbMock = $this->createDBConnectionMock();
            } else {
                throw new \LogicException(
                    sprintf("Declare property 'dbMock' in %s to use 'createDBConnectionMock'.", static::class)
                );
            }
        }
    }

    /**
     * Sets up partial mocks for `DatabaseService`-derived classes.
     *
     * - Dynamically creates a partial mock for a `DatabaseService`-derived class.
     * - Assigns the mock to `$srvMock` if the property is declared.
     *
     * Throws:
     * - `\LogicException` if the required property `srvMock` is not declared in the test class.
     *
     * @param string $className       Fully qualified class name of the service to mock.
     *                                Example: `\App\services\database\MyService::class`
     * @param array  $constructorArgs Constructor arguments for the service being mocked.
     *                                Example: `[false, $this->dbMock, $this->debugMock]`
     * @param array  $methodList      List of methods to mock on the service.
     *                                Example: `['fetchDataWithQuery']`
     *
     * @return void
     *
     * Example:
     * ```php
     * $this->setUpForDatabaseServiceTests(
     *     \App\services\database\MyService::class,
     *     [false, $this->dbMock, $this->debugMock],
     *     ['fetchDataWithQuery']
     * );
     * ```
     */
    protected function setUpForDatabaseServiceTests(
        string $className,
        array $constructorArgs,
        array $methodList
    ): void {
        // Set up partial DatabaseService-derived Mock
        if (method_exists($this, 'createPartialDatabaseServiceMock')) {
            if (property_exists($this, 'srvMock')) {
                $this->srvMock = $this->createPartialDatabaseServiceMock(
                    $className,       // e.g. DailyInstrumentService::class
                    $constructorArgs, // e.g. [false, $this->dbMock, $this->debugMock],
                    $methodList       // e.g. ['executeUpdateQuery']
                );
            } else {
                throw new \LogicException(
                    sprintf("Declare 'srvMock' in %s to use 'createPartialDatabaseServiceMock'.", static::class)
                );
            }
        }
    }
}
