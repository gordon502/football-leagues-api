<?php

namespace App\Modules\OrganizationalUnit\Model;

interface OrganizationalUnitSetInterface
{
    public function setName(string $name): static;

    public function setCountry(string $country): static;

    public function setAddress(string $address): static;

    public function setCity(string $city): static;

    public function setPostalCode(string $postalCode): static;

    public function setPhone(string|null $phone): static;
}
