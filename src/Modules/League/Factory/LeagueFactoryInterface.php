<?php

namespace App\Modules\League\Factory;

use App\Modules\League\Model\LeagueCreatableInterface;
use App\Modules\League\Model\LeagueInterface;

interface LeagueFactoryInterface
{
    public function create(
        LeagueCreatableInterface $leagueCreatable,
        string $modelClass
    ): LeagueInterface;
}
