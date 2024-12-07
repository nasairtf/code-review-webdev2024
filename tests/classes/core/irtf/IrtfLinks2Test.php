<?php

namespace Tests\classes\core\irtf;

use PHPUnit\Framework\TestCase;
use App\core\irtf\IrtfLinks;

class IrtfLinks2Test extends TestCase
{
    public function testGetFuture2018WhitePapers(): void
    {
        $this->assertEquals('/meetings/irtf_future_2018/WhitePapers', IrtfLinks::getFuture2018WhitePapers());
    }

    public function testGetAstrophysicsDecadal190126(): void
    {
        $this->assertEquals(
            '/meetings/irtf_future_2018/WhitePapers/IRTF_Astrophysics_Decadal_190126.pdf',
            IrtfLinks::getAstrophysicsDecadal190126()
        );
    }

    public function testGetPlanetaryDecadal200710(): void
    {
        $this->assertEquals(
            '/meetings/irtf_future_2018/WhitePapers/IRTF_Planetary_Decadal_200710.pdf',
            IrtfLinks::getPlanetaryDecadal200710()
        );
    }

    public function testGetObservingTop(): void
    {
        $this->assertEquals('/observing', IrtfLinks::getObservingTop());
    }

    public function testGetFeedback(): void
    {
        $this->assertEquals('/observing/feedback/feedback.php', IrtfLinks::getFeedback());
    }

    public function testGetChecklist(): void
    {
        $this->assertEquals('/observing/preparingForRun.php', IrtfLinks::getChecklist());
    }

    public function testGetCreditDoc(): void
    {
        $this->assertEquals('/observing/creditcardform.doc', IrtfLinks::getCreditDoc());
    }

    public function testGetCreditPdf(): void
    {
        $this->assertEquals('/observing/creditcardform.pdf', IrtfLinks::getCreditPdf());
    }

    public function testGetDriverClearance(): void
    {
        $this->assertEquals('/observing/driverclearance.pdf', IrtfLinks::getDriverClearance());
    }

    public function testGetObserving(): void
    {
        $this->assertEquals('/observing/index.php', IrtfLinks::getObserving());
    }

    public function testGetObservingInfo(): void
    {
        $this->assertEquals('/observing/information.php', IrtfLinks::getObservingInfo());
    }

    public function testGetHelium(): void
    {
        $this->assertEquals('/observing/liquidHelium.php', IrtfLinks::getHelium());
    }

    public function testGetObserverInfo(): void
    {
        $this->assertEquals('/observing/observerInfo.php', IrtfLinks::getObserverInfo());
    }

    public function testGetObserverManual(): void
    {
        $this->assertEquals('/observing/observerManual.php', IrtfLinks::getObserverManual());
    }

    public function testGetORF(): void
    {
        $this->assertEquals('/observing/orf', IrtfLinks::getORF());
    }

    public function testGetHalePohakuServices(): void
    {
        $this->assertEquals('/observing/servicesHalePohaku.pdf', IrtfLinks::getHalePohakuServices());
    }

    public function testGetObservingSchedule(): void
    {
        $this->assertEquals('/observing/schedule.php', IrtfLinks::getObservingSchedule());
    }

    public function testGetStorage(): void
    {
        $this->assertEquals('/observing/storageShipping.php', IrtfLinks::getStorage());
    }

    public function testGetStorageForm(): void
    {
        $this->assertEquals('/observing/storage_print.pdf', IrtfLinks::getStorageForm());
    }

    public function testGetTelescopeSpecs(): void
    {
        $this->assertEquals('/observing/telescopeSpecs.php', IrtfLinks::getTelescopeSpecs());
    }
}
