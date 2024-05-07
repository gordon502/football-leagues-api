<?php

namespace App\Modules\League\Model;

interface LeagueUpdatableInterface
{
    public function getName(): string|null;

    public function isActive(): bool|null;

    public function getLevel(): int|null;

    public function getOrganizationalUnitId(): string|null;
}
