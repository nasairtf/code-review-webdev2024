<?php

declare(strict_types=1);

namespace Tests\classes\validators\forms\proposals;

use App\validators\forms\proposals\UpdateApplicationDateValidator;
use App\core\common\Debug;
use Mockery;
use PHPUnit\Framework\TestCase;

class UpdateApplicationDateValidatorTest extends TestCase
{
    private $debugMock;
    private $validator;

    protected function setUp(): void
    {
        $this->debugMock = Mockery::mock(Debug::class);
        $this->validator = new UpdateApplicationDateValidator($this->debugMock);
    }

    public function testValidateYearReturnsValidYear(): void
    {
        $result = $this->validator->validateYear(2023);
        $this->assertEquals(2023, $result);
    }

    public function testValidateSemesterReturnsValidSemester(): void
    {
        $result = $this->validator->validateSemester('A');
        $this->assertEquals('A', $result);
    }

    public function testValidateObsAppIDReturnsValidID(): void
    {
        $result = $this->validator->validateObsAppID(123);
        $this->assertEquals(123, $result);
    }

    public function testValidateTimestampReturnsValidTimestamp(): void
    {
        $result = $this->validator->validateTimestamp(1672531200);
        $this->assertEquals(1672531200, $result);
    }
}

/*
<?php

declare(strict_types=1);

namespace Tests\classes\validators\forms\proposals;

use PHPUnit\Framework\TestCase;
use App\validators\forms\proposals\UpdateApplicationDateValidator;
use App\core\common\Debug;

class UpdateApplicationDateValidatorTest extends TestCase
{
    private $validator;

    protected function setUp(): void
    {
        $mockDebug = $this->createMock(Debug::class);
        $this->validator = new UpdateApplicationDateValidator($mockDebug);
    }

    public function testValidateYear(): void
    {
        $this->assertSame(2024, $this->validator->validateYear(2024));
    }

    public function testValidateYearThrowsException(): void
    {
        $this->expectException(\Exception::class);
        $this->validator->validateYear(999);
    }

    public function testValidateSemester(): void
    {
        $this->assertSame('A', $this->validator->validateSemester('a'));
        $this->assertSame('B', $this->validator->validateSemester('B'));
    }

    public function testValidateSemesterThrowsException(): void
    {
        $this->expectException(\Exception::class);
        $this->validator->validateSemester('invalid');
    }

    public function testValidateObsAppID(): void
    {
        $this->assertSame(123, $this->validator->validateObsAppID('123'));
    }

    public function testValidateObsAppIDThrowsException(): void
    {
        $this->expectException(\Exception::class);
        $this->validator->validateObsAppID('invalid');
    }

    public function testValidateTimestamp(): void
    {
        $this->assertSame(1672444800, $this->validator->validateTimestamp(1672444800));
    }

    public function testValidateTimestampThrowsExceptionForInvalidTimestamp(): void
    {
        $this->expectException(\Exception::class);
        $this->validator->validateTimestamp(-1);
    }
}
*/
