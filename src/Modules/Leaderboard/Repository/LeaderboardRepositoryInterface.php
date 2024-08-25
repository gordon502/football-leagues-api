<?php

namespace App\Modules\Leaderboard\Repository;

use App\Common\Repository\HybridModelRepositoryInterface;
use App\Modules\Leaderboard\Model\LeaderboardInterface;
use App\Modules\SeasonTeam\Model\SeasonTeamInterface;

interface LeaderboardRepositoryInterface extends HybridModelRepositoryInterface
{
    public function findById(string $id): ?LeaderboardInterface;

    public function findAllForSeasonTeam(SeasonTeamInterface $seasonTeam): array;
}
