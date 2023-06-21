<?php

namespace Tests\Unit\Modules\Invoices\Application;

use App\Domain\Enums\StatusEnum;
use App\Modules\Approval\Api\ApprovalFacadeInterface;
use App\Modules\Approval\Api\Dto\ApprovalDto;
use App\Modules\Invoices\Application\InvoiceService;
use App\Modules\Invoices\Domain\Models\Invoice;
use App\Modules\Invoices\Domain\Repositories\InvoiceRepositoryInterface;
use InvalidArgumentException;
use LogicException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class InvoiceServiceTests extends TestCase
{
    private InvoiceRepositoryInterface $invoiceRepository;
    private ApprovalFacadeInterface $approvalFacade;
    private InvoiceService $invoiceService;
    private $invoiceMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->invoiceRepository = $this->createMock(InvoiceRepositoryInterface::class);
        $this->approvalFacade = $this->createMock(ApprovalFacadeInterface::class);

        $this->invoiceService = new InvoiceService(
            $this->invoiceRepository,
            $this->approvalFacade
        );
        $this->invoiceMock = $this->getMockBuilder(Invoice::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testApproveInvoice_WithValidDto_ApprovesInvoice(): void
    {
        // Arrange
        $invoiceId = Uuid::uuid4();
        $this->invoiceMock->expects($this->any())
            ->method('getId')
            ->willReturn($invoiceId);
        $this->invoiceMock->expects($this->any())
            ->method('getStatus')
            ->willReturn(StatusEnum::DRAFT);

        $approvalDto = new ApprovalDto($invoiceId, StatusEnum::DRAFT, 'invoice');

        $this->invoiceRepository->expects($this->once())
            ->method('findById')
            ->with($this->equalTo($invoiceId))
            ->willReturn($this->invoiceMock);

        $this->approvalFacade->expects($this->once())
            ->method('approve')
            ->with($this->equalTo($approvalDto));

        // Act
        $result = $this->invoiceService->approveInvoice($invoiceId->toString());

        // Assert
        $this->assertTrue($result);
    }

    public function testRejectInvoice_WithValidDto_ApprovesInvoice(): void
    {
        // Arrange
        $invoiceId = Uuid::uuid4();

        $this->invoiceMock->expects($this->any())
            ->method('getId')
            ->willReturn($invoiceId);
        $this->invoiceMock->expects($this->any())
            ->method('getStatus')
            ->willReturn(StatusEnum::DRAFT);
        $approvalDto = new ApprovalDto($invoiceId, StatusEnum::DRAFT, 'invoice');


        $this->invoiceRepository->expects($this->once())
            ->method('findById')
            ->with($this->equalTo($invoiceId))
            ->willReturn($this->invoiceMock);

        $this->approvalFacade->expects($this->once())
            ->method('reject')
            ->with($this->equalTo($approvalDto));

        // Act
        $result = $this->invoiceService->rejectInvoice($invoiceId->toString());

        // Assert
        $this->assertTrue($result);
    }

    public function testApproveInvoice_WithEmptyId_ThrowsInvalidArgumentException(): void
    {
        // Arrange
        $this->expectException(InvalidArgumentException::class);

        // Act && Assert
        $this->invoiceService->approveInvoice('');
    }

    public function testApproveInvoice_WhenApprovalFacadeThrowsLogicException_ThrowsLogicException(): void
    {
        // Arrange
        $invoiceId = Uuid::uuid4();

        $this->invoiceMock->expects($this->any())
            ->method('getId')
            ->willReturn($invoiceId);
        $this->invoiceMock->expects($this->any())
            ->method('getStatus')
            ->willReturn(StatusEnum::DRAFT);

        $this->invoiceRepository->expects($this->once())
            ->method('findById')
            ->with($this->equalTo($invoiceId))
            ->willReturn($this->invoiceMock);

        $this->approvalFacade->expects($this->once())
            ->method('approve')
            ->willThrowException(new LogicException('approval status is already assigned'));

        $this->expectException(LogicException::class);

        // Act && Assert
        $this->invoiceService->approveInvoice($invoiceId->toString());
    }

    public function testRejectInvoice_WithEmptyId_ThrowsInvalidArgumentException(): void
    {
        // Arrange
        $this->expectException(InvalidArgumentException::class);

        // Act && Assert
        $this->invoiceService->rejectInvoice('');
    }

    public function testRejectInvoice_WhenApprovalFacadeThrowsLogicException_ThrowsLogicException(): void
    {
        // Arrange
        // Arrange
        $invoiceId = Uuid::uuid4();

        $this->invoiceMock->expects($this->any())
            ->method('getId')
            ->willReturn($invoiceId);
        $this->invoiceMock->expects($this->any())
            ->method('getStatus')
            ->willReturn(StatusEnum::DRAFT);

        $this->invoiceRepository->expects($this->once())
            ->method('findById')
            ->with($this->equalTo($invoiceId))
            ->willReturn($this->invoiceMock);

        $this->approvalFacade->expects($this->once())
            ->method('reject')
            ->willThrowException(new LogicException('reject status is already assigned'));

        $this->expectException(LogicException::class);

        // Act && Assert
        $this->invoiceService->rejectInvoice($invoiceId->toString());
    }

    public function testFindById_WithValidId_ReturnsInvoice(): void
    {
        // Arrange
        $invoiceId = Uuid::uuid4();
        $invoiceMock = $this->getMockBuilder(Invoice::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->invoiceMock->expects($this->any())
            ->method('getId')
            ->willReturn($invoiceId);

        $this->invoiceRepository->expects($this->once())
            ->method('findById')
            ->with($this->equalTo($invoiceId))
            ->willReturn($invoiceMock);

        // Act
        $result = $this->invoiceService->findById($invoiceId);

        // Assert
        $this->assertEquals($invoiceMock, $result);
    }

    public function testFindById_WithEmptyId_ThrowsInvalidArgumentException(): void
    {
        // Arrange
        $this->expectException(InvalidArgumentException::class);

        // Act && Assert
        $this->invoiceService->findById('');
    }

    public function testFindByIdWithCompanyAndProducts_WithValidId_ReturnsInvoiceWithCompanyAndProducts(): void
    {
        // Arrange
        $invoiceId = Uuid::uuid4();
        $invoiceMock = $this->getMockBuilder(Invoice::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->invoiceMock->expects($this->any())
            ->method('getId')
            ->willReturn($invoiceId);

        $this->invoiceRepository->expects($this->once())
            ->method('findByIdWithCompanyAndProducts')
            ->willReturn($invoiceMock);

        // Act
        $result = $this->invoiceService->findByIdWithCompanyAndProducts($invoiceId->toString());

        // Assert
        $this->assertEquals($invoiceMock, $result);
    }

    public function testFindByIdWithCompanyAndProducts_WithEmptyId_ThrowsInvalidArgumentException(): void
    {
        // Arrange
        $this->expectException(InvalidArgumentException::class);

        // Act && Assert
        $this->invoiceService->findByIdWithCompanyAndProducts('');
    }

    public function testGetApprovalDto_WithValidId_ReturnsApprovalDto(): void
    {
        // Arrange
        $invoiceId = Uuid::uuid4();
        $invoiceMock = $this->getMockBuilder(Invoice::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->invoiceMock->expects($this->any())
            ->method('getId')
            ->willReturn($invoiceId);
        $invoiceMock->expects($this->any())
            ->method('getStatus')
            ->willReturn(StatusEnum::DRAFT);

        $this->invoiceRepository->expects($this->once())
            ->method('findById')
            ->with($this->equalTo($invoiceId))
            ->willReturn($invoiceMock);

        // Act
        $result = $this->invoiceService->getApprovalDto($invoiceId->toString());

        // Assert
        $this->assertInstanceOf(ApprovalDto::class, $result);
        $this->assertEquals($invoiceId, $result->id);
        $this->assertEquals(StatusEnum::DRAFT, $result->status);
    }

    public function testGetApprovalDto_WithInvalidId_ThrowsNotFoundResourceException(): void
    {
        // Arrange
        $invoiceId = Uuid::uuid4();

        $this->invoiceRepository->expects($this->once())
            ->method('findById')
            ->with($this->equalTo($invoiceId))
            ->willReturn(null);

        $this->expectException(NotFoundResourceException::class);

        // Act && Assert
        $this->invoiceService->getApprovalDto($invoiceId->toString());
    }

    public function testGetApprovalDto_WithEmptyId_ThrowsInvalidArgumentException(): void
    {
        // Arrange
        $this->expectException(InvalidArgumentException::class);

        // Act && Assert
        $this->invoiceService->getApprovalDto('');
    }

}
