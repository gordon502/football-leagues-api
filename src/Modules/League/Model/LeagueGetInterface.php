<?php

namespace App\Modules\League\Model;

use App\Modules\OrganizationalUnit\Model\OrganizationalUnitGetInterface;
use Doctrine\Common\Collections\Collection;

interface LeagueGetInterface
{
    public function getId(): string;

    public function getName(): string;

    public function isActive(): bool;

    public function getLevel(): int|null;

    public function getOrganizationalUnit(): OrganizationalUnitGetInterface;

    public function getSeasons(): Collection;
}
