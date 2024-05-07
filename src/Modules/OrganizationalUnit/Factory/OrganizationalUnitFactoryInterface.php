<?php

namespace App\Modules\OrganizationalUnit\Factory;

use App\Modules\OrganizationalUnit\Model\OrganizationalUnitCreatableInterface;
use App\Modules\OrganizationalUnit\Model\OrganizationalUnitInterface;

interface OrganizationalUnitFactoryInterface
{
    public function create(
        OrganizationalUnitCreatableInterface $userCreatable,
        string $modelClass
    ): OrganizationalUnitInterface;
}
