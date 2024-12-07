<?php

namespace Tests\classes\core\irtf;

use PHPUnit\Framework\TestCase;
use App\core\irtf\IrtfLinks;

class IrtfLinks16Test extends TestCase
{
    public function testGetMIMWTI(): void
    {
        $this->assertEquals(
            '/irtf/computing/network/remotepwr_coolracks.php',
            IrtfLinks::getMIMWTI()
        );
    }

    public function testGetTCSRmWTI(): void
    {
        $this->assertEquals(
            'http://128.171.165.27',
            IrtfLinks::getTCSRmWTI()
        );
    }

    public function testGetTCSRmWTIDocs(): void
    {
        $this->assertEquals(
            '/irtf/computing/network/remotepwr_tcsrm.php',
            IrtfLinks::getTCSRmWTIDocs()
        );
    }

    public function testGetCoudeWTI(): void
    {
        $this->assertEquals(
            'http://128.171.165.192:6876',
            IrtfLinks::getCoudeWTI()
        );
    }

    public function testGetCoudeWTIhttps(): void
    {
        $this->assertEquals(
            'https://128.171.165.192:6476',
            IrtfLinks::getCoudeWTIhttps()
        );
    }

    public function testGetCoudeWTIDocs(): void
    {
        $this->assertEquals(
            '/irtf/computing/network/remotepwr_coude.php',
            IrtfLinks::getCoudeWTIDocs()
        );
    }

    public function testGetHiloSrvWTI(): void
    {
        $this->assertEquals(
            'http://128.171.110.175:6875',
            IrtfLinks::getHiloSrvWTI()
        );
    }

    public function testGetHiloSrvWTIhttps(): void
    {
        $this->assertEquals(
            'https://128.171.110.175:6475',
            IrtfLinks::getHiloSrvWTIhttps()
        );
    }

    public function testGetHiloSrvWTIDocs(): void
    {
        $this->assertEquals(
            '/irtf/computing/network/remotepwr_hilosrv.php',
            IrtfLinks::getHiloSrvWTIDocs()
        );
    }

    public function testGetVNCIndex(): void
    {
        $this->assertEquals(
            '/irtf/vnc_POTD/vnc.php',
            IrtfLinks::getVNCIndex()
        );
    }

    public function testGetVNCPotD(): void
    {
        $this->assertEquals(
            '/irtf/vnc_POTD/index.html',
            IrtfLinks::getVNCPotD()
        );
    }

    public function testGetSysVNC(): void
    {
        $this->assertEquals(
            '/irtf/computing/centos6/howtos/realvnc.php',
            IrtfLinks::getSysVNC()
        );
    }

    public function testGetIborg(): void
    {
        $this->assertEquals(
            'http://iborg.ifa.hawaii.edu:8080/Plone',
            IrtfLinks::getIborg()
        );
    }

    public function testGetPlone(): void
    {
        $this->assertEquals(
            '/irtf/plone-mirror/irtf_plone_2013.08.21/iborg.ifa.hawaii.edu_8080/Plone.html',
            IrtfLinks::getPlone()
        );
    }

    public function testGetGDrive(): void
    {
        $this->assertEquals(
            'https://sites.google.com/a/hawaii.edu/irtf-gdrive-gsite/',
            IrtfLinks::getGDrive()
        );
    }

    public function testGetVehicleList(): void
    {
        $this->assertEquals(
            '/irtf/contacts/vehicle_list.php',
            IrtfLinks::getVehicleList()
        );
    }

    public function testGetStaffPhoneList(): void
    {
        $this->assertEquals(
            '/irtf/contacts/staff_phone_list.php',
            IrtfLinks::getStaffPhoneList()
        );
    }

    public function testGetNightStaffPhoneList(): void
    {
        $this->assertEquals(
            '/irtf/contacts/night_phone_list.php',
            IrtfLinks::getNightStaffPhoneList()
        );
    }

    public function testGetIfa(): void
    {
        $this->assertEquals(
            'https://www.ifa.hawaii.edu',
            IrtfLinks::getIfa()
        );
    }
}
