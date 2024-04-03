<?php

namespace App\Modules\User\Factory;

use App\Common\Factory\SimpleFactoryInterface;
use App\Modules\User\Model\UserInterface;

interface UserFactoryInterface extends SimpleFactoryInterface
{
    public function create(): UserInterface;
}
