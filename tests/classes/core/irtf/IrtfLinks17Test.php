<?php

declare(strict_types=1);

namespace Tests\classes\core\irtf;

use PHPUnit\Framework\TestCase;
use App\core\irtf\IrtfLinks;

class IrtfLinks17Test extends TestCase
{
    public function testGetIfaCalendar(): void
    {
        $this->assertEquals(
            'https://home.ifa.hawaii.edu/ifa/calendar.htm',
            IrtfLinks::getIfaCalendar()
        );
    }

    public function testGetIfaPhones(): void
    {
        $this->assertEquals(
            'https://app.ifa.hawaii.edu/personnel',
            IrtfLinks::getIfaPhones()
        );
    }

    public function testGetNeosurvey(): void
    {
        $this->assertEquals(
            'http://smass.mit.edu/minus.html',
            IrtfLinks::getNeosurvey()
        );
    }

    public function testGetUHFMOWH1Top(): void
    {
        $this->assertEquals(
            'https://www.hawaii.edu/fmo/payment-reimbursement/forms-disbursing/',
            IrtfLinks::getUHFMOWH1Top()
        );
    }

    public function testGetUHFMOWH1TopAlt(): void
    {
        $this->assertEquals(
            'https://www.hawaii.edu/fmo/payment-reimbursement/forms-disbursing/',
            IrtfLinks::getUHFMOWH1TopAlt()
        );
    }

    public function testGetUHFMOWH1Form(): void
    {
        $this->assertEquals(
            'https://drive.google.com/file/d/15ksQJRrQgO66klWvtYg5Cm2zT7EAT-U0/view',
            IrtfLinks::getUHFMOWH1Form()
        );
    }

    public function testGetUHFMOWH1FormAlt(): void
    {
        $this->assertEquals(
            'https://drive.google.com/file/d/15ksQJRrQgO66klWvtYg5Cm2zT7EAT-U0/view',
            IrtfLinks::getUHFMOWH1FormAlt()
        );
    }

    public function testGetCDCGuidelines(): void
    {
        $this->assertEquals(
            'https://www.cdc.gov/coronavirus/2019-ncov/specific-groups/guidance-business-response.html',
            IrtfLinks::getCDCGuidelines()
        );
    }

    public function testGetClO(): void
    {
        $this->assertEquals(
            'https://ndacc.larc.nasa.gov/instruments/microwave-radiometer',
            IrtfLinks::getClO()
        );
    }
}
