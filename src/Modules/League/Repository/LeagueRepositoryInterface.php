<?php

namespace App\Modules\League\Repository;

use App\Common\Repository\HybridModelRepositoryInterface;
use App\Modules\League\Model\LeagueInterface;

interface LeagueRepositoryInterface extends HybridModelRepositoryInterface
{
    public function findById(string $id): ?LeagueInterface;
}
