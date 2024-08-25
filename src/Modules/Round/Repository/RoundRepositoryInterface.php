<?php

namespace App\Modules\Round\Repository;

use App\Common\Repository\HybridModelRepositoryInterface;
use App\Modules\Round\Model\RoundInterface;

interface RoundRepositoryInterface extends HybridModelRepositoryInterface
{
    public function findById(string $id): ?RoundInterface;
}
