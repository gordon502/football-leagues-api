<?php

namespace App\Modules\GameEvent\Repository;

use App\Common\Repository\DeletableByIdInterface;
use App\Common\Repository\FindableByIdInterface;
use App\Common\Repository\UpdateOneInterface;
use App\Modules\GameEvent\Model\GameEventInterface;

interface GameEventRepositoryInterface extends FindableByIdInterface, DeletableByIdInterface, UpdateOneInterface
{
    public function findById(string $id): ?GameEventInterface;
}
