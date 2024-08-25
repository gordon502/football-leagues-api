<?php

namespace App\Modules\User\Repository;

use App\Common\Repository\HybridModelRepositoryInterface;
use App\Modules\User\Model\UserInterface;

interface UserRepositoryInterface extends HybridModelRepositoryInterface
{
    public function findById(string $id): ?UserInterface;

    public function findByEmail(string $email): ?UserInterface;
}
