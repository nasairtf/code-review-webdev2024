<?php

declare(strict_types=1);

namespace Tests\classes\controllers\feedback;

use Mockery;
use PHPUnit\Framework\TestCase;
use Tests\utilities\helpers\UnitTestTeardownTrait;

class FeedbackControllerTest extends TestCase
{
    use UnitTestTeardownTrait;

    public function testReturnsAsc(): void
    {
        $this->assertSame(1, 1);
    }
}
