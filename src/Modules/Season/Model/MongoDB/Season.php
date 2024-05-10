<?php

namespace App\Modules\Season\Model\MongoDB;

use App\Common\Model\MongoDB\ModelUuidTrait;
use App\Common\Timestamp\TimestampableTrait;
use App\Modules\League\Model\LeagueInterface;
use App\Modules\League\Model\MongoDB\League;
use App\Modules\Season\Model\SeasonInterface;
use Doctrine\ODM\MongoDB\Mapping\Annotations\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations\Field;
use Doctrine\ODM\MongoDB\Mapping\Annotations\HasLifecycleCallbacks;
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
