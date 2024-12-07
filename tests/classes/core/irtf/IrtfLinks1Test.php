<?php

namespace Tests\classes\core\irtf;

use PHPUnit\Framework\TestCase;
use App\core\irtf\IrtfLinks;

class IrtfLinks1Test extends TestCase
{
    public function testGetHome(): void
    {
        $this->assertEquals('/', IrtfLinks::getHome());
    }

    public function testGetSiteMap(): void
    {
        $this->assertEquals('/siteMap.php', IrtfLinks::getSiteMap());
    }

    public function testGetHelp(): void
    {
        $this->assertEquals('/help.php', IrtfLinks::getHelp());
    }

    public function testGetAbout(): void
    {
        $this->assertEquals('/information/about.php', IrtfLinks::getAbout());
    }

    public function testGetContacts(): void
    {
        $this->assertEquals('/information/contacts.php', IrtfLinks::getContacts());
    }

    public function testGetCredits(): void
    {
        $this->assertEquals('/information/credits.php', IrtfLinks::getCredits());
    }

    public function testGetMetrics(): void
    {
        $this->assertEquals('/information/metrics/IRTF_metrics_200630.pdf', IrtfLinks::getMetrics());
    }

    public function testGetPastAnnouncements(): void
    {
        $this->assertEquals('/information/pastAnnouncements.php', IrtfLinks::getPastAnnouncements());
    }

    public function testGetVideo(): void
    {
        $this->assertEquals('/information/video.php', IrtfLinks::getVideo());
    }

    public function testGetRemoteLocations(): void
    {
        $this->assertEquals('/information/remlocations.php', IrtfLinks::getRemoteLocations());
    }

    public function testGetContactUs(): void
    {
        $this->assertEquals('/information/contactus.php', IrtfLinks::getContactUs());
    }

    public function testGetPastMeetings(): void
    {
        $this->assertEquals('/information/pastMeetings.php', IrtfLinks::getPastMeetings());
    }

    public function testGetNews(): void
    {
        $this->assertEquals('/information/newsletter/index.php', IrtfLinks::getNews());
    }

    public function testGetNewsBySemester(): void
    {
        $semester = '2023A';
        $this->assertEquals(
            "/information/newsletter/index.php?s={$semester}",
            IrtfLinks::getNewsBySemester($semester)
        );
    }

    public function testGetNewsIndex(): void
    {
        $this->assertEquals('/information/newsletter/index.php?s=index', IrtfLinks::getNewsIndex());
    }

    public function testGetAstroday(): void
    {
        $this->assertEquals('/information/miscellaneous/Astroday2k2.ppt', IrtfLinks::getAstroday());
    }

    public function testGetPlanning(): void
    {
        $this->assertEquals('/Documents/index.php', IrtfLinks::getPlanning());
    }

    public function testGetMeetings(): void
    {
        $this->assertEquals('/meetings', IrtfLinks::getMeetings());
    }

    public function testGetFuture2018Meeting(): void
    {
        $this->assertEquals('/meetings/irtf_future_2018', IrtfLinks::getFuture2018Meeting());
    }

    public function testGetFuture2018Presentations(): void
    {
        $this->assertEquals('/meetings/irtf_future_2018/Presentations', IrtfLinks::getFuture2018Presentations());
    }
}
