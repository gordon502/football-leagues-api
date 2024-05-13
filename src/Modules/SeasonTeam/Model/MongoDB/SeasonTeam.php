<?php

namespace App\Modules\SeasonTeam\Model\MongoDB;

use App\Common\Model\MongoDB\ModelUuidTrait;
use App\Common\Timestamp\TimestampableTrait;
use App\Modules\Season\Model\MongoDB\Season;
use App\Modules\Season\Model\SeasonInterface;
use App\Modules\SeasonTeam\Model\SeasonTeamInterface;
use App\Modules\Team\Model\TeamInterface;
use Doctrine\ODM\MongoDB\Mapping\Annotations\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations\Field;
use Doctrine\ODM\MongoDB\Mapping\Annotations\HasLifecycleCallbacks;
use Doctrine\ODM\MongoDB\Mapping\Annotations\ReferenceOne;

#[Document(collection: 'season_team')]
#[HasLifecycleCallbacks]
class SeasonTeam implements SeasonTeamInterface
{
    use ModelUuidTrait;
    use TimestampableTrait;

    #[Field(type: 'string')]
    protected string $name;

    #[ReferenceOne(targetDocument: TeamInterface::class, inversedBy: 'seasonTeams')]
    protected TeamInterface $team;

    #[ReferenceOne(targetDocument: Season::class, inversedBy: 'seasonTeams')]
    protected SeasonInterface $season;

    public function getName(): string
    {
        return $this->name;
    }

    public function getTeam(): TeamInterface
    {
        return $this->team;
    }

    public function getSeason(): SeasonInterface
    {
        return $this->season;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function setTeam(TeamInterface $team): static
    {
        $this->team = $team;

        return $this;
    }

    public function setSeason(SeasonInterface $season): static
    {
        $this->season = $season;

        return $this;
    }
}
