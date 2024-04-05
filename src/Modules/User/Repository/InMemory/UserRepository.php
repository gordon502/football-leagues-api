<?php

namespace App\Modules\User\Repository\InMemory;

use App\Modules\User\Model\InMemory\User;
use App\Modules\User\Model\UserInterface;
use App\Modules\User\Repository\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function findById(int|string $id): ?UserInterface
    {
        // TODO: in memory logic for finding a user by id
        if ($id == 1) {
            return new User();
        }

        return null;
    }
}
