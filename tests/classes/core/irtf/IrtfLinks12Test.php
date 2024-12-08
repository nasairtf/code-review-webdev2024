<?php

declare(strict_types=1);

namespace Tests\classes\core\irtf;

use PHPUnit\Framework\TestCase;
use App\core\irtf\IrtfLinks;

class IrtfLinks12Test extends TestCase
{
    public function testGetIRTFStaff(): void
    {
        $this->assertEquals(
            '/~proposal/documentation/irtfstaff.php',
            IrtfLinks::getIRTFStaff()
        );
    }

    public function testGetUploadSchedule(): void
    {
        $this->assertEquals(
            '/~proposal/schedule/UploadScheduleFile.php',
            IrtfLinks::getUploadSchedule()
        );
    }

    public function testGetExportTACResults(): void
    {
        $this->assertEquals(
            '/~proposal/schedule/ExportTACResults.php',
            IrtfLinks::getExportTACResults()
        );
    }

    public function testGetUploadTACResults(): void
    {
        $this->assertEquals(
            '/~proposal/schedule/UploadTACResults.php',
            IrtfLinks::getUploadTACResults()
        );
    }

    public function testGetGenerateSchedule(): void
    {
        $this->assertEquals(
            '/~proposal/schedule/GenerateSchedule.php',
            IrtfLinks::getGenerateSchedule()
        );
    }

    public function testGetEditSupportAstromers(): void
    {
        $this->assertEquals(
            '/~proposal/schedule/EditSupportAstromers.php',
            IrtfLinks::getEditSupportAstromers()
        );
    }

    public function testGetEditOperators(): void
    {
        $this->assertEquals(
            '/~proposal/schedule/EditOperators.php',
            IrtfLinks::getEditOperators()
        );
    }

    public function testGetEditInstruments(): void
    {
        $this->assertEquals(
            '/~proposal/schedule/EditInstruments.php',
            IrtfLinks::getEditInstruments()
        );
    }

    public function testGetEditEngPrograms(): void
    {
        $this->assertEquals(
            '/~proposal/schedule/EditEngPrograms.php',
            IrtfLinks::getEditEngPrograms()
        );
    }

    public function testGetTAC(): void
    {
        $this->assertEquals(
            '/~proposal/tac/tac.php',
            IrtfLinks::getTAC()
        );
    }

    public function testGetTACIndex(): void
    {
        $this->assertEquals(
            '/~proposal/tac/tac.php?s=index',
            IrtfLinks::getTACIndex()
        );
    }

    public function testGetTACSemester(): void
    {
        $sem = '2023A';
        $this->assertEquals(
            "/~proposal/tac/tac.php?s={$sem}",
            IrtfLinks::getTACSemester($sem)
        );
    }

    public function testGetDataRequest(): void
    {
        $this->assertEquals(
            '/~proposal/datarequest',
            IrtfLinks::getDataRequest()
        );
    }

    public function testGetScienceHighlightsUpload(): void
    {
        $this->assertEquals(
            '/~proposal/sciencehighlights',
            IrtfLinks::getScienceHighlightsUpload()
        );
    }

    public function testGetAo(): void
    {
        $this->assertEquals(
            '/~ao',
            IrtfLinks::getAo()
        );
    }

    public function testGetApogee(): void
    {
        $this->assertEquals(
            '/~apogee',
            IrtfLinks::getApogee()
        );
    }

    public function testGetCshell(): void
    {
        $this->assertEquals(
            '/~cshell',
            IrtfLinks::getCshell()
        );
    }

    public function testGetCshellStart(): void
    {
        $this->assertEquals(
            '/~cshell/start.html',
            IrtfLinks::getCshellStart()
        );
    }

    public function testGetNsfcam(): void
    {
        $this->assertEquals(
            '/~nsfcam',
            IrtfLinks::getNsfcam()
        );
    }
}
