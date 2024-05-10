<?php

namespace App\Modules\Season\Model\MariaDB;

use App\Common\Model\MariaDB\ModelUuidTrait;
use App\Common\Timestamp\TimestampableTrait;
use App\Modules\League\Model\LeagueInterface;
use App\Modules\League\Model\MariaDB\League;
use App\Modules\League\Repository\MariaDB\LeagueRepository;
use App\Modules\Season\Model\SeasonInterface;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: LeagueRepository::class)]
#[Table(name: 'season')]
#[HasLifecycleCallbacks]
class Season implements SeasonInterface
{
    use ModelUuidTrait;
    use TimestampableTrait;

    #[Column(type: 'string')]
    protected string $name;

    #[Column(type: 'boolean')]
    protected bool $active;

    #[Column(type: 'string')]
    protected string $period;

    #[ManyToOne(targetEntity: League::class, inversedBy: 'seasons')]
    protected LeagueInterface $league;

    public function getName(): string
    {
        return $this->name;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function getPeriod(): string
    {
        return $this->period;
    }

    public function getLeague(): LeagueInterface
    {
        return $this->league;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;

        return $this;
    }

    public function setPeriod(string $period): static
    {
        $this->period = $period;

        return $this;
    }

    public function setLeague(LeagueInterface $league): static
    {
        $this->league = $league;

        return $this;
    }
}
