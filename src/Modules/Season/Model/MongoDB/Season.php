<?php

namespace App\Modules\Season\Model\MongoDB;

use App\Common\Model\MongoDB\ModelUuidTrait;
use App\Common\Timestamp\TimestampableTrait;
use App\Modules\League\Model\LeagueInterface;
use App\Modules\League\Model\MongoDB\League;
use App\Modules\Round\Model\MongoDB\Round;
use App\Modules\Season\Model\SeasonInterface;
use App\Modules\SeasonTeam\Model\MongoDB\SeasonTeam;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations\Field;
use Doctrine\ODM\MongoDB\Mapping\Annotations\HasLifecycleCallbacks;
use Doctrine\ODM\MongoDB\Mapping\Annotations\ReferenceMany;
use Doctrine\ODM\MongoDB\Mapping\Annotations\ReferenceOne;

#[Document(collection: 'season')]
#[HasLifecycleCallbacks]
class Season implements SeasonInterface
{
    use ModelUuidTrait;
    use TimestampableTrait;

    #[Field(type: 'string')]
    protected string $name;

    #[Field(type: 'boolean')]
    protected bool $active;

    #[Field(type: 'string')]
    protected string $period;

    #[ReferenceOne(targetDocument: League::class, inversedBy: 'seasons')]
    protected LeagueInterface $league;

    #[ReferenceMany(targetDocument: SeasonTeam::class, cascade: ['all'], orphanRemoval: true, mappedBy: 'season')]
    protected Collection $seasonTeams;

    #[ReferenceMany(targetDocument: Round::class, cascade: ['all'], orphanRemoval: true, mappedBy: 'season')]
    protected Collection $rounds;

    public function __construct()
    {
        $this->seasonTeams = new ArrayCollection();
        $this->rounds = new ArrayCollection();
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
