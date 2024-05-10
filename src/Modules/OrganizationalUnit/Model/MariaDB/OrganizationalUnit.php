<?php

namespace App\Modules\OrganizationalUnit\Model\MariaDB;

use App\Common\Model\MariaDB\ModelUuidTrait;
use App\Common\Timestamp\TimestampableTrait;
use App\Modules\League\Model\MariaDB\League;
use App\Modules\OrganizationalUnit\Model\OrganizationalUnitInterface;
use App\Modules\OrganizationalUnit\Repository\MariaDB\OrganizationalUnitRepository;
use App\Modules\Team\Model\MariaDB\Team;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: OrganizationalUnitRepository::class)]
#[Table(name: 'organizational_unit')]
#[HasLifecycleCallbacks]
class OrganizationalUnit implements OrganizationalUnitInterface
{
    use ModelUuidTrait;
    use TimestampableTrait;

    #[Column(type: 'string')]
    protected string $name;

    #[Column(type: 'string')]
    protected string $country;

    #[Column(type: 'string')]
    protected string $address;

    #[Column(type: 'string')]
    protected string $city;

    #[Column(type: 'string')]
    protected string $postalCode;

    #[Column(type: 'string', nullable: true)]
    protected ?string $phone = null;

    #[OneToMany(targetEntity: League::class, mappedBy: 'organizationalUnit', cascade: ['all'], orphanRemoval: true)]
    protected Collection $leagues;

    #[OneToMany(targetEntity: Team::class, mappedBy: 'organizationalUnit', cascade: ['all'], orphanRemoval: true)]
    protected Collection $teams;

    public function __construct()
    {
        $this->leagues = new ArrayCollection();
        $this->teams = new ArrayCollection();
    }

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

    public function getLeagues(): Collection
    {
        return $this->leagues;
    }

    public function getTeams(): Collection
    {
        return $this->teams;
    }
}
