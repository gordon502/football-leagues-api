<?php

namespace App\Modules\User\Factory\InMemory;

use App\Modules\User\Factory\UserFactoryInterface;
use App\Modules\User\Model\InMemory\User;
use App\Modules\User\Model\UserInterface;

class UserFactory implements UserFactoryInterface
{
    public function create(): UserInterface
    {
        return new User();
    }
}
