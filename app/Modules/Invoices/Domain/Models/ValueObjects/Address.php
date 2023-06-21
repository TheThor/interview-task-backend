<?php

namespace App\Modules\Invoices\Domain\Models\ValueObjects;

final class Address
{
    private string $street;

    private string $city;

    private string $zip;

    public function __construct(string $street, string $city, string $zip)
    {
        $this->street = $street;
        $this->city = $city;
        $this->zip = $zip;
    }

    /**
     * @return string
     */
    public function getStreet(): string
    {
        return $this->street;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @return string
     */
    public function getZip(): string
    {
        return $this->zip;
    }
}
