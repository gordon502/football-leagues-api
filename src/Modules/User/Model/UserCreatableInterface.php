<?php

namespace App\Modules\User\Model;

interface UserCreatableInterface
{
    public function getEmail(): string|null;

    public function getName(): string|null;

    public function getPassword(): string|null;
}
