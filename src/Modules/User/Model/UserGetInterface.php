<?php

namespace App\Modules\User\Model;

interface UserGetInterface
{
    public function getId(): string|int;

    public function getEmail(): string;

    public function getName(): string;

    public function getRole(): string;

    public function getAvatar(): string|null;

    public function isBlocked(): bool;
}
