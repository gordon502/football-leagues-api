<?php

namespace App\Modules\League\Model;

use App\Modules\OrganizationalUnit\Model\OrganizationalUnitInterface;

interface LeagueSetInterface
{
    public function setName(string $name): static;

    public function setActive(bool $active): static;

    public function setLevel(int|null $level): static;

    public function setOrganizationalUnit(OrganizationalUnitInterface $organizationalUnit): static;
}
