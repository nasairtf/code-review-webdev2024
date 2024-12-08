<?php

declare(strict_types=1);

namespace Tests\classes\core\irtf;

use PHPUnit\Framework\TestCase;
use App\core\irtf\IrtfLinks;

class IrtfLinks9Test extends TestCase
{
    public function testGetSpexTool(): void
    {
        $this->assertEquals(
            '/~spex/Spextool_v4.1.tar.gz',
            IrtfLinks::getSpexTool()
        );
    }

    public function testGetSpexToolData(): void
    {
        $this->assertEquals(
            '/~spex/uSpeXdata.tar.gz',
            IrtfLinks::getSpexToolData()
        );
    }

    public function testGetSpexSpectralLibrary(): void
    {
        $this->assertEquals(
            '/~spex/IRTF_Spectral_Library',
            IrtfLinks::getSpexSpectralLibrary()
        );
    }

    public function testGetSpexSpectralReferences(): void
    {
        $this->assertEquals(
            '/~spex/IRTF_Spectral_Library/References.html',
            IrtfLinks::getSpexSpectralReferences()
        );
    }

    public function testGetSpexExtendedSpectralLibrary(): void
    {
        $this->assertEquals(
            '/~spex/IRTF_Extended_Spectral_Library',
            IrtfLinks::getSpexExtendedSpectralLibrary()
        );
    }

    public function testGetSpexPrismLibrary(): void
    {
        $this->assertEquals(
            'http://pono.ucsd.edu/~adam/browndwarfs/spexprism/library.html',
            IrtfLinks::getSpexPrismLibrary()
        );
    }

    public function testGetSpexStartupShutdown(): void
    {
        $this->assertEquals(
            '/~spex/work/startup_shutdown/startup_shutdown.html',
            IrtfLinks::getSpexStartupShutdown()
        );
    }

    public function testGetTcs3(): void
    {
        $this->assertEquals(
            '/~tcs3',
            IrtfLinks::getTcs3()
        );
    }

    public function testGetStarcat(): void
    {
        $this->assertEquals(
            '/~tcs3/related/starcat',
            IrtfLinks::getStarcat()
        );
    }

    public function testGetT3RemoteManual(): void
    {
        $this->assertEquals(
            '/~tcs3/tcs3/users_manuals/1102_t3remote.pdf',
            IrtfLinks::getT3RemoteManual()
        );
    }

    public function testGetTcs3UserManuals(): void
    {
        $this->assertEquals(
            '/~tcs3/tcs3/users_manuals/',
            IrtfLinks::getTcs3UserManuals()
        );
    }

    public function testGetTexes(): void
    {
        $this->assertEquals(
            '/~texes/',
            IrtfLinks::getTexes()
        );
    }

    public function testGetMirsi2(): void
    {
        $this->assertEquals(
            '/~m2',
            IrtfLinks::getMirsi2()
        );
    }

    public function testGetSpex2(): void
    {
        $this->assertEquals(
            '/~s2',
            IrtfLinks::getSpex2()
        );
    }

    public function testGetAutofocus(): void
    {
        $this->assertEquals(
            '/~fct',
            IrtfLinks::getAutofocus()
        );
    }

    public function testGetCoolracks(): void
    {
        $this->assertEquals(
            '/~coolracks',
            IrtfLinks::getCoolracks()
        );
    }

    public function testGetIqup(): void
    {
        $this->assertEquals(
            '/~iqup',
            IrtfLinks::getIqup()
        );
    }

    public function testGetIqupTemps(): void
    {
        $this->assertEquals(
            '/~iqup/domeenv/dome.html',
            IrtfLinks::getIqupTemps()
        );
    }

    public function testGetIqupHVAC(): void
    {
        $this->assertEquals(
            '/~iqup/hvac',
            IrtfLinks::getIqupHVAC()
        );
    }

    public function testGetHVAC2014(): void
    {
        $this->assertEquals(
            '/Facility/2014_hvac/',
            IrtfLinks::getHVAC2014()
        );
    }
}
