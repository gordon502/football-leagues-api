<?php

namespace App\Modules\User\Model;

interface UserSetInterface
{
    public function setEmail(string $email): static;

    public function setName(string $name): static;

    public function setPassword(string $password): static;

    public function setRole(string $role): static;

    public function setAvatar(string|null $avatar): static;

    public function setBlocked(bool $blocked): static;
}
