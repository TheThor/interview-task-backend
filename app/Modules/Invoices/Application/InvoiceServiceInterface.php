<?php

namespace App\Modules\Invoices\Application;

interface InvoiceServiceInterface
{
    public function findById($id);
    public function approveInvoice(string $id);
    public function rejectInvoice(string $id);
    public function findByIdWithCompanyAndProducts($id);
}
