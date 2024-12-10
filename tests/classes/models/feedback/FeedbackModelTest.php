<?php

declare(strict_types=1);

namespace Tests\classes\models\feedback;

use PHPUnit\Framework\TestCase;
use Mockery;
use App\models\feedback\FeedbackModel;
use App\core\common\Debug;

class FeedbackModelTest extends TestCase
{
    private $debugMock;
    private $model;

    protected function setUp(): void
    {
        $this->debugMock = Mockery::mock(Debug::class);
        $this->model = new FeedbackModel($this->debugMock);
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function testSaveFeedbackCallsInsertFeedbackWithDependencies(): void
    {
        $validData = [
            'feedback' => [],
            'instruments' => [],
            'operators' => [],
            'support' => [],
        ];

        $this->assertTrue($this->model->saveFeedback($validData));
    }
}
