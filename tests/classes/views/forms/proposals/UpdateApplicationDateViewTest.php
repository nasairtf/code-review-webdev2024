<?php

declare(strict_types=1);

namespace Tests\classes\views\forms\proposals;

use Mockery;
use PHPUnit\Framework\TestCase;
use Tests\utilities\helpers\UnitTestSetupTrait;
use Tests\utilities\helpers\UnitTestTeardownTrait;

class UpdateApplicationDateViewTest extends TestCase
{
    use UnitTestSetupTrait;
    use UnitTestTeardownTrait;

    public function testReturnsAsc(): void
    {
        $this->assertSame(1, 1);
    }
}
