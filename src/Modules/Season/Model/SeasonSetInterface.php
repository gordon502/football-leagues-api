<?php

namespace App\Modules\Season\Model;

use App\Modules\League\Model\LeagueInterface;

interface SeasonSetInterface
{
    public function setName(string $name): static;

    public function setActive(bool $active): static;

    public function setPeriod(string $period): static;

    public function setLeague(LeagueInterface $league): static;
}
