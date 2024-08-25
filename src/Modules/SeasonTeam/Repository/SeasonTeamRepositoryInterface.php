<?php

namespace App\Modules\SeasonTeam\Repository;

use App\Common\Repository\HybridModelRepositoryInterface;
use App\Modules\SeasonTeam\Model\SeasonTeamInterface;

interface SeasonTeamRepositoryInterface extends HybridModelRepositoryInterface
{
    public function findById(string $id): ?SeasonTeamInterface;
}
