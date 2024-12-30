<?php

declare(strict_types=1);

namespace Tests\classes\views\forms\feedback;

use Mockery;
use PHPUnit\Framework\TestCase;
use Tests\utilities\helpers\UnitTestTeardownTrait;

class FeedbackViewTest extends TestCase
{
    use UnitTestTeardownTrait;

    public function testReturnsAsc(): void
    {
        $this->assertSame(1, 1);
    }
}
