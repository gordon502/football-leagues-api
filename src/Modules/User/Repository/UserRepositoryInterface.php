<?php

namespace App\Modules\User\Repository;

use App\Common\Repository\FindableByIdInterface;
use App\Modules\User\Model\UserInterface;

interface UserRepositoryInterface extends FindableByIdInterface
{
    public function findById(string $id): ?UserInterface;
}
