<?php

use Mockery as Mockery;
use PHPUnit\Framework\TestCase;
use App\views\forms\BaseFormView;

class BaseFormViewTest extends TestCase
{
    public function tearDown(): void
    {
        // Ensure Mockery's expectations are met and clear resources
        Mockery::close();
    }

    public function testCanInstantiate(): void
    {
        // Mock Debug and set expectations
        $mockDebug = Mockery::mock('App\core\common\Debug');
        $mockDebug->shouldReceive('debugHeading')->andReturn('Mock Debug Heading');
        $mockDebug->shouldReceive('debug')->andReturnNull();
        $mockDebug->shouldReceive('debugVariable')->andReturnNull();

        // Create a partial mock for BaseFormView with dependencies mocked
        $stub = Mockery::mock(BaseFormView::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        // Inject the mocked Debug instance
        $stub->__construct(false, $mockDebug);

        $this->assertInstanceOf(BaseFormView::class, $stub);
    }

    public function testDebugModeInitialization(): void
    {
        // Mock the Debug class
        $mockDebug = Mockery::mock('App\core\common\Debug');
        $mockDebug->shouldReceive('debugHeading')->andReturn('Mock Debug Heading');
        $mockDebug->shouldReceive('debug')->andReturnNull();
        $mockDebug->shouldReceive('debugVariable')->andReturnNull();

        // Create a partial mock for BaseFormView
        $stub = Mockery::mock(BaseFormView::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        // Inject the mocked Debug instance
        $stub->__construct(true, $mockDebug);

        // Assertions
        $this->assertTrue($stub->formatHtml, 'HTML formatting should be enabled.');
    }
}
