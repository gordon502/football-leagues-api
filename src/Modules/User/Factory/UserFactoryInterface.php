<?php

namespace App\Modules\User\Factory;

use App\Modules\User\Model\UserCreatableInterface;
use App\Modules\User\Model\UserInterface;

interface UserFactoryInterface
{
    public function create(UserCreatableInterface $userCreatable, string $modelClass): UserInterface;
}
