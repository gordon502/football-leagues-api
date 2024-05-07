<?php

namespace App\Modules\OrganizationalUnit\Dto;

use App\Common\OAAttributes\OARoleBasedProperty;
use App\Common\Serialization\RoleSerializationGroup;
use App\Modules\OrganizationalUnit\Model\OrganizationalUnitGetInterface;
use Symfony\Component\Serializer\Attribute\Groups;

readonly class OrganizationalUnitGetDto implements OrganizationalUnitGetInterface
{
    public function __construct(
        private OrganizationalUnitGetInterface $organizationalUnit
    ) {
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Organizational unit identifier.', RoleSerializationGroup::ALL)]
    public function getId(): string
    {
        return $this->organizationalUnit->getId();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Organizational unit name.', RoleSerializationGroup::ALL)]
    public function getName(): string
    {
        return $this->organizationalUnit->getName();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Organizational unit country.', RoleSerializationGroup::ALL)]
    public function getCountry(): string
    {
        return $this->organizationalUnit->getCountry();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Organizational unit address.', RoleSerializationGroup::ALL)]
    public function getAddress(): string
    {
        return $this->organizationalUnit->getAddress();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Organizational unit city.', RoleSerializationGroup::ALL)]
    public function getCity(): string
    {
        return $this->organizationalUnit->getCity();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Organizational unit postal code.', RoleSerializationGroup::ALL)]
    public function getPostalCode(): string
    {
        return $this->organizationalUnit->getPostalCode();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Organizational unit phone.', RoleSerializationGroup::ALL)]
    public function getPhone(): string|null
    {
        return $this->organizationalUnit->getPhone();
    }
}
