<?php

declare(strict_types=1);

namespace Tests\classes\core\irtf;

use PHPUnit\Framework\TestCase;
use App\core\irtf\IrtfLinks;

class IrtfLinks15Test extends TestCase
{
    public function testGetNetworkPolicies(): void
    {
        $this->assertEquals(
            '/irtf/computing/policies',
            IrtfLinks::getNetworkPolicies()
        );
    }

    public function testGetEmailNotes(): void
    {
        $this->assertEquals(
            '/irtf/computing/info/email.php',
            IrtfLinks::getEmailNotes()
        );
    }

    public function testGetDVDNotes(): void
    {
        $this->assertEquals(
            '/irtf/computing/info/DVD.html',
            IrtfLinks::getDVDNotes()
        );
    }

    public function testGetPowerOnNotes(): void
    {
        $this->assertEquals(
            '/irtf/computing/info/recipe.txt',
            IrtfLinks::getPowerOnNotes()
        );
    }

    public function testGetMailAliases(): void
    {
        $this->assertEquals(
            '/irtf/aliases',
            IrtfLinks::getMailAliases()
        );
    }

    public function testGetAdminProcedures(): void
    {
        $this->assertEquals(
            '/irtf/admin_procedures',
            IrtfLinks::getAdminProcedures()
        );
    }

    public function testGetApacheDocs(): void
    {
        $this->assertEquals(
            '/manual/en',
            IrtfLinks::getApacheDocs()
        );
    }

    public function testGetAWStats(): void
    {
        $this->assertEquals(
            '/awstats/awstats.pl',
            IrtfLinks::getAWStats()
        );
    }

    public function testGetWebStats(): void
    {
        $this->assertEquals(
            '/irtf/webalizer',
            IrtfLinks::getWebStats()
        );
    }

    public function testGetCacti(): void
    {
        $this->assertEquals(
            '/irtf/cacti',
            IrtfLinks::getCacti()
        );
    }

    public function testGetFiberDocs(): void
    {
        $this->assertEquals(
            '/irtf/computing/network/fiber.php',
            IrtfLinks::getFiberDocs()
        );
    }

    public function testGetKVMDcos(): void
    {
        $this->assertEquals(
            '/irtf/computing/network/kvm_201406.php',
            IrtfLinks::getKVMDcos()
        );
    }

    public function testGetKVM(): void
    {
        $this->assertEquals(
            'http://128.171.165.20',
            IrtfLinks::getKVM()
        );
    }

    public function testGetKVMtcs(): void
    {
        $this->assertEquals(
            'http://128.171.165.20/hkc',
            IrtfLinks::getKVMtcs()
        );
    }

    public function testGetKVMmim(): void
    {
        $this->assertEquals(
            'http://128.171.165.24/hkc',
            IrtfLinks::getKVMmim()
        );
    }

    public function testGetKVMcoude(): void
    {
        $this->assertEquals(
            'http://128.171.165.25/hkc',
            IrtfLinks::getKVMcoude()
        );
    }

    public function testGetUPS(): void
    {
        $this->assertEquals(
            'http://128.171.165.54',
            IrtfLinks::getUPS()
        );
    }

    public function testGetWTIDocs(): void
    {
        $this->assertEquals(
            '/irtf/computing/network/remotepwr.php',
            IrtfLinks::getWTIDocs()
        );
    }

    public function testGetRPCDocs(): void
    {
        $this->assertEquals(
            '/irtf/computing/network/remotepwr.php',
            IrtfLinks::getRPCDocs()
        );
    }
}
