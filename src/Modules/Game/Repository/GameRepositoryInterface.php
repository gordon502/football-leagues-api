<?php

namespace App\Modules\Game\Repository;

use App\Common\Repository\DeletableByIdInterface;
use App\Common\Repository\FindableByIdInterface;
use App\Common\Repository\UpdateOneInterface;
use App\Modules\Game\Model\GameInterface;

interface GameRepositoryInterface extends FindableByIdInterface, DeletableByIdInterface, UpdateOneInterface
{
    public function findById(string $id): ?GameInterface;
}
