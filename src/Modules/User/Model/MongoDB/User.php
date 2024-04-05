<?php

namespace App\Modules\User\Model\MongoDB;

use App\Modules\User\Model\UserInterface;
use App\Modules\User\Repository\MongoDB\UserRepository;
use DateTimeInterface;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\Document(collection: 'users', repositoryClass: UserRepository::class)]
class User implements UserInterface
{
    #[MongoDB\Id(type: 'string', strategy: 'UUID')]
    protected string $id;

    #[MongoDB\Field(type: 'string')]
    #[MongoDB\UniqueIndex(order: 'asc')]
    protected string $email;

    #[MongoDB\Field(type: 'string')]
    protected string $name;

    #[MongoDB\Field(type: 'string')]
    protected string $password;

    #[MongoDB\Field(type: 'string')]
    protected string $role;

    #[MongoDB\Field(type: 'string', nullable: true)]
    protected ?string $avatar = null;

    #[MongoDB\Field(type: 'boolean')]
    protected bool $blocked;

    #[MongoDB\Field(type: 'date')]
    protected DateTimeInterface $createdAt;

    #[MongoDB\Field(type: 'date')]
    protected DateTimeInterface $updatedAt;

    #[MongoDB\Field(type: 'date', nullable: true)]
    protected ?DateTimeInterface $deletedAt = null;

    public function getId(): string
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;
        return $this;
    }

    public function getAvatar(): string|null
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): static
    {
        $this->avatar = $avatar;
        return $this;
    }

    public function isBlocked(): bool
    {
        return $this->blocked;
    }

    public function setBlocked(bool $blocked): static
    {
        $this->blocked = $blocked;
        return $this;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getDeletedAt(): ?DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?DateTimeInterface $deletedAt): static
    {
        $this->deletedAt = $deletedAt;
        return $this;
    }

    // Security UserInterface methods
    public function getRoles(): array
    {
        return ['ROLE_' . strtoupper($this->role)];
    }

    public function eraseCredentials(): void
    {
        $this->password = '';
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }
}
