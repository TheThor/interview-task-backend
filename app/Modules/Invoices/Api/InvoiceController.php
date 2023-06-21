<?php

namespace App\Modules\Invoices\Api;

use App\Domain\Enums\StatusEnum;
use App\Infrastructure\Controller;
use App\Modules\Approval\Api\ApprovalFacadeInterface;
use App\Modules\Approval\Api\Dto\ApprovalDto;
use App\Modules\Invoices\Application\InvoiceServiceInterface;
use http\Env\Response;
use LogicException;
use Ramsey\Uuid\Uuid;

class InvoiceController extends Controller
{
    private InvoiceServiceInterface $invoiceService;
    private ApprovalFacadeInterface $approvalFacade;

    public function __construct(
        InvoiceServiceInterface $invoiceService,
        ApprovalFacadeInterface $approvalFacade
    ) {
        $this->invoiceService = $invoiceService;
        $this->approvalFacade = $approvalFacade;
    }

    public function show($id)
    {
        $invoice = $this->invoiceService->findByIdWithCompanyAndProducts($id);

        return response()->json($invoice);
    }

    public function approve($id)
    {
        try {
            $this->invoiceService->approveInvoice($id);
        } catch (LogicException $exception) {
            return response()->json(['error' => $exception->getMessage()], 400);
        }


        return response()->json(['message' => 'Invoice approved']);
    }

    public function reject($id)
    {
        try {
            $this->invoiceService->approveInvoice($id);
        } catch (LogicException $exception) {
            return response()->json(['error' => $exception->getMessage()], 400);
        }

        return response()->json(['message' => 'Invoice rejected']);
    }
}
