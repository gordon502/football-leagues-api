<?php

namespace App\Modules\Team\Model\MongoDB;

use App\Common\Model\MongoDB\ModelUuidTrait;
use App\Common\Timestamp\TimestampableTrait;
use App\Modules\OrganizationalUnit\Model\MongoDB\OrganizationalUnit;
use App\Modules\OrganizationalUnit\Model\OrganizationalUnitInterface;
use App\Modules\Team\Model\TeamInterface;
use Doctrine\ODM\MongoDB\Mapping\Annotations\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations\Field;
use Doctrine\ODM\MongoDB\Mapping\Annotations\HasLifecycleCallbacks;
use Doctrine\ODM\MongoDB\Mapping\Annotations\ReferenceOne;

#[Document(collection: 'team')]
#[HasLifecycleCallbacks]
class Team implements TeamInterface
{
    use ModelUuidTrait;
    use TimestampableTrait;

    #[Field(type: 'string')]
    private string $name;

    #[Field(type: 'int', nullable: true)]
    private int|null $yearEstablished;

    #[Field(type: 'string', nullable: true)]
    private string|null $colors;

    #[Field(type: 'string', nullable: true)]
    private string|null $country;

    #[Field(type: 'string', nullable: true)]
    private string|null $address;

    #[Field(type: 'string', nullable: true)]
    private string|null $city;

    #[Field(type: 'string', nullable: true)]
    private string|null $postalCode;

    #[Field(type: 'string', nullable: true)]
    private string|null $site;

    #[Field(type: 'string', nullable: true)]
    private string|null $stadium;

    #[ReferenceOne(targetDocument: OrganizationalUnit::class, inversedBy: 'teams')]
    private OrganizationalUnitInterface $organizationalUnit;

    public function getName(): string
    {
        return $this->name;
    }

    public function getYearEstablished(): int|null
    {
        return $this->yearEstablished;
    }

    public function getColors(): string|null
    {
        return $this->colors;
    }

    public function getCountry(): string|null
    {
        return $this->country;
    }

    public function getAddress(): string|null
    {
        return $this->address;
    }

    public function getCity(): string|null
    {
        return $this->city;
    }

    public function getPostalCode(): string|null
    {
        return $this->postalCode;
    }

    public function getSite(): string|null
    {
        return $this->site;
    }

    public function getStadium(): string|null
    {
        return $this->stadium;
    }

    public function getOrganizationalUnit(): OrganizationalUnitInterface
    {
        return $this->organizationalUnit;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function setYearEstablished(?int $yearEstablished): static
    {
        $this->yearEstablished = $yearEstablished;

        return $this;
    }

    public function setColors(?string $colors): static
    {
        $this->colors = $colors;

        return $this;
    }

    public function setCountry(?string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function setCity(?string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function setPostalCode(?string $postalCode): static
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function setSite(?string $site): static
    {
        $this->site = $site;

        return $this;
    }

    public function setStadium(?string $stadium): static
    {
        $this->stadium = $stadium;

        return $this;
    }

    public function setOrganizationalUnit(OrganizationalUnitInterface $organizationalUnit): static
    {
        $this->organizationalUnit = $organizationalUnit;

        return $this;
    }
}
