<?php

namespace App\Modules\User\Model;

interface UserUpdatableInterface
{
    public function getEmail(): string|null;

    public function getName(): string|null;

    public function getPassword(): string|null;

    public function getRole(): string|null;

    public function getAvatar(): string|null;

    public function isBlocked(): bool|null;
}
