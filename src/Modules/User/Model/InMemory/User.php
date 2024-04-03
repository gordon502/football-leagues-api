<?php

namespace App\Modules\User\Model\InMemory;

use App\Modules\User\Model\UserInterface;
use DateTime;
use DateTimeInterface;

class User implements UserInterface
{
    public function getId(): string|int
    {
        // TODO: Implement getId() method.
        return '';
    }

    public function getName(): string
    {
        // TODO: Implement getName() method.
        return '';
    }

    public function setName(string $name): static
    {
        // TODO: Implement setName() method.
        return $this;
    }

    public function getEmail(): string
    {
        // TODO: Implement getEmail() method.
        return '';
    }

    public function setEmail(string $email): static
    {
        // TODO: Implement setEmail() method.
        return $this;
    }

    public function getPassword(): string
    {
        // TODO: Implement getPassword() method.
        return '';
    }

    public function setPassword(string $password): static
    {
        // TODO: Implement setPassword() method.
        return $this;
    }

    public function getRole(): string
    {
        // TODO: Implement getRole() method.
        return '';
    }

    public function setRole(string $role): static
    {
        // TODO: Implement setRole() method.
        return $this;
    }

    public function getAvatar(): string|null
    {
        // TODO: Implement getAvatar() method.
        return null;
    }

    public function setAvatar(?string $avatar): static
    {
        // TODO: Implement setAvatar() method.
        return $this;
    }

    public function isBlocked(): bool
    {
        // TODO: Implement isBlocked() method.
        return false;
    }

    public function setBlocked(bool $blocked): static
    {
        // TODO: Implement setBlocked() method.
        return $this;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        // TODO: Implement getCreatedAt() method.
        return new DateTime();
    }

    public function getUpdatedAt(): DateTimeInterface
    {
        // TODO: Implement getUpdatedAt() method.
        return new DateTime();
    }

    public function getDeletedAt(): DateTimeInterface|null
    {
        // TODO: Implement getDeletedAt() method.
        return null;
    }
}
