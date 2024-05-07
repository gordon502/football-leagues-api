<?php

namespace App\Modules\League\Dto;

use App\Common\OAAttributes\OARoleBasedProperty;
use App\Common\Serialization\RoleSerializationGroup;
use App\Modules\League\Model\LeagueCreatableInterface;
use Symfony\Component\Validator\Constraints as Assert;

class LeagueCreateDto implements LeagueCreatableInterface
{
    private string|null $name;
    private bool|null $active;
    private int|null $level;
    private string|null $organizationalUnitId;

    public function __construct(
        string $name = null,
        bool $active = null,
        int|null $level = null,
        string $organizationalUnitId = null
    ) {
        $this->name = $name;
        $this->active = $active;
        $this->level = $level;
        $this->organizationalUnitId = $organizationalUnitId;
    }

    #[OARoleBasedProperty('League name.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    public function getName(): string|null
    {
        return $this->name;
    }

    #[OARoleBasedProperty('League active.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\NotNull]
    #[Assert\Type('bool')]
    public function isActive(): bool|null
    {
        return $this->active;
    }

    #[OARoleBasedProperty('League level.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\Type(['int', 'null'])]
    public function getLevel(): int|null
    {
        return $this->level;
    }

    #[OARoleBasedProperty(
        'Organizational unit identifier.',
        [
            RoleSerializationGroup::ADMIN,
            RoleSerializationGroup::MODERATOR,
            RoleSerializationGroup::EDITOR
        ],
        property: 'organizational_unit_id'
    )]
    #[Assert\NotNull]
    #[Assert\Uuid]
    public function getOrganizationalUnitId(): string|null
    {
        return $this->organizationalUnitId;
    }
}
