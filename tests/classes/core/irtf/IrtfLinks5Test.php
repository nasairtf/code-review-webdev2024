<?php

namespace Tests\classes\core\irtf;

use PHPUnit\Framework\TestCase;
use App\core\irtf\IrtfLinks;

class IrtfLinks5Test extends TestCase
{
    public function testGetObserverZoom(): void
    {
        $this->assertEquals(
            '/observing/computer/zoom.php',
            IrtfLinks::getObserverZoom()
        );
    }

    public function testGetZoomMeetingInstructions(): void
    {
        $this->assertEquals(
            '/observing/computer/ZoomMeetingInstructions.pdf',
            IrtfLinks::getZoomMeetingInstructions()
        );
    }

    public function testGetObserverSkype(): void
    {
        $this->assertEquals(
            '/observing/computer/skype.php',
            IrtfLinks::getObserverSkype()
        );
    }

    public function testGetCommunicationOptions(): void
    {
        $this->assertEquals(
            '/observing/computer/communications.php',
            IrtfLinks::getCommunicationOptions()
        );
    }

    public function testGetVideoConference(): void
    {
        $this->assertEquals(
            '/observing/computer/video_conference.php',
            IrtfLinks::getVideoConference()
        );
    }

    public function testGetServiceObserving(): void
    {
        $this->assertEquals(
            '/observing/computer/service.php',
            IrtfLinks::getServiceObserving()
        );
    }

    public function testGetSafetyIndex(): void
    {
        $this->assertEquals(
            '/safety/index.php',
            IrtfLinks::getSafetyIndex()
        );
    }

    public function testGetSafetyObservers(): void
    {
        $this->assertEquals(
            '/safety/index.php#IRTFObservers',
            IrtfLinks::getSafetyObservers()
        );
    }

    public function testGetSafetyProcedures(): void
    {
        $this->assertEquals(
            '/safety/index.php#Procedures',
            IrtfLinks::getSafetyProcedures()
        );
    }

    public function testGetSafetyRiskAndRelease(): void
    {
        $this->assertEquals(
            '/safety/index.php#RiskAndRelease',
            IrtfLinks::getSafetyRiskAndRelease()
        );
    }

    public function testGetSafetyArchived(): void
    {
        $this->assertEquals(
            '/safety/index.php#Archived',
            IrtfLinks::getSafetyArchived()
        );
    }

    public function testGetSafetyRegulations(): void
    {
        $this->assertEquals(
            '/safety/IRTF_Safety_Regulations.pdf',
            IrtfLinks::getSafetyRegulations()
        );
    }

    public function testGetWinterPrecautions(): void
    {
        $this->assertEquals(
            '/safety/MKSS_Winter_Precautions.pdf',
            IrtfLinks::getWinterPrecautions()
        );
    }

    public function testGetDrivingRecommendations(): void
    {
        $this->assertEquals(
            '/safety/Driving_Recommendations.pdf',
            IrtfLinks::getDrivingRecommendations()
        );
    }

    public function testGetEvacuationProcedure(): void
    {
        $this->assertEquals(
            '/safety/Evacuation_Procedure.pdf',
            IrtfLinks::getEvacuationProcedure()
        );
    }

    public function testGetMaunaKeaHazards(): void
    {
        $this->assertEquals(
            '/safety/MK_Hazards.pdf',
            IrtfLinks::getMaunaKeaHazards()
        );
    }

    public function testGetWinterHazards(): void
    {
        $this->assertEquals(
            '/safety/Winter_Hazards.pdf',
            IrtfLinks::getWinterHazards()
        );
    }

    public function testGetRiskReleaseForm(): void
    {
        $this->assertEquals(
            '/safety/Risk_Release_Form.pdf',
            IrtfLinks::getRiskReleaseForm()
        );
    }

    public function testGetHealthCautionsForm(): void
    {
        $this->assertEquals(
            '/safety/Health_Cautions_Form.pdf',
            IrtfLinks::getHealthCautionsForm()
        );
    }
}
