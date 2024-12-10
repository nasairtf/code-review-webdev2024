<?php

declare(strict_types=1);

namespace Tests\models\ishell;

use App\models\ishell\TemperaturesModel;
use App\core\common\Debug;
use App\services\database\ishell\read\TemperaturesService;
use PHPUnit\Framework\TestCase;

class TemperaturesModelTest extends TestCase
{
    private $debugMock;
    private $dbReadMock;
    private $model;

    protected function setUp(): void
    {
        $this->debugMock = $this->createMock(Debug::class);
        $this->dbReadMock = $this->createMock(TemperaturesService::class);
        $this->model = new TemperaturesModel(
            [
                'controller' => 'monitor',
                'temps' => [
                    'monitor' => ['sensors' => [['id' => 'sensor1', 'timestamp' => 'last_day']]],
                ],
            ],
            $this->debugMock,
            $this->dbReadMock
        );
    }

    public function testFetchTemperatureDataReturnsStructuredData(): void
    {
        $this->dbReadMock->method('fetchTemperatureData')
            ->willReturn([['ktemp' => 300.123, 'timestamp' => '2024-12-01 12:00:00']]);

        $result = $this->model->fetchTemperatureData();

        $this->assertArrayHasKey('cur', $result);
        $this->assertArrayHasKey('all', $result);
    }
}
