<?php

namespace App\Modules\Invoices\Api;

use App\Infrastructure\Controller;
use App\Modules\Approval\Api\ApprovalFacadeInterface;
use App\Modules\Invoices\Application\InvoiceServiceInterface;
use LogicException;

class InvoiceController extends Controller
{
    private InvoiceServiceInterface $invoiceService;

    public function __construct(
        InvoiceServiceInterface $invoiceService,
        ApprovalFacadeInterface $approvalFacade
    ) {
        $this->invoiceService = $invoiceService;
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
