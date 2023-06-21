<?php

namespace Tests\TestUtils;

use App\Domain\Enums\StatusEnum;
use App\Modules\Invoices\Domain\Models\Invoice;
use Illuminate\Support\Carbon;
use Ramsey\Uuid\Uuid;

class InvoiceModelUtil
{
    public static function createValidApprovedInvoice() : Invoice
    {
        $invoice = new Invoice();
        $invoice->setId(Uuid::uuid4());
        $invoice->setNumber('INV-2023-001');
        $invoice->setDate(Carbon::now());
        $invoice->setDueDate(Carbon::now()->addDays(30));
        $invoice->setCompanyId(Uuid::uuid4());
        $invoice->setStatus(StatusEnum::APPROVED);

        return $invoice;
    }

    public static function createValidRejectedInvoice() : Invoice
    {
        $invoice = new Invoice();
        $invoice->setId(Uuid::uuid4());
        $invoice->setNumber('INV-2023-001');
        $invoice->setDate(Carbon::now());
        $invoice->setDueDate(Carbon::now()->addDays(30));
        $invoice->setCompanyId(Uuid::uuid4());
        $invoice->setStatus(StatusEnum::REJECTED);

        return $invoice;
    }

    public static function createValidDraftInvoice() : Invoice
    {
        $invoice = new Invoice();
        $invoice->setId(Uuid::uuid4());
        $invoice->setNumber('INV-2023-001');
        $invoice->setDate(Carbon::now());
        $invoice->setDueDate(Carbon::now()->addDays(30));
        $invoice->setCompanyId(Uuid::uuid4());
        $invoice->setStatus(StatusEnum::DRAFT);

        return $invoice;
    }

}
