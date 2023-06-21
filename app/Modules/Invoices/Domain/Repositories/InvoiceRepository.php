<?php

namespace App\Modules\Invoices\Domain\Repositories;


use App\Modules\Invoices\Domain\Models\Invoice;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\UuidInterface;

class InvoiceRepository implements InvoiceRepositoryInterface
{
    public function findById(UuidInterface $id): ?Invoice
    {
        return Invoice::find($id);
    }

    public function findByStatus(string $status): array
    {
        return Invoice::where('status', $status)->get()->toArray();
    }

    public function findByIdWithCompanyAndProducts(string $id): Builder|array|Collection|Model
    {
        return Invoice::with('company', 'productLines')->find($id);
    }

    public function findByCompanyAndStatus(string $companyId, string $status): array
    {
        return Invoice::where('company_id', $companyId)
            ->where('status', $status)
            ->get()
            ->toArray();
    }

    public function save(Invoice $invoice): Invoice
    {
        $invoice->save();

        return $invoice;
    }

    public function delete(Invoice $invoice): void
    {
        $invoice->delete();
    }
}
