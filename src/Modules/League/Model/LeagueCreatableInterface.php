<?php

namespace App\Modules\League\Model;

interface LeagueCreatableInterface
{
    public function getName(): string|null;

    public function isActive(): bool|null;

    public function getLevel(): int|null;

    public function getOrganizationalUnitId(): string|null;
}
