<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Domain\Models;

use App\Modules\Invoices\Domain\Models\Casts\AddressCast;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    protected $table = 'companies';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'phone',
        'email',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'address' => AddressCast::class,
    ];

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }
}
