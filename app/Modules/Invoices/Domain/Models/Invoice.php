<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Domain\Models;

use App\Domain\Enums\StatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Ramsey\Uuid\UuidInterface;

class Invoice extends Model
{
    protected $table = 'invoices';

    protected $guarded = [];

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'number',
        'date',
        'due_date',
        'company_id',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'id' => 'string',
        'date' => 'date',
        'status' => StatusEnum::class,
        'due_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function getId(): UuidInterface
    {
        return $this->getAttribute('id');
    }

    public function getNumber(): string
    {
        return $this->getAttribute('number');
    }

    public function getDate(): Carbon
    {
        return $this->getAttribute('date');
    }

    public function getDueDate(): Carbon
    {
        return $this->getAttribute('due_date');
    }

    public function getCompanyId(): UuidInterface
    {
        return $this->getAttribute('company_id');
    }

    public function getStatus(): StatusEnum
    {
        return is_string($this->getAttribute('status')) ? StatusEnum::tryFrom($this->getAttribute('status')) : $this->getAttribute('status');
    }

    public function setStatus($value)
    {
        $this->status = $value;
    }

    public function getCreatedAt(): Carbon
    {
        return $this->getAttribute('created_at');
    }

    public function getUpdatedAt(): Carbon
    {
        return $this->getAttribute('updated_at');
    }

    // Relationships

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function productLines()
    {
        return $this->belongsToMany(Product::class, 'invoice_product_lines', 'invoice_id', 'product_id')
            ->withPivot('quantity');
    }
}
