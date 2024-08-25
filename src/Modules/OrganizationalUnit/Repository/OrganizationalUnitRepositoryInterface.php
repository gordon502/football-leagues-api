<?php

namespace App\Modules\OrganizationalUnit\Repository;

use App\Common\Repository\HybridModelRepositoryInterface;
use App\Modules\OrganizationalUnit\Model\OrganizationalUnitInterface;

interface OrganizationalUnitRepositoryInterface extends HybridModelRepositoryInterface
{
    public function findById(string $id): ?OrganizationalUnitInterface;
}
