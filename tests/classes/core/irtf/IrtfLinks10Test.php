<?php

namespace Tests\classes\core\irtf;

use PHPUnit\Framework\TestCase;
use App\core\irtf\IrtfLinks;

class IrtfLinks10Test extends TestCase
{
    public function testGetQuickLook(): void
    {
        $this->assertEquals(
            '/~quicklook',
            IrtfLinks::getQuickLook()
        );
    }

    public function testGetVNC(): void
    {
        $this->assertEquals(
            '/~vnc',
            IrtfLinks::getVNC()
        );
    }

    public function testGetIRTFPowerMonitor(): void
    {
        $this->assertEquals(
            'http://irtfpowermonitor.ifa.hawaii.edu/',
            IrtfLinks::getIRTFPowerMonitor()
        );
    }

    public function testGetFacilityInstruments(): void
    {
        $this->assertEquals(
            '/instruments',
            IrtfLinks::getFacilityInstruments()
        );
    }

    public function testGetVisitorInstruments(): void
    {
        $this->assertEquals(
            '/instruments/#Visitor',
            IrtfLinks::getVisitorInstruments()
        );
    }

    public function testGetRetiredInstruments(): void
    {
        $this->assertEquals(
            '/instruments/retiredInstruments.php',
            IrtfLinks::getRetiredInstruments()
        );
    }

    public function testGetIRTFCameras(): void
    {
        $this->assertEquals(
            '/~irtfcameras',
            IrtfLinks::getIRTFCameras()
        );
    }

    public function testGetWebcams(): void
    {
        $this->assertEquals(
            '/~irtfcameras',
            IrtfLinks::getWebcams()
        );
    }

    public function testGetIRTFCameraDocs(): void
    {
        $this->assertEquals(
            '/~irtfcameras/irtf',
            IrtfLinks::getIRTFCameraDocs()
        );
    }

    public function testGetWebCamDocs(): void
    {
        $this->assertEquals(
            '/~irtfcameras/irtf',
            IrtfLinks::getWebCamDocs()
        );
    }

    public function testGetTrouble(): void
    {
        $this->assertEquals(
            '/~trouble',
            IrtfLinks::getTrouble()
        );
    }

    public function testGetTroubleLog(): void
    {
        $this->assertEquals(
            '/irtf/troublelog/troublelog.php',
            IrtfLinks::getTroubleLog()
        );
    }

    public function testGetFacility(): void
    {
        $this->assertEquals(
            '/Facility',
            IrtfLinks::getFacility()
        );
    }

    public function testGetFacilityCommunications(): void
    {
        $this->assertEquals(
            '/Facility#Communications',
            IrtfLinks::getFacilityCommunications()
        );
    }

    public function testGetFacilitySchedules(): void
    {
        $this->assertEquals(
            '/Facility#Schedules',
            IrtfLinks::getFacilitySchedules()
        );
    }

    public function testGetFacilitySystems(): void
    {
        $this->assertEquals(
            '/Facility/#Systems',
            IrtfLinks::getFacilitySystems()
        );
    }

    public function testGetFacilityOffsite(): void
    {
        $this->assertEquals(
            '/Facility/#Offsite',
            IrtfLinks::getFacilityOffsite()
        );
    }

    public function testGetFacilityArchived(): void
    {
        $this->assertEquals(
            '/Facility/retiredInstruments.php',
            IrtfLinks::getFacilityArchived()
        );
    }

    public function testGetMIM(): void
    {
        $this->assertEquals(
            '/Facility/MIM',
            IrtfLinks::getMIM()
        );
    }

    public function testGetBass(): void
    {
        $this->assertEquals(
            'http://www.aero.org/capabilities/remotesensing/bass.html',
            IrtfLinks::getBass()
        );
    }
}
