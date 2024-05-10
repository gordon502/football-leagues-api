<?php

namespace App\Modules\Team\Model;

use App\Modules\OrganizationalUnit\Model\OrganizationalUnitInterface;

interface TeamSetInterface
{
    public function setName(string $name): static;

    public function setYearEstablished(int|null $yearEstablished): static;

    public function setColors(string|null $colors): static;

    public function setCountry(string|null $country): static;

    public function setAddress(string|null $address): static;

    public function setCity(string|null $city): static;

    public function setPostalCode(string|null $postalCode): static;

    public function setSite(string|null $site): static;

    public function setStadium(string|null $stadium): static;

    public function setOrganizationalUnit(OrganizationalUnitInterface $organizationalUnit): static;
}
