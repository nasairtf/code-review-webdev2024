<?php

declare(strict_types=1);

namespace Tests\views\ishell;

use App\views\ishell\TemperaturesView;
use App\core\common\Debug;
use App\core\irtf\IrtfUtilities;
use PHPUnit\Framework\TestCase;

class TemperaturesViewTest extends TestCase
{
    private $debugMock;
    private $view;

    protected function setUp(): void
    {
        $this->debugMock = $this->createMock(Debug::class);
        $this->view = new TemperaturesView(
            [
                'controller' => 'monitor',
                'template' => ['name' => 'temperature_template.php'],
                'monitor' => ['title' => 'Monitor View', 'url' => '/monitor', 'head' => 'Header', 'cur' => 'Now', 'cols' => 2],
            ],
            $this->debugMock
        );
    }

    public function testRenderPage(): void
    {
        $temperatures = [
            'monitor' => [
                ['channel' => 'Sensor 1', 'temperature' => 300.123, 'timestamp' => '2024-12-01 12:00:00'],
                ['channel' => 'Sensor 2', 'temperature' => 302.456, 'timestamp' => '2024-12-01 13:00:00'],
            ]
        ];

        $images = [
            ['path' => '/images/graph1.jpg', 'alt' => 'Graph 1'],
            ['path' => '/images/graph2.jpg', 'alt' => 'Graph 2'],
        ];

        $this->expectOutputRegex('/Monitor View/'); // Matches the title
        $this->expectOutputRegex('/Sensor 1/');     // Matches sensor data
        $this->expectOutputRegex('/300.123/');      // Matches temperature
        $this->expectOutputRegex('/Graph 1/');      // Matches graph alt text

        $this->view->renderPage($temperatures, $images);
    }

    public function testPrepareTemperatureDataArrangesDataCorrectly(): void
    {
        $temperatures = [
            'monitor' => [
                ['channel' => 'Sensor 1', 'temperature' => 300.123, 'timestamp' => '2024-12-01 12:00:00'],
                ['channel' => 'Sensor 2', 'temperature' => 302.456, 'timestamp' => '2024-12-01 13:00:00'],
                ['channel' => 'Sensor 3', 'temperature' => 305.789, 'timestamp' => '2024-12-01 14:00:00'],
            ]
        ];

        $method = new \ReflectionMethod($this->view, 'prepareTemperatureData');
        $method->setAccessible(true);

        $result = $method->invoke($this->view, $temperatures, 2);

        $this->assertCount(2, $result); // Two columns
        $this->assertCount(2, $result[0]); // First row has 2 items
        $this->assertCount(1, $result[1]); // Second row has 1 item
    }

    public function testPrepareImageDataSanitizesImages(): void
    {
        $images = [
            ['path' => '/images/graph1.jpg', 'alt' => 'Graph 1'],
            ['path' => '/images/graph2.jpg', 'alt' => 'Graph 2'],
        ];

        $method = new \ReflectionMethod($this->view, 'prepareImageData');
        $method->setAccessible(true);

        $result = $method->invoke($this->view, $images);

        foreach ($result as $image) {
            $this->assertArrayHasKey('path', $image);
            $this->assertArrayHasKey('alt', $image);
        }

        $this->assertEquals('/images/graph1.jpg', $result[0]['path']);
        $this->assertEquals('Graph 1', $result[0]['alt']);
    }

    public function testArrangeDataByColumnsDistributesDataCorrectly(): void
    {
        $data = [
            ['channel' => 'Sensor 1', 'temperature' => 300.123],
            ['channel' => 'Sensor 2', 'temperature' => 302.456],
            ['channel' => 'Sensor 3', 'temperature' => 305.789],
        ];

        $method = new \ReflectionMethod($this->view, 'arrangeDataByColumns');
        $method->setAccessible(true);

        $result = $method->invoke($this->view, $data, 2);

        $this->assertCount(2, $result); // Two columns
        $this->assertCount(2, $result[0]); // First row has 2 items
        $this->assertCount(1, $result[1]); // Second row has 1 item
    }
}
