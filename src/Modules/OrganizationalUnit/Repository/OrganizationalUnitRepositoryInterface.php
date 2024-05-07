<?php

namespace App\Modules\OrganizationalUnit\Repository;

use App\Common\Repository\DeletableByIdInterface;
use App\Common\Repository\FindableByIdInterface;
use App\Modules\OrganizationalUnit\Model\OrganizationalUnitInterface;

interface OrganizationalUnitRepositoryInterface extends FindableByIdInterface, DeletableByIdInterface
{
    public function findById(string $id): ?OrganizationalUnitInterface;
}
