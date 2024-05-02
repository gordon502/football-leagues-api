<?php

namespace App\Modules\User\Dto;

use App\Common\Serialization\RoleSerializationGroup;
use App\Modules\User\Model\UserCreatableInterface;
use OpenApi\Attributes\Property;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class UserCreateDto implements UserCreatableInterface
{
    private string $email;
    private string $name;
    private string $password;

    public function __construct(string $email = null, string $name = null, string $password = null)
    {
        $this->email = $email;
        $this->name = $name;
        $this->password = $password;
    }

    #[Groups([RoleSerializationGroup::GUEST])]
    #[Property(description: 'User email. It will be used for login. It must be unique.')]
    #[Assert\NotBlank]
    #[Assert\Email]
    public function getEmail(): string
    {
        return $this->email;
    }

    #[Groups([RoleSerializationGroup::GUEST])]
    #[Property(description: 'User display name.')]
    #[Assert\NotBlank]
    public function getName(): string
    {
        return $this->name;
    }

    #[Groups([RoleSerializationGroup::GUEST])]
    #[Property(description: 'User password.')]
    #[Assert\Length(
        min: 8,
        max: 255,
        minMessage: 'Password must be at least {{ limit }} characters long.',
        maxMessage: 'Password cannot be longer than {{ limit }} characters.'
    )]
    public function getPassword(): string
    {
        return $this->password;
    }
}
