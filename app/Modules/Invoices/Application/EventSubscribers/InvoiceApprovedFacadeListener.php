<?php

namespace App\Modules\Invoices\Application\EventSubscribers;

use App\Domain\Enums\StatusEnum;
use App\Modules\Approval\Api\Events\EntityApproved;
use App\Modules\Invoices\Domain\Repositories\InvoiceRepositoryInterface;
use Illuminate\Contracts\Queue\ShouldQueue;

final readonly class InvoiceApprovedFacadeListener implements ShouldQueue
{
    private InvoiceRepositoryInterface $invoiceRepository;

    public function __construct(InvoiceRepositoryInterface $invoiceRepository)
    {
        $this->invoiceRepository = $invoiceRepository;
    }

    public function handle(EntityApproved $event): void
    {
        $entity = $this->invoiceRepository->findById($event->approvalDto->id);
        $entity->setStatus(StatusEnum::APPROVED);
        $this->invoiceRepository->save($entity);
    }
}
