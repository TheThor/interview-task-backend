<?php

namespace App\Modules\Invoices\Infrastructure\Providers;

use App\Modules\Invoices\Application\InvoiceService;
use App\Modules\Invoices\Application\InvoiceServiceInterface;
use App\Modules\Invoices\Domain\Repositories\InvoiceRepository;
use App\Modules\Invoices\Domain\Repositories\InvoiceRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class InvoiceRegistrationProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(InvoiceRepositoryInterface::class, InvoiceRepository::class);
        $this->app->bind(InvoiceServiceInterface::class, InvoiceService::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot() {}
}
