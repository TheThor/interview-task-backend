<?php

namespace App\Modules\Invoices\Domain\Models\Casts;

use App\Modules\Invoices\Domain\Models\ValueObjects\Address as AddressValueObject;
use Illuminate\Database\Eloquent\Model;

class AddressCast
{
    /**
     * Cast the given value.
     *
     * @param $model
     * @param $key
     * @param $value
     * @param array $attributes
     * @return AddressValueObject
     */
    public function get($model, $key, $value, array $attributes) {
        return new AddressValueObject(
            $attributes['street'],
            $attributes['city'],
            $attributes['zip'],
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
        if (! $value instanceof AddressValueObject) {
            throw new InvalidArgumentException('The given value is not an Address instance.');
        }

        return [
            'street' => $value->getStreet(),
            'city' => $value->getCity(),
            'zip' => $value->getZip(),
        ];
    }
}
