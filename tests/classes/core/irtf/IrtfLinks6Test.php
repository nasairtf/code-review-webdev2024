<?php

declare(strict_types=1);

namespace Tests\classes\core\irtf;

use PHPUnit\Framework\TestCase;
use App\core\irtf\IrtfLinks;

class IrtfLinks6Test extends TestCase
{
    public function testGetSummitInformation(): void
    {
        $this->assertEquals(
            '/safety/Summit_Information.pdf',
            IrtfLinks::getSummitInformation()
        );
    }

    public function testGetSafeEnjoyableTrip(): void
    {
        $this->assertEquals(
            '/safety/Safe_and_Enjoyable_Trip.pdf',
            IrtfLinks::getSafeEnjoyableTrip()
        );
    }

    public function testGetMKSSSafetyInfo(): void
    {
        $this->assertEquals(
            '/safety/MKSS_safety_info_2013/',
            IrtfLinks::getMKSSSafetyInfo()
        );
    }

    public function testGetVehicleInformation(): void
    {
        $this->assertEquals(
            '/observing/transportation/information.php',
            IrtfLinks::getVehicleInformation()
        );
    }

    public function testGetVehicleSchedule(): void
    {
        $this->assertEquals(
            '/Keybox/vehicle.txt',
            IrtfLinks::getVehicleSchedule()
        );
    }

    public function testGetAccidentReporting(): void
    {
        $this->assertEquals(
            '/observing/transportation/Accident_reporting.pdf',
            IrtfLinks::getAccidentReporting()
        );
    }

    public function testGetWeatherQuickLook(): void
    {
        $this->assertEquals(
            '/weather/quicklook.php',
            IrtfLinks::getWeatherQuickLook()
        );
    }

    public function testGetVisibleAllSky(): void
    {
        $this->assertEquals(
            '/weather/allsky.php',
            IrtfLinks::getVisibleAllSky()
        );
    }

    public function testGetInfraredAllSky(): void
    {
        $this->assertEquals(
            'http://www.cfht.hawaii.edu/~asiva',
            IrtfLinks::getInfraredAllSky()
        );
    }

    public function testGetWeather(): void
    {
        $this->assertEquals(
            '/weather/index.php',
            IrtfLinks::getWeather()
        );
    }

    public function testGetIRTFWeather(): void
    {
        $this->assertEquals(
            '/weather/IRTFLocalPages.php',
            IrtfLinks::getIRTFWeather()
        );
    }

    public function testGetMKWC(): void
    {
        $this->assertEquals(
            'http://mkwc.ifa.hawaii.edu/',
            IrtfLinks::getMKWC()
        );
    }

    public function testGetIFAWeather(): void
    {
        $this->assertEquals(
            'http://www.ifa.hawaii.edu/info/front_page_news/weather.shtml',
            IrtfLinks::getIFAWeather()
        );
    }

    public function testGet88inAllSky(): void
    {
        $this->assertEquals(
            'http://kree.ifa.hawaii.edu/allsky',
            IrtfLinks::get88inAllSky()
        );
    }

    public function testGetGeminiCloudCam(): void
    {
        $this->assertEquals(
            'https://www.gemini.edu/sciops/telescopes-and-sites/weather/mauna-kea/cloud-cam',
            IrtfLinks::getGeminiCloudCam()
        );
    }

    public function testGetCFHTHomepage(): void
    {
        $this->assertEquals(
            'http://www.cfht.hawaii.edu',
            IrtfLinks::getCFHTHomepage()
        );
    }

    public function testGetASIVA(): void
    {
        $this->assertEquals(
            'http://www.cfht.hawaii.edu/~asiva',
            IrtfLinks::getASIVA()
        );
    }

    public function testGetCFHTCloudCams(): void
    {
        $this->assertEquals(
            'http://www.cfht.hawaii.edu/en/gallery/cloudcams',
            IrtfLinks::getCFHTCloudCams()
        );
    }

    public function testGetKeckHomepage(): void
    {
        $this->assertEquals(
            'http://www2.keck.ifa.hawaii.edu',
            IrtfLinks::getKeckHomepage()
        );
    }
}
