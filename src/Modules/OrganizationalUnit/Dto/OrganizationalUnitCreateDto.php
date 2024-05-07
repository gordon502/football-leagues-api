<?php

namespace App\Modules\OrganizationalUnit\Dto;

use App\Common\OAAttributes\OARoleBasedProperty;
use App\Common\Serialization\RoleSerializationGroup;
use App\Modules\OrganizationalUnit\Model\OrganizationalUnitCreatableInterface;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class OrganizationalUnitCreateDto implements OrganizationalUnitCreatableInterface
{
    private string $name;
    private string $country;
    private string $address;
    private string $city;
    private string $postalCode;
    private ?string $phone;

    public function __construct(
        string $name,
        string $country,
        string $address,
        string $city,
        string $postalCode,
        ?string $phone
    ) {
        $this->name = $name;
        $this->country = $country;
        $this->address = $address;
        $this->city = $city;
        $this->postalCode = $postalCode;
        $this->phone = $phone;
    }

    #[Groups([RoleSerializationGroup::ADMIN])]
    #[OARoleBasedProperty(description: 'Organizational unit name.', roles: [RoleSerializationGroup::ADMIN])]
    #[Assert\NotBlank]
    public function getName(): string
    {
        return $this->name;
    }

    #[Groups([RoleSerializationGroup::ADMIN])]
    #[OARoleBasedProperty(description: 'Organizational unit country.', roles: [RoleSerializationGroup::ADMIN])]
    #[Assert\NotBlank]
    public function getCountry(): string
    {
        return $this->country;
    }

    #[Groups([RoleSerializationGroup::ADMIN])]
    #[OARoleBasedProperty(description: 'Organizational unit address.', roles: [RoleSerializationGroup::ADMIN])]
    #[Assert\NotBlank]
    public function getAddress(): string
    {
        return $this->address;
    }

    #[Groups([RoleSerializationGroup::ADMIN])]
    #[OARoleBasedProperty(description: 'Organizational unit city.', roles: [RoleSerializationGroup::ADMIN])]
    #[Assert\NotBlank]
    public function getCity(): string
    {
        return $this->city;
    }

    #[Groups([RoleSerializationGroup::ADMIN])]
    #[OARoleBasedProperty(description: 'Organizational unit postal code.', roles: [RoleSerializationGroup::ADMIN])]
    #[Assert\NotBlank]
    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    #[Groups([RoleSerializationGroup::ADMIN])]
    #[OARoleBasedProperty(description: 'Organizational unit phone.', roles: [RoleSerializationGroup::ADMIN])]
    public function getPhone(): string|null
    {
        return $this->phone;
    }
}
