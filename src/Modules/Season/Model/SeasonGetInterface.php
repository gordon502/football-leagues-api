<?php

namespace App\Modules\Season\Model;

use App\Modules\League\Model\LeagueInterface;
use Doctrine\Common\Collections\Collection;

interface SeasonGetInterface
{
    public function getId(): string;

    public function getName(): string;

    public function isActive(): bool;

    public function getPeriod(): string;

    public function getLeague(): LeagueInterface;

    public function getSeasonTeams(): Collection;
}
