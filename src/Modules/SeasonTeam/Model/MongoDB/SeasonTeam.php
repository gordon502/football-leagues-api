<?php

namespace App\Modules\SeasonTeam\Model\MongoDB;

use App\Common\Model\MongoDB\ModelUuidTrait;
use App\Common\Timestamp\TimestampableTrait;
use App\Modules\Game\Model\MongoDB\Game;
use App\Modules\Season\Model\MongoDB\Season;
use App\Modules\Season\Model\SeasonInterface;
use App\Modules\SeasonTeam\Model\SeasonTeamInterface;
use App\Modules\Team\Model\MongoDB\Team;
use App\Modules\Team\Model\TeamInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations\Field;
use Doctrine\ODM\MongoDB\Mapping\Annotations\HasLifecycleCallbacks;
use Doctrine\ODM\MongoDB\Mapping\Annotations\ReferenceMany;
use Doctrine\ODM\MongoDB\Mapping\Annotations\ReferenceOne;

#[Document(collection: 'season_team')]
#[HasLifecycleCallbacks]
class SeasonTeam implements SeasonTeamInterface
{
    use ModelUuidTrait;
    use TimestampableTrait;

    #[Field(type: 'string')]
    protected string $name;

    #[ReferenceOne(targetDocument: Team::class, inversedBy: 'seasonTeams')]
    protected TeamInterface $team;

    #[ReferenceOne(targetDocument: Season::class, inversedBy: 'seasonTeams')]
    protected SeasonInterface $season;

    #[ReferenceMany(targetDocument: Game::class, mappedBy: 'seasonTeam1')]
    protected Collection $gamesAsTeam1;

    #[ReferenceMany(targetDocument: Game::class, mappedBy: 'seasonTeam2')]
    protected Collection $gamesAsTeam2;

    public function __construct()
    {
        $this->gamesAsTeam1 = new ArrayCollection();
        $this->gamesAsTeam2 = new ArrayCollection();
    }

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

    public function getGamesAsTeam1(): Collection
    {
        return $this->gamesAsTeam1;
    }

    public function getGamesAsTeam2(): Collection
    {
        return $this->gamesAsTeam2;
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
