<?php

namespace App\Modules\User\Dto;

use App\Common\OARoleBasedProperty\OARoleBasedProperty;
use App\Common\Serialization\RoleSerializationGroup;
use App\Modules\User\Model\UserGetInterface;
use Symfony\Component\Serializer\Attribute\Groups;

readonly class UserGetDto implements UserGetInterface
{
    public function __construct(
        private UserGetInterface $user
    ) {
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('User identifier.', RoleSerializationGroup::ALL)]
    public function getId(): string
    {
        return $this->user->getId();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('User email.', RoleSerializationGroup::ALL)]
    public function getEmail(): string
    {
        return $this->user->getEmail();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('User name.', RoleSerializationGroup::ALL)]
    public function getName(): string
    {
        return $this->user->getName();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('User role.', RoleSerializationGroup::ALL)]
    public function getRole(): string
    {
        return $this->user->getRole();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('User avatar.', RoleSerializationGroup::ALL)]
    public function getAvatar(): string|null
    {
        return $this->user->getAvatar();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Is user blocked.', RoleSerializationGroup::ALL)]
    public function isBlocked(): bool
    {
        return $this->user->isBlocked();
    }
}
