<?php

namespace App\Modules\Team\Model;

interface TeamCreatableInterface
{
    public function getName(): string|null;

    public function getYearEstablished(): int|null;

    public function getColors(): string|null;

    public function getCountry(): string|null;

    public function getAddress(): string|null;

    public function getCity(): string|null;

    public function getPostalCode(): string|null;

    public function getSite(): string|null;

    public function getStadium(): string|null;

    public function getOrganizationalUnitId(): string|null;
}
