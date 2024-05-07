<?php

namespace App\Modules\OrganizationalUnit\Dto;

use App\Common\Dto\NotIncludedInBody;
use App\Common\Dto\NotIncludedInBodyTrait;
use App\Common\OAAttributes\OARoleBasedProperty;
use App\Common\Serialization\RoleSerializationGroup;
use App\Modules\OrganizationalUnit\Model\OrganizationalUnitUpdatableInterface;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

readonly class OrganizationalUnitUpdateDto implements OrganizationalUnitUpdatableInterface
{
    use NotIncludedInBodyTrait;

    public function __construct(
        private string|null|NotIncludedInBody $name = new NotIncludedInBody(),
        private string|null|NotIncludedInBody $country = new NotIncludedInBody(),
        private string|null|NotIncludedInBody $address = new NotIncludedInBody(),
        private string|null|NotIncludedInBody $city = new NotIncludedInBody(),
        private string|null|NotIncludedInBody $postalCode = new NotIncludedInBody(),
        private string|null|NotIncludedInBody $phone = new NotIncludedInBody()
    ) {
    }

    #[Groups([RoleSerializationGroup::ADMIN])]
    #[Assert\NotBlank]
    #[OARoleBasedProperty('Organizational unit name.', [RoleSerializationGroup::ADMIN])]
    public function getName(): string|null
    {
        return $this->toValueOrNull($this->name);
    }

    #[Groups([RoleSerializationGroup::ADMIN])]
    #[Assert\NotBlank]
    #[OARoleBasedProperty('Organizational unit country.', [RoleSerializationGroup::ADMIN])]
    public function getCountry(): string|null
    {
        return $this->toValueOrNull($this->country);
    }

    #[Groups([RoleSerializationGroup::ADMIN])]
    #[Assert\NotBlank]
    #[OARoleBasedProperty('Organizational unit address.', [RoleSerializationGroup::ADMIN])]
    public function getAddress(): string|null
    {
        return $this->toValueOrNull($this->address);
    }

    #[Groups([RoleSerializationGroup::ADMIN])]
    #[Assert\NotBlank]
    #[OARoleBasedProperty('Organizational unit city.', [RoleSerializationGroup::ADMIN])]
    public function getCity(): string|null
    {
        return $this->toValueOrNull($this->city);
    }

    #[Groups([RoleSerializationGroup::ADMIN])]
    #[Assert\NotBlank]
    #[OARoleBasedProperty('Organizational unit postal code.', [RoleSerializationGroup::ADMIN])]
    public function getPostalCode(): string|null
    {
        return $this->toValueOrNull($this->postalCode);
    }

    #[Groups([RoleSerializationGroup::ADMIN])]
    #[Assert\NotBlank]
    #[OARoleBasedProperty('Organizational unit phone.', [RoleSerializationGroup::ADMIN])]
    public function getPhone(): string|null
    {
        return $this->toValueOrNull($this->phone);
    }
}
