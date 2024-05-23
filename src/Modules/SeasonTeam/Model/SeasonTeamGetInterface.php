<?php

namespace App\Modules\SeasonTeam\Model;

use App\Modules\Season\Model\SeasonInterface;
use App\Modules\Team\Model\TeamInterface;

interface SeasonTeamGetInterface
{
    public function getId(): string;

    public function getName(): string;

    public function getTeam(): TeamInterface;

    public function getSeason(): SeasonInterface;

    public function getGamesAsTeam1(): Collection;

    public function getGamesAsTeam2(): Collection;
}
