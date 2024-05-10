<?php

namespace App\Modules\SeasonTeam\Model;

use App\Modules\Season\Model\SeasonInterface;
use App\Modules\Team\Model\TeamInterface;

interface SeasonTeamSetInterface
{
    public function setName(string $name): static;

    public function setTeam(TeamInterface $team): static;

    public function setSeason(SeasonInterface $season): static;
}
