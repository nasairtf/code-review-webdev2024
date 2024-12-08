<?php

declare(strict_types=1);

namespace Tests\classes\core\irtf;

use PHPUnit\Framework\TestCase;
use App\core\irtf\IrtfLinks;

class IrtfLinks7Test extends TestCase
{
    public function testGetKeckCloudCam1(): void
    {
        $this->assertEquals(
            'http://www2.keck.hawaii.edu/software/weather',
            IrtfLinks::getKeckCloudCam1()
        );
    }

    public function testGetKeckCloudCam2(): void
    {
        $this->assertEquals(
            'http://www2.keck.hawaii.edu/realtime/webcam',
            IrtfLinks::getKeckCloudCam2()
        );
    }

    public function testGetIRReferenceData(): void
    {
        $this->assertEquals(
            '/IRrefdata',
            IrtfLinks::getIRReferenceData()
        );
    }

    public function testGetTelescopeReferenceData(): void
    {
        $this->assertEquals(
            '/IRrefdata/telescope_ref_data.php',
            IrtfLinks::getTelescopeReferenceData()
        );
    }

    public function testGetPhotometricCatalogs(): void
    {
        $this->assertEquals(
            '/IRrefdata/ph_catalogs.php',
            IrtfLinks::getPhotometricCatalogs()
        );
    }

    public function testGetSpectralCatalogs(): void
    {
        $this->assertEquals(
            '/IRrefdata/sp_catalogs.php',
            IrtfLinks::getSpectralCatalogs()
        );
    }

    public function testGetDaytimeSkyBackground(): void
    {
        $this->assertEquals(
            '/IRrefdata/day_sky_bkgrnd.php',
            IrtfLinks::getDaytimeSkyBackground()
        );
    }

    public function testGetIWAFDV(): void
    {
        $this->assertEquals(
            '/IRrefdata/iwafdv.html',
            IrtfLinks::getIWAFDV()
        );
    }

    public function testGetUKIRT(): void
    {
        $this->assertEquals(
            'http://www.ukirt.hawaii.edu/astronomy',
            IrtfLinks::getUKIRT()
        );
    }

    public function testGetResearch(): void
    {
        $this->assertEquals(
            '/research/',
            IrtfLinks::getResearch()
        );
    }

    public function testGetResearchAcknowledgment(): void
    {
        $this->assertEquals(
            '/research/acknowledge.php',
            IrtfLinks::getResearchAcknowledgment()
        );
    }

    public function testGetAwardedTime(): void
    {
        $this->assertEquals(
            '/research/awarded_time.php',
            IrtfLinks::getAwardedTime()
        );
    }

    public function testGetScienceHighlightsPage(): void
    {
        $this->assertEquals(
            '/research/science.php',
            IrtfLinks::getScienceHighlightsPage()
        );
    }

    public function testGetDataReductionResources(): void
    {
        $this->assertEquals(
            '/research/dr_resources',
            IrtfLinks::getDataReductionResources()
        );
    }

    public function testGetFreiaProject(): void
    {
        $this->assertEquals(
            '/research/freia/freia.php',
            IrtfLinks::getFreiaProject()
        );
    }

    public function testGetBiblioInclude(): void
    {
        $this->assertEquals(
            '/htdocs/research/biblio/publications.inc',
            IrtfLinks::getBiblioInclude()
        );
    }

    public function testGetBiblioIncludeDev(): void
    {
        $this->assertEquals(
            '/htdocs/research/biblio/publications_DEV.inc',
            IrtfLinks::getBiblioIncludeDev()
        );
    }

    public function testGetBiblioPHP(): void
    {
        $this->assertEquals(
            '/htdocs/research/biblio/publications.php',
            IrtfLinks::getBiblioPHP()
        );
    }

    public function testGetBiblioPHPDev(): void
    {
        $this->assertEquals(
            '/htdocs/research/biblio/publications_DEV.php',
            IrtfLinks::getBiblioPHPDev()
        );
    }
}
