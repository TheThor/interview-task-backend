<?php
declare(strict_types=1);

namespace App\Modules\Invoices\Domain;


namespace App\Modules\Invoices\Domain\Models;

use App\Modules\Invoices\Domain\Models\Casts\PriceCast;
use App\Modules\Invoices\Domain\Models\ValueObjects\Price;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\UuidInterface;

class Product extends Model
{
    protected $table = 'products';

    protected $guarded = [];

    public $incrementing = false;

    protected $keyType = 'string';

    private Price $price;

    protected $fillable = [
        'id',
        'name',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'id' => 'string',
        'price' => PriceCast::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): Price
    {
        return $this->price;
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): string
    {
        return $this->updated_at;
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }
}
