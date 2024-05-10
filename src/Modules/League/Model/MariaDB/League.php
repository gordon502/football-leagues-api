<?php

namespace App\Modules\League\Model\MariaDB;

use App\Common\Model\MariaDB\ModelUuidTrait;
use App\Common\Timestamp\TimestampableTrait;
use App\Modules\League\Model\LeagueInterface;
use App\Modules\League\Repository\MariaDB\LeagueRepository;
use App\Modules\OrganizationalUnit\Model\MariaDB\OrganizationalUnit;
use App\Modules\OrganizationalUnit\Model\OrganizationalUnitInterface;
use App\Modules\Season\Model\MariaDB\Season;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: LeagueRepository::class)]
#[Table(name: 'league')]
#[HasLifecycleCallbacks]
class League implements LeagueInterface
{
    use ModelUuidTrait;
    use TimestampableTrait;

    #[Column(type: 'string', length: 255)]
    private string $name;

    #[Column(type: 'boolean')]
    private bool $active;

    #[Column(type: 'integer', nullable: true)]
    private int|null $level;

    #[ManyToOne(targetEntity: OrganizationalUnit::class, inversedBy: 'leagues')]
    private OrganizationalUnitInterface $organizationalUnit;

    #[OneToMany(targetEntity: Season::class, mappedBy: 'league', orphanRemoval: true)]
    private Collection $seasons;

    public function __construct()
    {
        $this->seasons = new ArrayCollection();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;

        return $this;
    }

    public function getLevel(): int|null
    {
        return $this->level;
    }

    public function setLevel(int|null $level): static
    {
        $this->level = $level;

        return $this;
    }

    public function getOrganizationalUnit(): OrganizationalUnitInterface
    {
        return $this->organizationalUnit;
    }

    public function setOrganizationalUnit(OrganizationalUnitInterface $organizationalUnit): static
    {
        $this->organizationalUnit = $organizationalUnit;

        return $this;
    }

    public function getSeasons(): Collection
    {
        return $this->seasons;
    }
}
