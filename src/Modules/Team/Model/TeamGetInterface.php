<?php

namespace App\Modules\Team\Model;

use App\Modules\OrganizationalUnit\Model\OrganizationalUnitInterface;
use Doctrine\Common\Collections\Collection;

interface TeamGetInterface
{
    public function getId(): string;

    public function getName(): string;

    public function getYearEstablished(): int|null;

    public function getColors(): string|null;

    public function getCountry(): string|null;

    public function getAddress(): string|null;

    public function getCity(): string|null;

    public function getPostalCode(): string|null;

    public function getSite(): string|null;

    public function getStadium(): string|null;

    public function getOrganizationalUnit(): OrganizationalUnitInterface;

    public function getSeasonTeams(): Collection;
}
