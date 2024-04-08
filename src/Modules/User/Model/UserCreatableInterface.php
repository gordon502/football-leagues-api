<?php

namespace App\Modules\User\Model;

interface UserCreatableInterface
{
    public function getEmail(): string;

    public function getName(): string;

    public function getPassword(): string;
}
