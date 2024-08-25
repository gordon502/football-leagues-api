<?php

namespace App\Modules\Season\Repository;

use App\Common\Repository\HybridModelRepositoryInterface;
use App\Modules\Season\Model\SeasonInterface;

interface SeasonRepositoryInterface extends HybridModelRepositoryInterface
{
    public function findById(string $id): ?SeasonInterface;
}
