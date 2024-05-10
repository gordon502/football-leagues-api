<?php

namespace App\Modules\SeasonTeam\Dto;

use App\Common\Dto\DtoPropertyRelatedToEntity;
use App\Common\Dto\NotIncludedInBody;
use App\Common\Dto\NotIncludedInBodyTrait;
use App\Common\OAAttributes\OARoleBasedProperty;
use App\Common\Serialization\RoleSerializationGroup;
use App\Modules\SeasonTeam\Model\SeasonTeamUpdatableInterface;
use App\Modules\Team\Model\TeamInterface;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

readonly class SeasonTeamUpdateDto implements SeasonTeamUpdatableInterface
{
    use NotIncludedInBodyTrait;

    public function __construct(
        private string|null|NotIncludedInBody $name = new NotIncludedInBody(),
        private string|null|NotIncludedInBody $teamId = new NotIncludedInBody(),
        private string|null|NotIncludedInBody $seasonId = new NotIncludedInBody(),
    ) {
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Season team name.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\NotNull]
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
    #[OARoleBasedProperty('Season team team identifier.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[DtoPropertyRelatedToEntity(TeamInterface::class)]
    #[Assert\NotBlank]
    #[Assert\Uuid]
    public function getTeamId(): string|null
    {
        return $this->toValueOrNull($this->teamId);
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Season team team identifier.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[DtoPropertyRelatedToEntity(TeamInterface::class)]
    #[Assert\NotBlank]
    #[Assert\Uuid]
    public function getSeasonId(): string|null
    {
        return $this->toValueOrNull($this->seasonId);
    }
}
