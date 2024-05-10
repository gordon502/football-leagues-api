<?php

namespace App\Modules\Season\Dto;

use App\Common\OAAttributes\OARoleBasedProperty;
use App\Common\Serialization\RoleSerializationGroup;
use App\Modules\Season\Model\SeasonGetInterface;
use Symfony\Component\Serializer\Attribute\Groups;

readonly class SeasonGetDto
{
    public function __construct(
        private SeasonGetInterface $season
    ) {
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Season identifier.', RoleSerializationGroup::ALL)]
    public function getId(): string
    {
        return $this->season->getId();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Season name.', RoleSerializationGroup::ALL)]
    public function getName(): string
    {
        return $this->season->getName();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Season period.', RoleSerializationGroup::ALL)]
    public function getPeriod(): string
    {
        return $this->season->getPeriod();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Season active.', RoleSerializationGroup::ALL)]
    public function isActive(): bool
    {
        return $this->season->isActive();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('League identifier.', RoleSerializationGroup::ALL)]
    public function getLeagueId(): string
    {
        return $this->season->getLeague()->getId();
    }
}
