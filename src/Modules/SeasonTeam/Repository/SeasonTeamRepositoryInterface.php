<?php

namespace App\Modules\SeasonTeam\Repository;

use App\Common\Repository\DeletableByIdInterface;
use App\Common\Repository\FindableByIdInterface;
use App\Modules\SeasonTeam\Model\SeasonTeamInterface;

interface SeasonTeamRepositoryInterface extends FindableByIdInterface, DeletableByIdInterface
{
    public function findById(string $id): ?SeasonTeamInterface;
}
