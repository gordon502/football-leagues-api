<?php

namespace App\Modules\Round\Repository;

use App\Common\Repository\DeletableByIdInterface;
use App\Common\Repository\FindableByIdInterface;
use App\Modules\Round\Model\RoundInterface;

interface RoundRepositoryInterface extends FindableByIdInterface, DeletableByIdInterface
{
    public function findById(string $id): ?RoundInterface;
}
