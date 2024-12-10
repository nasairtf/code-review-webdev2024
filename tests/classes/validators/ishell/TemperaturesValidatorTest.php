<?php

declare(strict_types=1);

namespace Tests\validators\ishell;

use App\validators\ishell\TemperaturesValidator;
use App\core\common\Debug;
use PHPUnit\Framework\TestCase;

class TemperaturesValidatorTest extends TestCase
{
    private $debugMock;
    private $validator;

    protected function setUp(): void
    {
        $this->debugMock = $this->createMock(Debug::class);
        $this->validator = new TemperaturesValidator(['controller' => 'monitor'], $this->debugMock);
    }

    public function testValidateTemperatureDataReturnsTransformedData(): void
    {
        $dbData = [
            'channel1' => [
                ['timestamp' => '2024-12-01 12:00:00', 'ktemp' => 300.123],
                ['timestamp' => '2024-12-01 13:00:00', 'ktemp' => 302.456],
            ],
            'channel2' => [
                ['timestamp' => '2024-12-01 12:00:00', 'ktemp' => 305.789],
            ],
        ];

        $result = $this->validator->validateTemperatureData($dbData);

        $this->assertArrayHasKey('times', $result);
        $this->assertArrayHasKey('temps', $result);
        $this->assertCount(2, $result['times']);
        $this->assertCount(2, $result['temps']);
    }

    public function testValidateTemperatureDataWithEmptyInput(): void
    {
        $result = $this->validator->validateTemperatureData([]);
        $this->assertSame(['times' => [], 'temps' => []], $result);
    }
}
