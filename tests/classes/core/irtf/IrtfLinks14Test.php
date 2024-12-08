<?php

declare(strict_types=1);

namespace Tests\classes\core\irtf;

use PHPUnit\Framework\TestCase;
use App\core\irtf\IrtfLinks;

class IrtfLinks14Test extends TestCase
{
    public function testGetGalleryTour(): void
    {
        $url = '"http://www.panaviz.com/scenic-hawaii/mauna-kea/nasa-irtf/bwdetect.html"';
        $this->assertEquals(
            "javascript:CreateWnd({$url},540,550,scrollbars=false);",
            IrtfLinks::getGalleryTour()
        );
    }

    public function testGetGalleryUser(): void
    {
        $this->assertEquals(
            '/gallery/user/',
            IrtfLinks::getGalleryUser()
        );
    }

    public function testGetGalleryFacilityOverview(): void
    {
        $this->assertEquals(
            '/gallery/facility',
            IrtfLinks::getGalleryFacilityOverview()
        );
    }

    public function testGetOOAInfoDrive(): void
    {
        $this->assertEquals(
            'https://drive.google.com/open?id=1895ax3z9FSjPvIfHnLGYUTYuSGRnzFr2m6htKRRndYo',
            IrtfLinks::getOOAInfoDrive()
        );
    }

    public function testGetStaffZoomDrive(): void
    {
        $this->assertEquals(
            'https://drive.google.com/drive/folders/1ImYOHzgahIPWbaSsjlByRimfa4i-0CTd?usp=sharing',
            IrtfLinks::getStaffZoomDrive()
        );
    }

    public function testGetSafety(): void
    {
        $this->assertEquals(
            '/irtf/Safety/Safety.html',
            IrtfLinks::getSafety()
        );
    }

    public function testGetStaffZoom(): void
    {
        $this->assertEquals(
            '/irtf/zoom/zoom.php',
            IrtfLinks::getStaffZoom()
        );
    }

    public function testGetStaffSkype(): void
    {
        $this->assertEquals(
            '/irtf/skype/skype.php',
            IrtfLinks::getStaffSkype()
        );
    }

    public function testGetTwiki(): void
    {
        $this->assertEquals(
            '/twiki',
            IrtfLinks::getTwiki()
        );
    }

    public function testGetIrtfOnly(): void
    {
        $this->assertEquals(
            '/irtf',
            IrtfLinks::getIrtfOnly()
        );
    }

    public function testGetIrtfOnlyBenchmark(): void
    {
        $this->assertEquals(
            '/irtf/benchmark/dailylog',
            IrtfLinks::getIrtfOnlyBenchmark()
        );
    }

    public function testGetIrtfOnlyOrf(): void
    {
        $this->assertEquals(
            '/irtf/orf',
            IrtfLinks::getIrtfOnlyOrf()
        );
    }

    public function testGetIrtfOnlyWiki(): void
    {
        $this->assertEquals(
            '/irtf/wiki',
            IrtfLinks::getIrtfOnlyWiki()
        );
    }

    public function testGetIrtfOnlyTechNotes(): void
    {
        $this->assertEquals(
            '/irtf/wiki/index.php/Main/TechGroupNotes',
            IrtfLinks::getIrtfOnlyTechNotes()
        );
    }

    public function testGetTOSchedule(): void
    {
        $this->assertEquals(
            '/irtf/tosched',
            IrtfLinks::getTOSchedule()
        );
    }

    public function testGetComputingDocs(): void
    {
        $this->assertEquals(
            '/irtf/computing',
            IrtfLinks::getComputingDocs()
        );
    }

    public function testGetNetworkDocs(): void
    {
        $this->assertEquals(
            '/irtf/computing/network',
            IrtfLinks::getNetworkDocs()
        );
    }

    public function testGetWebDocs(): void
    {
        $this->assertEquals(
            '/irtf/computing/webdocs',
            IrtfLinks::getWebDocs()
        );
    }

    public function testGetWebLogs(): void
    {
        $this->assertEquals(
            '/irtf/computing/log',
            IrtfLinks::getWebLogs()
        );
    }
}
