<?php

namespace Tests\classes\core\irtf;

use PHPUnit\Framework\TestCase;
use App\core\irtf\IrtfLinks;

class IrtfLinks3Test extends TestCase
{
    public function testGetIDL(): void
    {
        $this->assertEquals('/observing/computer/idl.php', IrtfLinks::getIDL());
    }

    public function testGetMapIndex(): void
    {
        $this->assertEquals('/observing/maps/index.php', IrtfLinks::getMapIndex());
    }

    public function testGetMapKeybox(): void
    {
        $this->assertEquals('/observing/maps/keybox.php', IrtfLinks::getMapKeybox());
    }

    public function testGetMapAccessRd(): void
    {
        $this->assertEquals('/observing/maps/access-road.pdf', IrtfLinks::getMapAccessRd());
    }

    public function testGetMapBigIsland(): void
    {
        $this->assertEquals(
            'http://www.ifa.hawaii.edu/maps/big_isle_map2.shtml',
            IrtfLinks::getMapBigIsland()
        );
    }

    public function testGetMapHiloMap(): void
    {
        $this->assertEquals(
            'http://www.ifa.hawaii.edu/maps/hilo_map.shtml',
            IrtfLinks::getMapHiloMap()
        );
    }

    public function testGetMapHPMap(): void
    {
        $this->assertEquals(
            'http://www.ifa.hawaii.edu/maps/hp_map.shtml',
            IrtfLinks::getMapHPMap()
        );
    }

    public function testGetMapHiloOffice(): void
    {
        $this->assertEquals('/observing/maps/hilooffice.pdf', IrtfLinks::getMapHiloOffice());
    }

    public function testGetMapIfaManoa(): void
    {
        $this->assertEquals('/observing/maps/ifa-hnl.pdf', IrtfLinks::getMapIfaManoa());
    }

    public function testGetMapMaunaKea(): void
    {
        $this->assertEquals(
            'http://www.ifa.hawaii.edu/maps/summit_map.shtml',
            IrtfLinks::getMapMaunaKea()
        );
    }

    public function testGetMapState(): void
    {
        $this->assertEquals(
            'https://www.ifa.hawaii.edu/maps/hawaii_maps.shtml',
            IrtfLinks::getMapState()
        );
    }

    public function testGetMapOahu(): void
    {
        $this->assertEquals(
            'http://www.ifa.hawaii.edu/maps/oahu_maps.shtml',
            IrtfLinks::getMapOahu()
        );
    }

    public function testGetApplyingForTime(): void
    {
        $this->assertEquals('/observing/applyingForTime.php', IrtfLinks::getApplyingForTime());
    }

    public function testGetPreparingForRun(): void
    {
        $this->assertEquals('/observing/preparingForRun.php', IrtfLinks::getPreparingForRun());
    }

    public function testGetDuringTheRun(): void
    {
        $this->assertEquals('/observing/duringTheRun.php', IrtfLinks::getDuringTheRun());
    }

    public function testGetPostRun(): void
    {
        $this->assertEquals('/observing/postRun.php', IrtfLinks::getPostRun());
    }

    public function testGetApplication(): void
    {
        $this->assertEquals('/observing/applicationForms.php', IrtfLinks::getApplication());
    }

    public function testGetApplicationForm(): void
    {
        $this->assertEquals('/observing/application/application.php', IrtfLinks::getApplicationForm());
    }

    public function testGetApplicationFAQ(): void
    {
        $this->assertEquals('/observing/application/applicationFAQ.php', IrtfLinks::getApplicationFAQ());
    }

    public function testGetApplicationDAPR(): void
    {
        $this->assertEquals('/observing/applicationDAPRInfo.php', IrtfLinks::getApplicationDAPR());
    }
}
