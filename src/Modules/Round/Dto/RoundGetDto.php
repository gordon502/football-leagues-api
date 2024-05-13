<?php

namespace App\Modules\Round\Dto;

use App\Common\OAAttributes\OARoleBasedProperty;
use App\Common\Serialization\RoleSerializationGroup;
use App\Modules\Round\Model\RoundGetInterface;
use Symfony\Component\Serializer\Attribute\Groups;

readonly class RoundGetDto
{
    public function __construct(
        private RoundGetInterface $round
    ) {
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Round ID.', RoleSerializationGroup::ALL)]
    public function getId(): string
    {
        return $this->round->getId();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Round number.', RoleSerializationGroup::ALL)]
    public function getNumber(): int
    {
        return $this->round->getNumber();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Round standard start date.', RoleSerializationGroup::ALL)]
    public function getStandardStartDate(): string
    {
        return $this->round->getStandardStartDate()->format('Y-m-d');
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Round standard end date.', RoleSerializationGroup::ALL)]
    public function getStandardEndDate(): string
    {
        return $this->round->getStandardEndDate()->format('Y-m-d');
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Season ID.', RoleSerializationGroup::ALL)]
    public function getSeasonId(): string
    {
        return $this->round->getSeason()->getId();
    }
}
