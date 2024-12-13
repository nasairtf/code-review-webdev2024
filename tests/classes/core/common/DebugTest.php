<?php

declare(strict_types=1);

namespace Tests\classes\core\common;

use Mockery;
use PHPUnit\Framework\TestCase;
use Tests\utilities\ConfigMockTrait;
use App\core\common\Debug;

/**
 * Unit tests for the Debug class.
 *
 * This test suite verifies the behavior of the Debug class, including debugging
 * output, error logging, and exception handling. Mockery is used to mock dependencies
 * such as the Config class to provide controlled test scenarios.
 *
 * @covers \App\core\common\Debug
 */
class DebugTest extends TestCase
{
    use ConfigMockTrait;

    /**
     * Test that the Debug constructor initializes properties correctly.
     *
     * This test verifies that the constructor sets the defaultColor,
     * debugMode, and debugLevel properties based on the given context
     * and configuration.
     *
     * @covers \App\core\common\Debug::__construct
     *
     * @return void
     */
    public function testConstructorInitializesPropertiesCorrectly(): void
    {
        // Instantiate Debug with a specific context and settings
        $debug = new Debug('database', true, 1);

        // Use assertions to verify that the properties are initialized correctly
        $this->assertTrue($debug->isDebugMode(), 'Debug mode should be enabled.');
        $this->assertEquals(1, $debug->getDebugLevel(), 'Debug level should be 1.');
        $this->assertEquals('red', $debug->getDefaultColor(), 'Default color should be red.');
    }

    /**
     * Validates the default behavior of the Debug constructor.
     *
     * @covers \App\core\common\Debug::__construct
     *
     * @return void
     */
    public function testConstructorUsesDefaultValues(): void
    {
        // Override the default mock behavior
        $configData = [
            'debug_config' => [
                'colors' => [], // Override: return an empty array
            ],
        ];
        $this->createConfigMock($configData);

        // Instantiate Debug without parameters to test default values
        $debug = new Debug();

        // Assertions for default behavior
        $this->assertFalse($debug->isDebugMode(), 'Debug mode should default to false.');
        $this->assertEquals(0, $debug->getDebugLevel(), 'Debug level should default to 0.');
        $this->assertEquals('green', $debug->getDefaultColor(), 'Default color should be green.');
    }

    /**
     * Validates that the fail method logs a message and throws a general exception.
     *
     * @covers \App\core\common\Debug::fail
     *
     * @return void
     */
    public function testFailThrowsGeneralException(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Test Failure');

        $debug = new Debug(null, true);
        $debug->fail('Log Message', 'Test Failure', 'blue');
    }

    /**
     * Validates debug output when debug mode is enabled.
     *
     * @covers \App\core\common\Debug::debug
     *
     * @return void
     */
    public function testDebugOutputsMessageWhenEnabled(): void
    {
        $this->expectOutputString("<p style='color: green;'>DEBUG: Test Debug Message</p>\n");

        $debug = new Debug(null, true);
        $debug->debug('Test Debug Message');
    }

    /**
     * Validates that debug output is suppressed when debug mode is disabled.
     *
     * @covers \App\core\common\Debug::debug
     *
     * @return void
     */
    public function testDebugDoesNotOutputMessageWhenDisabled(): void
    {
        $this->expectOutputString('');

        $debug = new Debug(null, false);
        $debug->debug('Test Debug Message');
    }

    /**
     * Validates debugVariable output when debug mode is enabled.
     *
     * @covers \App\core\common\Debug::debugVariable
     *
     * @return void
     */
    public function testDebugVariableOutputsWhenEnabled(): void
    {
        $this->expectOutputRegex('/DEBUG \(Test Variable\): Array/');

        $debug = new Debug(null, true);
        $debug->debugVariable(['key' => 'value'], 'Test Variable');
    }

    /**
     * Validates that the log method writes messages to the error log when debugLevel > 0.
     *
     * @covers \App\core\common\Debug::log
     *
     * @return void
     */
    public function testLogWritesToErrorLogWhenDebugLevelIsPositive(): void
    {
        $logFile = '/tmp/phpunit_error_log'; // Temporary file for the error log
        ini_set('error_log', $logFile); // Redirect PHP error log to the file

        $debug = new Debug(null, true, 1);
        $debug->log('Log Message', 'blue');

        $logContent = file_get_contents($logFile); // Read the error log
        unlink($logFile); // Clean up temporary file

        $this->assertStringContainsString(
            'Log Message',
            $logContent,
            'Log Message should appear in error log.'
        );
    }

    /**
     * Validates that debugHeading generates a formatted heading.
     *
     * @covers \App\core\common\Debug::debugHeading
     *
     * @return void
     */
    public function testDebugHeadingGeneratesFormattedHeading(): void
    {
        $debug = new Debug(null, true);

        $result = $debug->debugHeading('Controller', 'index');
        $this->assertStringContainsString('Controller: index()', $result);
    }

    /**
     * Validates that debugHeading handles unknown classes gracefully.
     *
     * @covers \App\core\common\Debug::debugHeading
     *
     * @return void
     */
    public function testDebugHeadingHandlesUnknownClasses(): void
    {
        $debug = new Debug(null, true);

        $result = $debug->debugHeading('Unknown', 'testMethod');
        $this->assertStringContainsString('Unknown: testMethod()', $result);
    }

    /**
     * Sets up the test environment before each test.
     *
     * - Configures Mockery expectations for the Config class to provide debugging colors.
     * - Ensures necessary parent setup logic is executed.
     *
     * @return void
     */
    protected function setUp(): void
    {
        // Ensure the parent setup runs if needed
        parent::setUp();

        // Mock the Config class for all tests
        $configData = [
            'debug_config' => [
                'colors' => [
                    'default' => 'green',
                    'database' => 'red',
                ],
            ],
        ];
        $this->createConfigMock($configData);
    }

    /**
     * Cleans up the test environment after each test.
     *
     * - Verifies and closes Mockery expectations.
     * - Ensures necessary parent teardown logic is executed.
     *
     * @return void
     */
    protected function tearDown(): void
    {
        // Ensure Mockery is closed down
        Mockery::close();
        // Ensure PHPUnit's tearDown logic runs too
        parent::tearDown();
    }
}
