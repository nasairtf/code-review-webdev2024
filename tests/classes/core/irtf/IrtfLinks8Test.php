<?php

declare(strict_types=1);

namespace Tests\classes\core\irtf;

use PHPUnit\Framework\TestCase;
use App\core\irtf\IrtfLinks;

class IrtfLinks8Test extends TestCase
{
    public function testGetBibliography(): void
    {
        $this->assertEquals(
            'https://ui.adsabs.harvard.edu/search/q=bibgroup%3A%22irtf%22&sort=date%20desc%2C%20bibcode%20desc&p_=0',
            IrtfLinks::getBibliography()
        );
    }

    public function testGetNonRefereedBibliography(): void
    {
        $this->assertEquals(
            '/research/biblio/Non_Refereed.html',
            IrtfLinks::getNonRefereedBibliography()
        );
    }

    public function testGetDissertationsBibliography(): void
    {
        $this->assertEquals(
            '/research/biblio/dissertations.html',
            IrtfLinks::getDissertationsBibliography()
        );
    }

    public function testGetArchiveAtIRSA(): void
    {
        $this->assertEquals(
            'https://irsa.ipac.caltech.edu/Missions/irtf.html',
            IrtfLinks::getArchiveAtIRSA()
        );
    }

    public function testGetIRTFDataArchive(): void
    {
        $this->assertEquals(
            'http://irtfweb.ifa.hawaii.edu/research/irtf_data_archive.php',
            IrtfLinks::getIRTFDataArchive()
        );
    }

    public function testGetFelix(): void
    {
        $this->assertEquals(
            '/~felix',
            IrtfLinks::getFelix()
        );
    }

    public function testGetIshell(): void
    {
        $this->assertEquals(
            '/~ishell',
            IrtfLinks::getIshell()
        );
    }

    public function testGetIshellDocs(): void
    {
        $this->assertEquals(
            '/~ishell/iSHELL_observing_manual.pdf',
            IrtfLinks::getIshellDocs()
        );
    }

    public function testGetMirsiCfa(): void
    {
        $this->assertEquals(
            'http://www.cfa.harvard.edu/mirsi',
            IrtfLinks::getMirsiCfa()
        );
    }

    public function testGetMirsi(): void
    {
        $this->assertEquals(
            '/~mirsi',
            IrtfLinks::getMirsi()
        );
    }

    public function testGetMirsiCfP2022A(): void
    {
        $this->assertEquals(
            '/~mirsi/MIRSI_Call_for_Proposals2022A.pdf',
            IrtfLinks::getMirsiCfP2022A()
        );
    }

    public function testGetMoc(): void
    {
        $this->assertEquals(
            '/~moc',
            IrtfLinks::getMoc()
        );
    }

    public function testGetMoris(): void
    {
        $this->assertEquals(
            '/~moris',
            IrtfLinks::getMoris()
        );
    }

    public function testGetOpihi(): void
    {
        $this->assertEquals(
            '/~opihi',
            IrtfLinks::getOpihi()
        );
    }

    public function testGetSmokey(): void
    {
        $this->assertEquals(
            '/~smokey',
            IrtfLinks::getSmokey()
        );
    }

    public function testGetSpectre(): void
    {
        $this->assertEquals(
            '/~spectre',
            IrtfLinks::getSpectre()
        );
    }

    public function testGetSpex(): void
    {
        $this->assertEquals(
            '/~spex',
            IrtfLinks::getSpex()
        );
    }

    public function testGetSpexSource(): void
    {
        $this->assertEquals(
            '/Facility/spectra_source/',
            IrtfLinks::getSpexSource()
        );
    }

    public function testGetSpexInternal(): void
    {
        $this->assertEquals(
            '/~spex/internal',
            IrtfLinks::getSpexInternal()
        );
    }
}
