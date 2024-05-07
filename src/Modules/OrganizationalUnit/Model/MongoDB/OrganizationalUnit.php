<?php

namespace App\Modules\OrganizationalUnit\Model\MongoDB;

use App\Common\Model\MongoDB\ModelUuidTrait;
use App\Common\Timestamp\TimestampableTrait;
use App\Modules\OrganizationalUnit\Model\OrganizationalUnitInterface;
use Doctrine\ODM\MongoDB\Mapping\Annotations\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations\Field;
use Doctrine\ODM\MongoDB\Mapping\Annotations\HasLifecycleCallbacks;

#[Document(collection: 'organizational_unit')]
#[HasLifecycleCallbacks]
class OrganizationalUnit implements OrganizationalUnitInterface
{
    use ModelUuidTrait;
    use TimestampableTrait;

    #[Field(type: 'string')]
    protected string $name;

    #[Field(type: 'string')]
    protected string $country;

    #[Field(type: 'string')]
    protected string $address;

    #[Field(type: 'string')]
    protected string $city;

    #[Field(type: 'string')]
    protected string $postalCode;

    #[Field(type: 'string', nullable: true)]
    protected ?string $phone = null;

    public function getName(): string
    {
        return $this->name;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    public function getPhone(): string|null
    {
        return $this->phone;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function setPostalCode(string $postalCode): static
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }
}
