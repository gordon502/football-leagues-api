<?php

namespace App\Modules\Round\Dto;

use App\Common\Dto\DtoPropertyRelatedToEntity;
use App\Common\Dto\NotIncludedInBody;
use App\Common\Dto\NotIncludedInBodyTrait;
use App\Common\OAAttributes\OARoleBasedProperty;
use App\Common\Serialization\RoleSerializationGroup;
use App\Modules\Round\Model\RoundUpdatableInterface;
use App\Modules\Season\Model\SeasonInterface;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

readonly class RoundUpdateDto implements RoundUpdatableInterface
{
    use NotIncludedInBodyTrait;

    public function __construct(
        private int|null|NotIncludedInBody $number = new NotIncludedInBody(),
        private string|null|NotIncludedInBody $standardStartDate = new NotIncludedInBody(),
        private string|null|NotIncludedInBody $standardEndDate = new NotIncludedInBody(),
        private string|null|NotIncludedInBody $seasonId = new NotIncludedInBody(),
    ) {
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Round number.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\NotNull]
    #[Assert\Type('int')]
    public function getNumber(): int|null
    {
        return $this->toValueOrNull($this->number);
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Round standard start date.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\NotNull]
    #[Assert\Date]
    public function getStandardStartDate(): string|null
    {
        return $this->toValueOrNull($this->standardStartDate);
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Round standard end date.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\NotNull]
    #[Assert\Date]
    public function getStandardEndDate(): string|null
    {
        return $this->toValueOrNull($this->standardEndDate);
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Round season identifier.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[DtoPropertyRelatedToEntity(SeasonInterface::class)]
    #[Assert\NotNull]
    #[Assert\Type('string')]
    public function getSeasonId(): string|null
    {
        return $this->toValueOrNull($this->seasonId);
    }
}
