<?php

declare(strict_types=1);

namespace Tests\classes\models\proposals;

use App\models\proposals\UpdateApplicationDateModel;
use App\services\database\troublelog\read\ObsAppService as DbRead;
use App\services\database\troublelog\write\ObsAppService as DbWrite;
use App\core\common\Debug;
use Mockery;
use PHPUnit\Framework\TestCase;

class UpdateApplicationDateModelTest extends TestCase
{
    private $debugMock;
    private $dbReadMock;
    private $dbWriteMock;
    private $model;

    protected function setUp(): void
    {
        $this->debugMock = Mockery::mock(Debug::class);
        $this->dbReadMock = Mockery::mock(DbRead::class);
        $this->dbWriteMock = Mockery::mock(DbWrite::class);
        $this->model = new UpdateApplicationDateModel($this->debugMock, $this->dbReadMock, $this->dbWriteMock);
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function testFetchSemesterDataReturnsData(): void
    {
        $this->dbReadMock->shouldReceive('fetchSemesterProposalListingFormData')
            ->once()
            ->with(2023, 'A')
            ->andReturn([['proposal_id' => 1, 'title' => 'Sample Proposal']]);

        $result = $this->model->fetchSemesterData(2023, 'A');
        $this->assertNotEmpty($result);
    }

    public function testUpdateProposalReturnsSuccessMessage(): void
    {
        $this->dbReadMock->shouldReceive('fetchProposalListingFormData')
            ->once()
            ->with(123)
            ->andReturn([['creationDate' => 1672531200]]);

        $this->dbWriteMock->shouldReceive('modifyProposalCreationDate')
            ->once()
            ->with(123, 1672531300)
            ->andReturn(1);

        $result = $this->model->updateProposal(123, 1672531300);
        $this->assertEquals('Successfully updated timestamp.', $result);
    }
}

/*
<?php

declare(strict_types=1);

namespace Tests\classes\models\proposals;

use PHPUnit\Framework\TestCase;
use App\models\proposals\UpdateApplicationDateModel;
use App\core\common\Debug;
use App\services\database\troublelog\read\ObsAppService as DbRead;
use App\services\database\troublelog\write\ObsAppService as DbWrite;

class UpdateApplicationDateModelTest extends TestCase
{
    private $model;
    private $mockDebug;
    private $mockDbRead;
    private $mockDbWrite;

    protected function setUp(): void
    {
        $this->mockDebug = $this->createMock(Debug::class);
        $this->mockDbRead = $this->createMock(DbRead::class);
        $this->mockDbWrite = $this->createMock(DbWrite::class);

        $this->model = new UpdateApplicationDateModel($this->mockDebug, $this->mockDbRead, $this->mockDbWrite);
    }

    public function testFetchSemesterData(): void
    {
        $this->mockDbRead
            ->expects($this->once())
            ->method('fetchSemesterProposalListingFormData')
            ->with(2024, 'A')
            ->willReturn([['data']]);

        $result = $this->model->fetchSemesterData(2024, 'A');
        $this->assertSame([['data']], $result);
    }

    public function testUpdateProposal(): void
    {
        $this->mockDbRead
            ->expects($this->once())
            ->method('fetchProposalListingFormData')
            ->willReturn([['creationDate' => 123456789]]);

        $this->mockDbWrite
            ->expects($this->once())
            ->method('modifyProposalCreationDate')
            ->willReturn(1);

        $result = $this->model->updateProposal(1, 987654321);
        $this->assertSame('Successfully updated timestamp.', $result);
    }

    public function testFetchProposalData(): void
    {
        $this->mockDbRead
            ->expects($this->once())
            ->method('fetchProposalListingFormData')
            ->with(123)
            ->willReturn([['proposal' => 'data']]);

        $result = $this->model->fetchProposalData(123);
        $this->assertSame([['proposal' => 'data']], $result);
    }

    public function testUpdateProposalDoesNotModifyIfTimestampIsSame(): void
    {
        $this->mockDbRead
            ->expects($this->once())
            ->method('fetchProposalListingFormData')
            ->with(123)
            ->willReturn([['creationDate' => 1672444800]]);

        $this->mockDbWrite
            ->expects($this->never())
            ->method('modifyProposalCreationDate');

        $result = $this->model->updateProposal(123, 1672444800);
        $this->assertSame('No changes made. The timestamp is already up to date.', $result);
    }
}
*/
