<?php

namespace App\Modules\Game\Repository;

use App\Common\Repository\HybridModelRepositoryInterface;
use App\Modules\Game\Model\GameInterface;

interface GameRepositoryInterface extends HybridModelRepositoryInterface
{
    public function findById(string $id): ?GameInterface;
}
