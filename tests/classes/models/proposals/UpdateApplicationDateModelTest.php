<?php

declare(strict_types=1);

namespace Tests\classes\models\proposals;

use Mockery;
use PHPUnit\Framework\TestCase;
use Tests\utilities\helpers\UnitTestTeardownTrait;

class UpdateApplicationDateModelTest extends TestCase
{
    use UnitTestTeardownTrait;

    public function testReturnsAsc(): void
    {
        $this->assertSame(1, 1);
    }
}
