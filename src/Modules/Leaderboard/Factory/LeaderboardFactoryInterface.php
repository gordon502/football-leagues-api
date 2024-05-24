<?php

namespace App\Modules\Leaderboard\Factory;

use App\Modules\Leaderboard\Model\LeaderboardCreatableInterface;
use App\Modules\Leaderboard\Model\LeaderboardInterface;

interface LeaderboardFactoryInterface
{
    public function create(
        LeaderboardCreatableInterface $leaderboardCreatable,
        string $modelClass
    ): LeaderboardInterface;
}
