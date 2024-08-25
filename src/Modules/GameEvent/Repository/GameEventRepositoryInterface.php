<?php

namespace App\Modules\GameEvent\Repository;

use App\Common\Repository\HybridModelRepositoryInterface;
use App\Modules\GameEvent\Model\GameEventInterface;

interface GameEventRepositoryInterface extends HybridModelRepositoryInterface
{
    public function findById(string $id): ?GameEventInterface;
}
