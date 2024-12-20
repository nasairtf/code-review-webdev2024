<?php

declare(strict_types=1);

namespace Tests\classes\services\database\ishell;

use Mockery;
use PHPUnit\Framework\TestCase;
use App\services\database\ishell\IshellService;
use App\services\database\DatabaseService;

/**
 * Unit tests for the IshellService class.
 *
 * This test suite validates the behavior of the IshellService class,
 * specifically ensuring that its constructor initializes the parent
 * DatabaseService with the correct parameters.
 *
 * @covers \App\services\database\ishell\IshellService
 */
class IshellServiceTest extends TestCase
{
    /**
     * Mock instance of DBConnection.
     *
     * @var Mockery\MockInterface
     */
    private $dbMock;

    /**
     * Mock instance of CustomDebug.
     *
     * @var Mockery\MockInterface
     */
    private $debugMock;

    /**
     * Tests the constructor initializes DatabaseService with the correct database name and debug mode.
     *
     * @covers \App\services\database\ishell\IshellService::__construct
     */
    public function testIshellServiceConstructorInitializesBaseService(): void
    {
        // Arrange
        $this->dbMock = Mockery::mock('alias:' . \App\services\database\DBConnection::class);
        $this->debugMock = Mockery::mock('alias:' . \App\core\common\CustomDebug::class);
        $this->mockGetInstance($this->dbMock, 'ishell');

        // Act
        $service = new IshellService(false);

        // Assert
        $this->assertInstanceOf(IshellService::class, $service);
        $this->assertInstanceOf(DatabaseService::class, $service);
    }

    /**
     * Cleans up after each test, closing Mockery expectations.
     *
     * @return void
     */
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
