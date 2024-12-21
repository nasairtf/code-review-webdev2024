<?php

declare(strict_types=1);

namespace Tests\classes\services\database\troublelog;

use Mockery;
use PHPUnit\Framework\TestCase;
use Tests\utilities\CustomDebugMockTrait;
use Tests\utilities\DBConnectionMockTrait;
use App\services\database\troublelog\TroublelogService;
use App\services\database\DatabaseService;

/**
 * Unit tests for the TroublelogService class.
 *
 * This test suite validates the behavior of the TroublelogService class,
 * specifically ensuring that its constructor initializes the parent
 * DatabaseService with the correct parameters.
 *
 * @covers \App\services\database\troublelog\TroublelogService
 */
class TroublelogServiceTest extends TestCase
{
    use CustomDebugMockTrait;
    use DBConnectionMockTrait;

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
     * @covers \App\services\database\troublelog\TroublelogService::__construct
     */
    public function testTroublelogServiceConstructorInitializesBaseService(): void
    {
        // Arrange
        $this->debugMock = $this->createCustomDebugMock();
        $this->dbMock = $this->createDBConnectionMock();
        $this->mockGetInstance($this->dbMock, 'troublelog');

        // Act
        $service = new TroublelogService(false);

        // Assert
        $this->assertInstanceOf(TroublelogService::class, $service);
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
