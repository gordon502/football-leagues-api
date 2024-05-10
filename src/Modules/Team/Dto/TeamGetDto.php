<?php

namespace App\Modules\Team\Dto;

use App\Common\OAAttributes\OARoleBasedProperty;
use App\Common\Serialization\RoleSerializationGroup;
use App\Modules\Team\Model\TeamGetInterface;
use Symfony\Component\Serializer\Attribute\Groups;

readonly class TeamGetDto
{
    public function __construct(
        private TeamGetInterface $team
    ) {
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Team ID.', RoleSerializationGroup::ALL)]
    public function getId(): string
    {
        return $this->team->getId();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Team name.', RoleSerializationGroup::ALL)]
    public function getName(): string
    {
        return $this->team->getName();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Year established.', RoleSerializationGroup::ALL)]
    public function getYearEstablished(): int|null
    {
        return $this->team->getYearEstablished();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Colors.', RoleSerializationGroup::ALL)]
    public function getColors(): string|null
    {
        return $this->team->getColors();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Country.', RoleSerializationGroup::ALL)]
    public function getCountry(): string|null
    {
        return $this->team->getCountry();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Address.', RoleSerializationGroup::ALL)]
    public function getAddress(): string|null
    {
        return $this->team->getAddress();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('City.', RoleSerializationGroup::ALL)]
    public function getCity(): string|null
    {
        return $this->team->getCity();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Postal code.', RoleSerializationGroup::ALL)]
    public function getPostalCode(): string|null
    {
        return $this->team->getPostalCode();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Site.', RoleSerializationGroup::ALL)]
    public function getSite(): string|null
    {
        return $this->team->getSite();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Stadium.', RoleSerializationGroup::ALL)]
    public function getStadium(): string|null
    {
        return $this->team->getStadium();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Organizational unit ID.', RoleSerializationGroup::ALL)]
    public function getOrganizationalUnitId(): string
    {
        return $this->team->getOrganizationalUnit()->getId();
    }
}
