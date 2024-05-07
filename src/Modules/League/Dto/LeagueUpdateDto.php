<?php

namespace App\Modules\League\Dto;

use App\Common\Dto\NotIncludedInBody;
use App\Common\Dto\NotIncludedInBodyTrait;
use App\Common\OAAttributes\OARoleBasedProperty;
use App\Common\Serialization\RoleSerializationGroup;
use App\Modules\League\Model\LeagueUpdatableInterface;
use Symfony\Component\Validator\Constraints as Assert;

readonly class LeagueUpdateDto implements LeagueUpdatableInterface
{
    use NotIncludedInBodyTrait;

    public function __construct(
        #[Assert\NotNull]
        #[Assert\NotBlank]
        private string|null|NotIncludedInBody $name = new NotIncludedInBody(),
        #[Assert\NotNull]
        #[Assert\Type('bool')]
        private bool|null|NotIncludedInBody $active = new NotIncludedInBody(),
        #[Assert\Type(['int', 'null'])]
        private int|null|NotIncludedInBody $level = new NotIncludedInBody(),
        #[Assert\NotNull]
        #[Assert\Uuid]
        private string|null|NotIncludedInBody $organizationalUnitId = new NotIncludedInBody(),
    ) {
    }

    #[OARoleBasedProperty('League name.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    public function getName(): string|null
    {
        return $this->toValueOrNull($this->name);
    }

    #[OARoleBasedProperty('League active.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    public function isActive(): bool|null
    {
        return $this->toValueOrNull($this->active);
    }

    #[OARoleBasedProperty('League level.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    public function getLevel(): int|null
    {
        return $this->toValueOrNull($this->level);
    }

    #[OARoleBasedProperty('Organizational unit identifier.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    public function getOrganizationalUnitId(): string|null
    {
        return $this->toValueOrNull($this->organizationalUnitId);
    }
}
