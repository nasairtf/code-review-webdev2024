<?php

declare(strict_types=1);

namespace Tests\classes\core\common;

use Mockery;
use PHPUnit\Framework\TestCase;
use Tests\utilities\helpers\UnitTestTeardownTrait;
use Tests\utilities\mocks\MockConfigTrait;
use App\core\common\CustomDebug;
use App\exceptions\DatabaseException;
use App\exceptions\EmailException;
use App\exceptions\ValidationException;

/**
 * Unit tests for the CustomDebug class.
 *
 * This test suite validates the behavior of the CustomDebug class, focusing
 * on its ability to log messages and throw domain-specific exceptions. Mockery
 * is used to mock dependencies, ensuring isolated and reliable tests.
 *
 * @covers \App\core\common\CustomDebug
 */
class CustomDebugTest extends TestCase
{
    use UnitTestTeardownTrait;
    use MockConfigTrait;

    /**
     * Validates that the failValidation method logs a message and throws a ValidationException.
     *
     * @covers \App\core\common\CustomDebug::failValidation
     *
     * @return void
     */
    public function testFailValidationThrowsValidationException(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Validation Error');

        $debug = new CustomDebug('validation', true);

        // Explicitly override the color to 'blue', which should take precedence over 'pink' in the config
        $debug->failValidation('Log (not pink) Validation Message', 'Validation Error', 'blue');
    }

    /**
     * Validates that the failDatabase method logs a message and throws a DatabaseException.
     *
     * @covers \App\core\common\CustomDebug::failDatabase
     *
     * @return void
     */
    public function testFailDatabaseThrowsDatabaseException(): void
    {
        $this->expectException(DatabaseException::class);
        $this->expectExceptionMessage('Database Error');

        $debug = new CustomDebug('database', true);

        // Explicitly override the color to 'red', which should take precedence over 'yellow' in the config
        $debug->failDatabase('Log (not yellow) Database Message', 'Database Error', 'red');
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

        $debug = new CustomDebug('email', true);

        // Explicitly override the color to 'orange', which should take precedence over 'purple' in the config
        $debug->failEmail('Log (not purple) Email Message', 'Email Error', 'orange');
    }

    /**
     * Sets up the test environment before each test.
     *
     * - Configures Mockery expectations for the Config class.
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
                    'database' => 'yellow',
                    'email' => 'purple',
                    'validation' => 'pink',
                ],
            ],
        ];
        $this->createConfigMock($configData);
    }
}
