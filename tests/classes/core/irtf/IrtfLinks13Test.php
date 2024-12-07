<?php

namespace Tests\classes\core\irtf;

use PHPUnit\Framework\TestCase;
use App\core\irtf\IrtfLinks;

class IrtfLinks13Test extends TestCase
{
    public function testGetNsfcam2(): void
    {
        $this->assertEquals(
            '/~nsfcam2/Welcome.html',
            IrtfLinks::getNsfcam2()
        );
    }

    public function testGetNsfcam2Internal(): void
    {
        $this->assertEquals(
            '/~nsfcam2/internal',
            IrtfLinks::getNsfcam2Internal()
        );
    }

    public function testGetNsfcamSNCalculator(): void
    {
        $this->assertEquals(
            '/cgi-bin/nsfcam/nsfcam_sncalc.cgi',
            IrtfLinks::getNsfcamSNCalculator()
        );
    }

    public function testGetNsfcamMagCalculator(): void
    {
        $this->assertEquals(
            '/cgi-bin/nsfcam/nsfcam_magcalc.cgi',
            IrtfLinks::getNsfcamMagCalculator()
        );
    }

    public function testGetNsfcamTimeCalculator(): void
    {
        $this->assertEquals(
            '/cgi-bin/nsfcam/nsfcam_timecalc.cgi',
            IrtfLinks::getNsfcamTimeCalculator()
        );
    }

    public function testGetNsfcamFilters(): void
    {
        $this->assertEquals(
            '/~nsfcam/hist/newfilters.html',
            IrtfLinks::getNsfcamFilters()
        );
    }

    public function testGetNsfcamMKFilters(): void
    {
        $this->assertEquals(
            '/~nsfcam/mkfilters.html',
            IrtfLinks::getNsfcamMKFilters()
        );
    }

    public function testGetNsfcam2QuickStart(): void
    {
        $this->assertEquals(
            '/~nsfcam2/Quickstart.html',
            IrtfLinks::getNsfcam2QuickStart()
        );
    }

    public function testGetPhcs(): void
    {
        $this->assertEquals(
            '/~phcs',
            IrtfLinks::getPhcs()
        );
    }

    public function testGetPhcsInternal(): void
    {
        $this->assertEquals(
            '/~phcs/internal',
            IrtfLinks::getPhcsInternal()
        );
    }

    public function testGetPoets(): void
    {
        $this->assertEquals(
            '/~poets',
            IrtfLinks::getPoets()
        );
    }

    public function testGetTcs1(): void
    {
        $this->assertEquals(
            '/Facility/tcs1',
            IrtfLinks::getTcs1()
        );
    }

    public function testGetVSMAMechanical(): void
    {
        $this->assertEquals(
            '/~vern/Mechanical',
            IrtfLinks::getVSMAMechanical()
        );
    }

    public function testGetISON(): void
    {
        $this->assertEquals(
            '/~ison',
            IrtfLinks::getISON()
        );
    }

    public function testGetGalleryTOC(): void
    {
        $this->assertEquals(
            '/gallery/toc.php',
            IrtfLinks::getGalleryTOC()
        );
    }

    public function testGetGallery(): void
    {
        $this->assertEquals(
            '/gallery/index.php',
            IrtfLinks::getGallery()
        );
    }

    public function testGetGalleryStaff(): void
    {
        $this->assertEquals(
            '/gallery/staff.php',
            IrtfLinks::getGalleryStaff()
        );
    }

    public function testGetGalleryNight(): void
    {
        $this->assertEquals(
            '/irtf/night_gallery.php',
            IrtfLinks::getGalleryNight()
        );
    }

    public function testGetGalleryFacility(): void
    {
        $this->assertEquals(
            '/gallery/facility/',
            IrtfLinks::getGalleryFacility()
        );
    }
}
