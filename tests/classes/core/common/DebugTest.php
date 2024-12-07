<?php

namespace Tests\classes\core\common;

use PHPUnit\Framework\TestCase;
use Mockery as Mockery;
use App\core\common\Debug;
use App\exceptions\DatabaseException;
use App\exceptions\EmailException;
use App\exceptions\ValidationException;

/**
 * Unit tests for the Debug class.
 */
class DebugTest extends TestCase
{
    /**
     * Prepare common mock configurations before each test.
     *
     * @return void
     */
    protected function setUp(): void
    {
        // Ensure the parent setup runs if needed
        parent::setUp();

        // Mock the Config class for all tests
        $mockConfig = Mockery::mock('alias:' . \App\core\common\Config::class);
        $mockConfig->shouldReceive('get')
            ->with('debug_config', 'colors')
            ->andReturn([
                'default' => 'green',
                'database' => 'red',
            ]);
    }

    /**
     * Clean up Mockery expectations after each test.
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

    /**
     * Test that the Debug constructor initializes properties correctly.
     *
     * This test verifies that the constructor sets the defaultColor,
     * debugMode, and debugLevel properties based on the given context
     * and configuration.
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
     * Test default behavior when no context or configuration is provided.
     *
     * @return void
     */
    public function testConstructorUsesDefaultValues(): void
    {
        // Override the default mock behavior
        $mockConfig = Mockery::mock('alias:' . \App\core\common\Config::class);
        $mockConfig->shouldReceive('get')
            ->with('debug_config', 'colors')
            ->andReturn([]); // Override: return an empty array

        // Instantiate Debug without parameters to test default values
        $debug = new Debug();

        // Assertions for default behavior
        $this->assertFalse($debug->isDebugMode(), 'Debug mode should default to false.');
        $this->assertEquals(0, $debug->getDebugLevel(), 'Debug level should default to 0.');
        $this->assertEquals('green', $debug->getDefaultColor(), 'Default color should be green.');
    }

    /**
     * Test that the fail method logs a message and throws a general exception.
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
     * Test that the failValidation method logs a message and throws a ValidationException.
     *
     * @return void
     */
    public function testFailValidationThrowsValidationException(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Validation Error');

        $debug = new Debug(null, true);
        $debug->failValidation('Log Message', 'Validation Error', 'blue');
    }

    /**
     * Test that the failDatabase method logs a message and throws a DatabaseException.
     *
     * @return void
     */
    public function testFailDatabaseThrowsDatabaseException(): void
    {
        $this->expectException(DatabaseException::class);
        $this->expectExceptionMessage('Database Error');

        $debug = new Debug(null, true);
        $debug->failDatabase('Log Message', 'Database Error', 'blue');
    }

    /**
     * Test that the failEmail method logs a message and throws an EmailException.
     *
     * @return void
     */
    public function testFailEmailThrowsEmailException(): void
    {
        $this->expectException(EmailException::class);
        $this->expectExceptionMessage('Email Error');

        $debug = new Debug(null, true);
        $debug->failEmail('Log Message', 'Email Error', 'blue');
    }

    /**
     * Test that the debug method outputs messages when debug mode is enabled.
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
     * Test that the debug method does not output messages when debug mode is disabled.
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
     * Test that debugVariable outputs variable information when debug mode is enabled.
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
     * Test that log method writes to the error log when debugLevel > 0.
     *
     * @return void
     */
    //public function testLogWritesToErrorLogWhenDebugLevelIsPositive(): void
    //{
    //    $this->expectOutputRegex('/DEBUG: Log Message/');

    //    $debug = new Debug(null, true, 1);
    //    $debug->log('Log Message', 'blue');
    //}
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
     * Test that the debugHeading method generates a formatted heading.
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
     * Test that debugHeading handles unknown classes gracefully.
     *
     * @return void
     */
    public function testDebugHeadingHandlesUnknownClasses(): void
    {
        $debug = new Debug(null, true);

        $result = $debug->debugHeading('Unknown', 'testMethod');
        $this->assertStringContainsString('Unknown: testMethod()', $result);
    }
}
