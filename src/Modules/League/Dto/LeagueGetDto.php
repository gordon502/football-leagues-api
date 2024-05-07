<?php

namespace App\Modules\League\Dto;

use App\Common\OAAttributes\OARoleBasedProperty;
use App\Common\Serialization\RoleSerializationGroup;
use App\Modules\League\Model\LeagueGetInterface;
use Symfony\Component\Serializer\Attribute\Groups;

readonly class LeagueGetDto
{
    public function __construct(
        private LeagueGetInterface $league
    ) {
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('League identifier.', RoleSerializationGroup::ALL)]
    public function getId(): string
    {
        return $this->league->getId();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('League name.', RoleSerializationGroup::ALL)]
    public function getName(): string
    {
        return $this->league->getName();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('League active.', RoleSerializationGroup::ALL)]
    public function isActive(): bool
    {
        return $this->league->isActive();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('League level.', RoleSerializationGroup::ALL)]
    public function getLevel(): int|null
    {
        return $this->league->getLevel();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty(
        'League organizational unit.',
        RoleSerializationGroup::ALL,
        property: 'organizational_unit_id'
    )]
    public function getOrganizationalUnitId(): string
    {
        return $this->league->getOrganizationalUnit()->getId();
    }
}
