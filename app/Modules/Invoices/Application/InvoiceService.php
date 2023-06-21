<?php

namespace App\Modules\Invoices\Application;

use App\Modules\Approval\Api\ApprovalFacadeInterface;
use App\Modules\Approval\Api\Dto\ApprovalDto;
use App\Modules\Invoices\Domain\Models\Invoice;
use App\Modules\Invoices\Domain\Repositories\InvoiceRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class InvoiceService implements InvoiceServiceInterface
{
    private InvoiceRepositoryInterface $invoiceRepository;
    private ApprovalFacadeInterface $approvalFacade;

    public function __construct(
        InvoiceRepositoryInterface $invoiceRepository,
        ApprovalFacadeInterface $approvalFacade
    ) {
        $this->invoiceRepository = $invoiceRepository;
        $this->approvalFacade = $approvalFacade;
    }

    public function findById($id)
    {
        if (empty($id)) {
            throw new InvalidArgumentException("To find an invoice an ID is required.");
        }
        return $this->invoiceRepository->findById($id);
    }

    public function approveInvoice(string $id): true
    {
        if (empty($id)) {
            throw new InvalidArgumentException("To approve an invoice an ID is required.");
        }
        $dto = $this->getApprovalDto($id);

        return $this->approvalFacade->approve($dto);
    }

    public function rejectInvoice(string $id): true
    {
        if (empty($id)) {
            throw new InvalidArgumentException("To reject an invoice an ID is required.");
        }
        $dto = $this->getApprovalDto($id);

        return $this->approvalFacade->reject($dto);
    }

    /**
     * @param $id
     * @return array|Builder|Collection|Model
     */
    public function findByIdWithCompanyAndProducts($id): Model|Collection|Builder|array
    {
        if (empty($id)) {
            throw new InvalidArgumentException("To find an invoice, with company and product lines, an ID is required.");
        }
        return $this->invoiceRepository->findByIdWithCompanyAndProducts($id);
    }

    /**
     * @param string $id
     * @return ApprovalDto
     */
    public function getApprovalDto(string $id): ApprovalDto
    {
        if (empty($id)) {
            throw new \InvalidArgumentException("To get an approval DTO, an ID is required.");
        }
        $uuid = Uuid::fromString($id);
        /** @var Invoice $invoice */
        $invoice = $this->findById($uuid);
        if ($invoice === null) {
            throw new NotFoundResourceException("Invoice not found for the provided ID.");
        }
        return new ApprovalDto($uuid, $invoice->getStatus(), 'invoice');
    }
}
