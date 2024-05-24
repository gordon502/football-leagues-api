<?php

namespace App\Modules\Season\Model\MariaDB;

use App\Common\Model\MariaDB\ModelUuidTrait;
use App\Common\Timestamp\TimestampableTrait;
use App\Modules\Leaderboard\Model\LeaderboardInterface;
use App\Modules\Leaderboard\Model\MariaDB\Leaderboard;
use App\Modules\League\Model\LeagueInterface;
use App\Modules\League\Model\MariaDB\League;
use App\Modules\League\Repository\MariaDB\LeagueRepository;
use App\Modules\Round\Model\MariaDB\Round;
use App\Modules\Season\Model\SeasonInterface;
use App\Modules\SeasonTeam\Model\MariaDB\SeasonTeam;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
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
    #[JoinColumn(name: 'league_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    protected LeagueInterface $league;

    #[OneToMany(targetEntity: SeasonTeam::class, mappedBy: 'season', cascade: ['all'], orphanRemoval: true)]
    #[JoinColumn(name: 'season_id', referencedColumnName: 'id')]
    private Collection $seasonTeams;

    #[OneToMany(targetEntity: Round::class, mappedBy: 'season', cascade: ['all'], orphanRemoval: true)]
    #[JoinColumn(name: 'season_id', referencedColumnName: 'id')]
    private Collection $rounds;

    #[OneToMany(targetEntity: Leaderboard::class, mappedBy: 'season', cascade: ['all'], orphanRemoval: true)]
    protected Collection $leaderboards;

    public function __construct()
    {
        $this->seasonTeams = new ArrayCollection();
        $this->rounds = new ArrayCollection();
        $this->leaderboards = new ArrayCollection();
    }

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

    public function getSeasonTeams(): Collection
    {
        return $this->seasonTeams;
    }

    public function getLeaderboards(): Collection
    {
        return $this->leaderboards;
    }

    public function getRounds(): Collection
    {
        return $this->rounds;
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
