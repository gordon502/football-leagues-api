<?php

namespace App\Modules\OrganizationalUnit\Model;

interface OrganizationalUnitCreatableInterface
{
    public function getName(): string|null;

    public function getCountry(): string|null;

    public function getAddress(): string|null;

    public function getCity(): string|null;

    public function getPostalCode(): string|null;

    public function getPhone(): string|null;
}
