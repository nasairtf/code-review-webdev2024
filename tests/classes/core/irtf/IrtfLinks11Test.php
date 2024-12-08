<?php

declare(strict_types=1);

namespace Tests\classes\core\irtf;

use PHPUnit\Framework\TestCase;
use App\core\irtf\IrtfLinks;

class IrtfLinks11Test extends TestCase
{
    public function testGetHipwac(): void
    {
        $this->assertEquals(
            'https://ssed.gsfc.nasa.gov/hipwac/researchers.html',
            IrtfLinks::getHipwac()
        );
    }

    public function testGetCeleste(): void
    {
        $this->assertEquals(
            'http://celeste',
            IrtfLinks::getCeleste()
        );
    }

    public function testGetDomeVents(): void
    {
        $this->assertEquals(
            '/Facility/dome_vents',
            IrtfLinks::getDomeVents()
        );
    }

    public function testGetFacilityPhones(): void
    {
        $this->assertEquals(
            '/Facility/phones',
            IrtfLinks::getFacilityPhones()
        );
    }

    public function testGetFacilityWeather(): void
    {
        $this->assertEquals(
            '/Facility/weather',
            IrtfLinks::getFacilityWeather()
        );
    }

    public function testGetFacilityTOC(): void
    {
        $this->assertEquals(
            '/Facility/facilityTOC.php',
            IrtfLinks::getFacilityTOC()
        );
    }

    public function testGetTipTilt(): void
    {
        $this->assertEquals(
            '/Facility/tiptilt/',
            IrtfLinks::getTipTilt()
        );
    }

    public function testGetPhotometers(): void
    {
        $this->assertEquals(
            '/Facility/photometers/',
            IrtfLinks::getPhotometers()
        );
    }

    public function testGetXGFit(): void
    {
        $this->assertEquals(
            '/Facility/xgfit/',
            IrtfLinks::getXGFit()
        );
    }

    public function testGetNewDAS(): void
    {
        $this->assertEquals(
            '/Facility/NewDAS/NewDAS.html',
            IrtfLinks::getNewDAS()
        );
    }

    public function testGetCranes(): void
    {
        $this->assertEquals(
            '/Facility/cranes',
            IrtfLinks::getCranes()
        );
    }

    public function testGetDV(): void
    {
        $this->assertEquals(
            '/Facility/DV/index.php',
            IrtfLinks::getDV()
        );
    }

    public function testGetDVGuide(): void
    {
        $this->assertEquals(
            '/Facility/DV/dv_userguide.pdf',
            IrtfLinks::getDVGuide()
        );
    }

    public function testGetDVCheatsheet(): void
    {
        $this->assertEquals(
            '/Facility/DV/dv_cheatsheet_v0.pdf',
            IrtfLinks::getDVCheatsheet()
        );
    }

    public function testGetAccountsList(): void
    {
        $this->assertEquals(
            '/~proposal/accounts/ListAccounts.php',
            IrtfLinks::getAccountsList()
        );
    }

    public function testGetListApplications(): void
    {
        $this->assertEquals(
            '/~proposal/applications/ListApplications.php',
            IrtfLinks::getListApplications()
        );
    }

    public function testGetEditApplications(): void
    {
        $this->assertEquals(
            '/~proposal/applications/EditApplications.php',
            IrtfLinks::getEditApplications()
        );
    }

    public function testGetProcessApplications(): void
    {
        $this->assertEquals(
            '/~proposal/applications/ProcessApplications.php',
            IrtfLinks::getProcessApplications()
        );
    }

    public function testGetProposalProcedures(): void
    {
        $this->assertEquals(
            '/~proposal/documentation',
            IrtfLinks::getProposalProcedures()
        );
    }
}
