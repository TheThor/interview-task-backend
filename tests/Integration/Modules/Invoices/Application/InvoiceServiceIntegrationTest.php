<?php

namespace Tests\Integration\Modules\Invoices\Application;

use App\Domain\Enums\StatusEnum;
use App\Modules\Approval\Api\ApprovalFacadeInterface;
use App\Modules\Approval\Api\Dto\ApprovalDto;
use App\Modules\Invoices\Application\InvoiceService;
use App\Modules\Invoices\Domain\Models\Company;
use App\Modules\Invoices\Domain\Models\Invoice;
use App\Modules\Invoices\Domain\Repositories\InvoiceRepositoryInterface;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use LogicException;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Translation\Exception\NotFoundResourceException;
use Tests\TestCase;

class InvoiceServiceIntegrationTest extends TestCase
{
    use DatabaseMigrations;

    private InvoiceService $invoiceService;

    protected function setUp(): void
    {
        parent::setUp();

        $invoiceRepository = $this->app->make(InvoiceRepositoryInterface::class);
        $approvalFacade = $this->app->make(ApprovalFacadeInterface::class);

        $this->invoiceService = new InvoiceService(
            $invoiceRepository,
            $approvalFacade
        );

        // Create and persist a company record
        $this->company = new Company();
        $this->company->setAttribute('id', Uuid::uuid4()->toString());
        $this->company->setAttribute('name', 'Company XYZ');
        $this->company->setAttribute('street', '123 Main Street');
        $this->company->setAttribute('city', 'City');
        $this->company->setAttribute('zip', '12345');
        $this->company->setAttribute('phone', '123-456-7890');
        $this->company->setAttribute('email', 'company@example.com');
        $this->company->setAttribute('created_at', now());
        $this->company->setAttribute('updated_at', now());
        $this->company->save();

        // Create and persist an Invoice object
        $this->invoice = new Invoice();
        $this->invoice->setAttribute('id', Uuid::uuid4()->toString());
        $this->invoice->setAttribute('number', 'INV-123');
        $this->invoice->setAttribute('date', now());
        $this->invoice->setAttribute('due_date', now());
        $this->invoice->setAttribute('company_id', $this->company->id);
        $this->invoice->setAttribute('status', StatusEnum::DRAFT);
        $this->invoice->setAttribute('created_at', now());
        $this->invoice->setAttribute('updated_at', now());
        $this->invoice->save();
    }

    protected function tearDown(): void
    {
        $this->invoice->delete();
        $this->company->delete();

        parent::tearDown();
    }

    public function testApproveInvoice_WithValidDto_ApprovesInvoice(): void
    {
        // Act
        $result = $this->invoiceService->approveInvoice($this->invoice->id);

        // Assert
        $this->assertTrue($result);
    }

    public function testApproveInvoice_WithEmptyId_ThrowsInvalidArgumentException(): void
    {
        // Act & Assert
        $this->expectException(InvalidArgumentException::class);
        $this->invoiceService->approveInvoice('');
    }

    public function testApproveInvoice_WhenApprovalFacadeThrowsLogicException_ThrowsLogicException(): void
    {
        // Arrange
        $invoice = Invoice::firstOrFail();
        $invoiceId = $invoice->id;
        $invoice->setStatus(StatusEnum::REJECTED);
        $invoice->save();

        // Act & Assert
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('approval status is already assigned');

        $this->invoiceService->approveInvoice($invoiceId);
    }
}
