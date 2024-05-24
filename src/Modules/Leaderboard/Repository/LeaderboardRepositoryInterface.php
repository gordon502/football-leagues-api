<?php

namespace App\Modules\Leaderboard\Repository;

use App\Common\Repository\DeletableByIdInterface;
use App\Common\Repository\FindableByIdInterface;
use App\Common\Repository\UpdateOneInterface;
use App\Modules\Leaderboard\Model\LeaderboardInterface;
use App\Modules\SeasonTeam\Model\SeasonTeamInterface;

interface LeaderboardRepositoryInterface extends FindableByIdInterface, DeletableByIdInterface, UpdateOneInterface
{
    public function findById(string $id): ?LeaderboardInterface;

    public function findAllForSeasonTeam(SeasonTeamInterface $seasonTeam): array;
}
