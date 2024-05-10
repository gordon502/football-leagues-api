<?php

namespace App\Modules\Season\Model;

interface SeasonCreatableInterface
{
    public function getName(): string|null;

    public function isActive(): bool|null;

    public function getPeriod(): string|null;

    public function getLeagueId(): string|null;
}
