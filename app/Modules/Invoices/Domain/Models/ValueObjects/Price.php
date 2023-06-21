<?php

namespace App\Modules\Invoices\Domain\Models\ValueObjects;

use InvalidArgumentException;

final class Price
{
    private float $value;

    private string $currency;

    public function __construct(float $value, string $currency = 'usd')
    {
        if ($value < 0) {
            throw new InvalidArgumentException("Price value can't be lower than 0.");
        }
        if (!in_array($currency, ['usd', 'eur'])) {
            throw new InvalidArgumentException("Currency not available.");
        }

        $this->value = $value;
        $this->currency = $currency;
    }

    /**
     * @return float
     */
    public function getValue(): float
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }
}
