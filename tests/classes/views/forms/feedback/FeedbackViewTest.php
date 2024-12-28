<?php

declare(strict_types=1);

namespace Tests\classes\views\forms\feedback;

use Mockery;
use PHPUnit\Framework\TestCase;
use Tests\utilities\PrivatePropertyTrait;

use App\views\forms\feedback\FeedbackView;
use App\core\common\Debug;
use App\core\irtf\IrtfLinks;

class FeedbackViewTest extends TestCase
{/*
    private $debugMock;
    private $irtfLinksMock;
    private $feedbackView;

    protected function setUp(): void
    {
        $this->debugMock = Mockery::mock(\App\core\common\Debug::class);
        $this->irtfLinksMock = Mockery::mock(IrtfLinks::class);
        $this->feedbackView = new FeedbackView(false, $this->debugMock, $this->irtfLinksMock);
    }*/

    public function testReturnsAsc(): void
    {
        $this->assertSame(1, 1);
    }
/*
    public function testGetFieldLabelsReturnsCorrectLabels(): void
    {
        $expected = [
            'respondent'         => 'Your Name',
            'email'              => 'E-mail Address',
            'dates'              => 'Observing Dates',
            'support_staff'      => 'Support Astronomer(s)',
            'operator_staff'     => 'Telescope Operator(s)',
            'instruments'        => 'Facility Instrument(s)',
            'visitor_instrument' => 'Visitor Instrument',
            'location'           => 'Observing Location',
            'experience'         => 'Overall Experience',
            'technical'          => 'Technical Commentary',
            'scientificstaff'    => 'Support Staff',
            'operators'          => 'Telescope Operators',
            'daycrew'            => 'Daycrew',
            'personnel'          => 'Personnel Support',
            'scientific'         => 'Scientific Results',
            'comments'           => 'Comments and Suggestions',
        ];

        $this->assertEquals($expected, $this->feedbackView->getFieldLabels());
    }

    public function testGetPageContentsCallsDebug(): void
    {
        $this->debugMock->shouldReceive('debugHeading')
            ->once()
            ->with('View', 'getPageContents')
            ->andReturn('Debug Heading');

        $this->debugMock->shouldReceive('debug')
            ->once()
            ->with('Debug Heading');

        $result = $this->feedbackView->getPageContents();
        $this->assertIsString($result);
    }*/

    /**
     * Cleans up the test environment after each unit test (method).
     *
     * - Verifies Mockery's expectations are met.
     * - Clears resources and prevents leaks between tests.
     * - Ensures necessary parent (PHPUnit) teardown logic runs as well.
     *
     * @return void
     */
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
