<?php

namespace App\Modules\OrganizationalUnit\Model;

interface OrganizationalUnitGetInterface
{
    public function getId(): string;

    public function getName(): string;

    public function getCountry(): string;

    public function getAddress(): string;

    public function getCity(): string;

    public function getPostalCode(): string;

    public function getPhone(): string|null;
}
