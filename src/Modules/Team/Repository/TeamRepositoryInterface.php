<?php

namespace App\Modules\Team\Repository;

use App\Common\Repository\HybridModelRepositoryInterface;
use App\Modules\Team\Model\TeamInterface;

interface TeamRepositoryInterface extends HybridModelRepositoryInterface
{
    public function findById(string $id): ?TeamInterface;
}
