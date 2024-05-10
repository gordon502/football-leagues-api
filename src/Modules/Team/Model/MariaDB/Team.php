<?php

namespace App\Modules\Team\Model\MariaDB;

use App\Common\Model\MariaDB\ModelUuidTrait;
use App\Common\Timestamp\TimestampableTrait;
use App\Modules\OrganizationalUnit\Model\MariaDB\OrganizationalUnit;
use App\Modules\OrganizationalUnit\Model\OrganizationalUnitInterface;
use App\Modules\Team\Model\TeamInterface;
use App\Modules\Team\Repository\MariaDB\TeamRepository;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: TeamRepository::class)]
#[Table(name: 'team')]
#[HasLifecycleCallbacks]
class Team implements TeamInterface
{
    use ModelUuidTrait;
    use TimestampableTrait;

    #[Column(type: 'string')]
    private string $name;

    #[Column(type: 'integer', nullable: true)]
    private int|null $yearEstablished;

    #[Column(type: 'string', nullable: true)]
    private string|null $colors;

    #[Column(type: 'string', nullable: true)]
    private string|null $country;

    #[Column(type: 'string', nullable: true)]
    private string|null $address;

    #[Column(type: 'string', nullable: true)]
    private string|null $city;

    #[Column(type: 'string', nullable: true)]
    private string|null $postalCode;

    #[Column(type: 'string', nullable: true)]
    private string|null $site;

    #[Column(type: 'string', nullable: true)]
    private string|null $stadium;

    #[ManyToOne(targetEntity: OrganizationalUnit::class, inversedBy: 'teams')]
    #[JoinColumn(name: 'organizational_unit_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
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
