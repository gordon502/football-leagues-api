<?php

namespace App\Modules\SeasonTeam\Model\MariaDB;

use App\Common\Model\MariaDB\ModelUuidTrait;
use App\Common\Timestamp\TimestampableTrait;
use App\Modules\Season\Model\MariaDB\Season;
use App\Modules\Season\Model\SeasonInterface;
use App\Modules\SeasonTeam\Model\SeasonTeamInterface;
use App\Modules\SeasonTeam\Repository\MariaDB\SeasonTeamRepository;
use App\Modules\Team\Model\MariaDB\Team;
use App\Modules\Team\Model\TeamInterface;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: SeasonTeamRepository::class)]
#[Table(name: 'season_team')]
#[HasLifecycleCallbacks]
class SeasonTeam implements SeasonTeamInterface
{
    use ModelUuidTrait;
    use TimestampableTrait;

    #[Column(type: 'string')]
    protected string $name;

    #[ManyToOne(targetEntity: Team::class, inversedBy: 'seasonTeams')]
    #[JoinColumn(name: 'team_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    protected TeamInterface $team;

    #[ManyToOne(targetEntity: Season::class, inversedBy: 'seasonTeams')]
    #[JoinColumn(name: 'season_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
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
