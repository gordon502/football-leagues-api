<?php

namespace App\Modules\Round\Dto;

use App\Common\OAAttributes\OARoleBasedProperty;
use App\Common\Serialization\RoleSerializationGroup;
use App\Modules\Round\Model\roundCreatableInterface;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class RoundCreateDto implements roundCreatableInterface
{
    private int|null $number;
    private string|null $standardStartDate;
    private string|null $standardEndDate;
    private string|null $seasonId;

    public function __construct(
        int|null $number,
        string|null $standardStartDate,
        string|null $standardEndDate,
        string|null $seasonId
    ) {
        $this->number = $number;
        $this->standardStartDate = $standardStartDate;
        $this->standardEndDate = $standardEndDate;
        $this->seasonId = $seasonId;
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
        return $this->number;
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
        return $this->standardStartDate;
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
        return $this->standardEndDate;
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Season ID.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\NotNull]
    #[Assert\Uuid]
    public function getSeasonId(): string|null
    {
        return $this->seasonId;
    }
}
