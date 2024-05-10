<?php

namespace App\Modules\SeasonTeam\Dto;

use App\Common\OAAttributes\OARoleBasedProperty;
use App\Common\Serialization\RoleSerializationGroup;
use App\Modules\SeasonTeam\Model\SeasonTeamGetInterface;
use Symfony\Component\Serializer\Attribute\Groups;

readonly class SeasonTeamGetDto
{
    public function __construct(
        private SeasonTeamGetInterface $seasonTeam
    ) {
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Season team identifier.', RoleSerializationGroup::ALL)]
    public function getId(): string
    {
        return $this->seasonTeam->getId();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Season team name.', RoleSerializationGroup::ALL)]
    public function getName(): string
    {
        return $this->seasonTeam->getName();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Season team team identifier.', RoleSerializationGroup::ALL)]
    public function getTeamId(): string
    {
        return $this->seasonTeam->getTeam()->getId();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Season team season identifier.', RoleSerializationGroup::ALL)]
    public function getSeasonId(): string
    {
        return $this->seasonTeam->getSeason()->getId();
    }
}
