<?php

namespace App\Modules\User\Dto;

use App\Common\Dto\NotIncludedInBody;
use App\Common\Dto\NotIncludedInBodyTrait;
use App\Common\OAAttributes\OARoleBasedProperty;
use App\Common\Serialization\RoleSerializationGroup;
use App\Modules\User\Model\UserUpdatableInterface;
use App\Modules\User\Role\UserRole;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

readonly class UserUpdateDto implements UserUpdatableInterface
{
    use NotIncludedInBodyTrait;

    public function __construct(
        private string|null|NotIncludedInBody $email = new NotIncludedInBody(),
        private string|null|NotIncludedInBody $name = new NotIncludedInBody(),
        private string|null|NotIncludedInBody $password = new NotIncludedInBody(),
        private string|null|NotIncludedInBody $role = new NotIncludedInBody(),
        private string|null|NotIncludedInBody $avatar = new NotIncludedInBody(),
        private bool|null|NotIncludedInBody $blocked = new NotIncludedInBody(),
    ) {
    }

    #[Groups(RoleSerializationGroup::OWNER)]
    #[Assert\NotBlank]
    #[Assert\Email]
    #[OARoleBasedProperty('User email.', [RoleSerializationGroup::OWNER])]
    public function getEmail(): string|null
    {
        return $this->toValueOrNull($this->email);
    }

    #[Groups([RoleSerializationGroup::OWNER, RoleSerializationGroup::ADMIN, RoleSerializationGroup::MODERATOR])]
    #[Assert\NotBlank]
    #[OARoleBasedProperty(
        'User name.',
        [RoleSerializationGroup::OWNER, RoleSerializationGroup::ADMIN, RoleSerializationGroup::MODERATOR]
    )]
    public function getName(): string|null
    {
        return $this->toValueOrNull($this->name);
    }

    #[Groups(RoleSerializationGroup::OWNER)]
    #[Assert\Length(
        min: 8,
        max: 255,
        minMessage: 'Password must be at least {{ limit }} characters long.',
        maxMessage: 'Password cannot be longer than {{ limit }} characters.'
    )]
    #[OARoleBasedProperty('User password.', [RoleSerializationGroup::OWNER])]
    public function getPassword(): string|null
    {
        return $this->toValueOrNull($this->password);
    }

    #[Groups([RoleSerializationGroup::ADMIN])]
    #[Assert\Choice(choices: [
        UserRole::ADMIN,
        UserRole::MODERATOR,
        UserRole::EDITOR,
        UserRole::USER
    ])]
    #[OARoleBasedProperty('User role.', [RoleSerializationGroup::ADMIN])]
    public function getRole(): string|null
    {
        return $this->toValueOrNull($this->role);
    }

    #[Groups([RoleSerializationGroup::OWNER, RoleSerializationGroup::ADMIN, RoleSerializationGroup::MODERATOR])]
    #[Assert\Type(['string', 'null'])]
    #[OARoleBasedProperty(
        'User avatar.',
        [RoleSerializationGroup::OWNER, RoleSerializationGroup::ADMIN, RoleSerializationGroup::MODERATOR]
    )]
    public function getAvatar(): string|null
    {
        return $this->toValueOrNull($this->avatar);
    }

    #[Groups([RoleSerializationGroup::ADMIN, RoleSerializationGroup::MODERATOR])]
    #[Assert\Type('bool')]
    #[OARoleBasedProperty('Block user.', [RoleSerializationGroup::ADMIN, RoleSerializationGroup::MODERATOR])]
    public function isBlocked(): bool|null
    {
        return $this->toValueOrNull($this->blocked);
    }
}
