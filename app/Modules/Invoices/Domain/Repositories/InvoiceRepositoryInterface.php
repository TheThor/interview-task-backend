<?php

namespace App\Modules\Invoices\Domain\Repositories;

use Ramsey\Uuid\UuidInterface;

interface InvoiceRepositoryInterface
{

    public function findById(UuidInterface $id);
    public function findByIdWithCompanyAndProducts(string $id);

}
