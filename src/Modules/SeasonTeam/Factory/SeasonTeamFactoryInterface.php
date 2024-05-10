<?php

namespace App\Modules\SeasonTeam\Factory;

use App\Modules\SeasonTeam\Model\SeasonTeamCreatableInterface;
use App\Modules\SeasonTeam\Model\SeasonTeamInterface;

interface SeasonTeamFactoryInterface
{
    public function create(
        SeasonTeamCreatableInterface $seasonTeamCreatable,
        string $modelClass
    ): SeasonTeamInterface;
}
