<?php

namespace App\Modules\Season\Repository;

use App\Common\Repository\DeletableByIdInterface;
use App\Common\Repository\FindableByIdInterface;
use App\Modules\Season\Model\SeasonInterface;

interface SeasonRepositoryInterface extends FindableByIdInterface, DeletableByIdInterface
{
    public function findById(string $id): ?SeasonInterface;
}
