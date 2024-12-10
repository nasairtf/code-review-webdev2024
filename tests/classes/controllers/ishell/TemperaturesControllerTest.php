<?php

declare(strict_types=1);

namespace Tests\controllers\ishell;

use App\controllers\ishell\TemperaturesController;
use App\models\ishell\TemperaturesModel;
use App\views\ishell\TemperaturesView;
use App\validators\ishell\TemperaturesValidator;
use App\core\common\Debug;
use App\services\graphs\GraphService;
use PHPUnit\Framework\TestCase;

class TemperaturesControllerTest extends TestCase
{
    private $debugMock;
    private $modelMock;
    private $viewMock;
    private $validatorMock;
    private $graphMock;
    private $controller;

    protected function setUp(): void
    {
        $this->debugMock = $this->createMock(Debug::class);
        $this->modelMock = $this->createMock(TemperaturesModel::class);
        $this->viewMock = $this->createMock(TemperaturesView::class);
        $this->validatorMock = $this->createMock(TemperaturesValidator::class);
        $this->graphMock = $this->createMock(GraphService::class);

        $this->controller = new TemperaturesController('monitor', $this->debugMock);

        // Override dependencies with mocks
        $reflection = new \ReflectionClass($this->controller);
        $this->setPrivateProperty($reflection, $this->controller, 'model', $this->modelMock);
        $this->setPrivateProperty($reflection, $this->controller, 'view', $this->viewMock);
        $this->setPrivateProperty($reflection, $this->controller, 'valid', $this->validatorMock);
        $this->setPrivateProperty($reflection, $this->controller, 'graph', $this->graphMock);
    }

    private function setPrivateProperty(\ReflectionClass $reflection, $instance, string $property, $value): void
    {
        $property = $reflection->getProperty($property);
        $property->setAccessible(true);
        $property->setValue($instance, $value);
    }

    public function testHandleRequestProcessesAndRendersData(): void
    {
        $this->modelMock->method('fetchTemperatureData')->willReturn([
            'cur' => ['channel1' => 300.123],
            'all' => ['channel1' => [['timestamp' => '2024-12-01 12:00:00', 'ktemp' => 300.123]]],
        ]);
        $this->validatorMock->method('validateTemperatureData')->willReturn([
            'times' => [[0]],
            'temps' => [[300.123]],
        ]);
        $this->graphMock->method('generateLinePlot2')->willReturn(true);
        $this->viewMock->method('renderPage')->willReturnCallback(function ($temps, $graphs) {
            echo 'Page Rendered';
        });

        $this->expectOutputString('Page Rendered');
        $this->controller->handleRequest();
    }
}
