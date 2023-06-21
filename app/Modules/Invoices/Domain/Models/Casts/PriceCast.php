<?php

namespace App\Modules\Invoices\Domain\Models\Casts;

use App\Modules\Invoices\Domain\Models\ValueObjects\Price as PriceValueObject;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class PriceCast
{
    /**
     * Cast the given value.
     *
     * @param $model
     * @param $key
     * @param $value
     * @param array $attributes
     *
     * @return PriceValueObject
     */
    public function get($model, $key, $value, array $attributes) {
        return new PriceValueObject(
            $attributes['price'],
            $attributes['currency']
        );
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     * @return array<string, string>
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): array
    {
        if (! $value instanceof PriceValueObject) {
            throw new InvalidArgumentException('The given value is not an Address instance.');
        }

        return [
            'price' => $value->getValue(),
            'currency' => $value->getCurrency(),
        ];
    }
}
