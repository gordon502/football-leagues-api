<?php

namespace App\Modules\League\Dto;

use App\Common\Dto\NotIncludedInBody;
use App\Common\Dto\NotIncludedInBodyTrait;
use App\Common\OAAttributes\OARoleBasedProperty;
use App\Common\Serialization\RoleSerializationGroup;
use App\Modules\League\Model\LeagueUpdatableInterface;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

readonly class LeagueUpdateDto implements LeagueUpdatableInterface
{
    use NotIncludedInBodyTrait;

    public function __construct(
        private string|null|NotIncludedInBody $name = new NotIncludedInBody(),
        private bool|null|NotIncludedInBody $active = new NotIncludedInBody(),
        private int|null|NotIncludedInBody $level = new NotIncludedInBody(),
        private string|null|NotIncludedInBody $organizationalUnitId = new NotIncludedInBody(),
    ) {
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('League name.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\NotBlank]
    public function getName(): string|null
    {
        return $this->toValueOrNull($this->name);
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('League active.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\NotNull]
    #[Assert\Type('boolean')]
    public function isActive(): bool|null
    {
        return $this->toValueOrNull($this->active);
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('League level.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\Type(['int', 'null'])]
    public function getLevel(): int|null
    {
        return $this->toValueOrNull($this->level);
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Organizational unit identifier.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\NotBlank]
    #[Assert\Uuid]
    public function getOrganizationalUnitId(): string|null
    {
        return $this->toValueOrNull($this->organizationalUnitId);
    }
}
