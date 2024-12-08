<?php

declare(strict_types=1);

namespace Tests\classes\core\irtf;

use PHPUnit\Framework\TestCase;
use App\core\irtf\IrtfLinks;

class IrtfLinks4Test extends TestCase
{
    public function testGetNasaApplicationDAPR(): void
    {
        $this->assertEquals(
            'https://science.nasa.gov/researchers/dual-anonymous-peer-review',
            IrtfLinks::getNasaApplicationDAPR()
        );
    }

    public function testGetSofia(): void
    {
        $this->assertEquals(
            '/observing/IRTF_SOFIA_Jointproposals_Feb2022.pdf',
            IrtfLinks::getSofia()
        );
    }

    public function testGetObservingApplications(): void
    {
        $this->assertEquals(
            '/observing/applicationForms',
            IrtfLinks::getObservingApplications()
        );
    }

    public function testGetStaffApplication(): void
    {
        $this->assertEquals(
            "{$_SERVER['PHP_SELF']}?staff",
            IrtfLinks::getStaffApplication()
        );
    }

    public function testGetTestApplication(): void
    {
        $this->assertEquals(
            '/observing/application/application_TEST.php',
            IrtfLinks::getTestApplication()
        );
    }

    public function testGetObservingApplicationsDoc(): void
    {
        $this->assertEquals(
            '/observing/application/ProposalAttachment_vSept2023.docx',
            IrtfLinks::getObservingApplicationsDoc()
        );
    }

    public function testGetObservingApplicationsSty(): void
    {
        $this->assertEquals(
            '/observing/application/epsf.sty',
            IrtfLinks::getObservingApplicationsSty()
        );
    }

    public function testGetObservingApplicationsTex(): void
    {
        $this->assertEquals(
            '/observing/application/ProposalAttachment_vSept2023.tex',
            IrtfLinks::getObservingApplicationsTex()
        );
    }

    public function testGetCallsForProposals(): void
    {
        $this->assertEquals(
            '/observing/callforproposals/index.php',
            IrtfLinks::getCallsForProposals()
        );
    }

    public function testGetCallsForProposalsIndex(): void
    {
        $this->assertEquals(
            '/observing/callforproposals/index.php?s=index',
            IrtfLinks::getCallsForProposalsIndex()
        );
    }

    public function testGetCallsForProposalsSemester(): void
    {
        $semester = '2023A';
        $this->assertEquals(
            "/observing/callforproposals/index.php?s={$semester}",
            IrtfLinks::getCallsForProposalsSemester($semester)
        );
    }

    public function testGetRemoteObserving(): void
    {
        $this->assertEquals(
            '/observing/computer',
            IrtfLinks::getRemoteObserving()
        );
    }

    public function testGetObserverComputing(): void
    {
        $this->assertEquals(
            '/observing/computer',
            IrtfLinks::getObserverComputing()
        );
    }

    public function testGetObserverDataBackup(): void
    {
        $this->assertEquals(
            '/observing/computer/data_backup.php',
            IrtfLinks::getObserverDataBackup()
        );
    }

    public function testGetObserverDataReleasePolicy(): void
    {
        $this->assertEquals(
            '/observing/computer/data_release_policy.php',
            IrtfLinks::getObserverDataReleasePolicy()
        );
    }

    public function testGetObserverAccounts(): void
    {
        $this->assertEquals(
            '/observing/computer/guests.php',
            IrtfLinks::getObserverAccounts()
        );
    }

    public function testGetObserverVnc(): void
    {
        $this->assertEquals(
            '/observing/computer/vnc.php',
            IrtfLinks::getObserverVnc()
        );
    }

    public function testGetRealVnc(): void
    {
        $this->assertEquals(
            'http://www.realvnc.com',
            IrtfLinks::getRealVnc()
        );
    }

    public function testGetRealVncDownload(): void
    {
        $this->assertEquals(
            'http://www.realvnc.com/download/viewer',
            IrtfLinks::getRealVncDownload()
        );
    }
}
