<?php

namespace App\Modules\SeasonTeam\Model;

interface SeasonTeamCreatableInterface
{
    public function getName(): string|null;

    public function getTeamId(): string | null;

    public function getSeasonId(): string | null;
}
