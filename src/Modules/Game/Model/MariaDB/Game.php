<?php

namespace App\Modules\Game\Model\MariaDB;

use App\Common\Model\MariaDB\ModelUuidTrait;
use App\Common\Timestamp\TimestampableTrait;
use App\Modules\Game\Model\GameInterface;
use App\Modules\GameEvent\Model\MariaDB\GameEvent;
use App\Modules\Round\Model\MariaDB\Round;
use App\Modules\Round\Model\RoundInterface;
use App\Modules\Game\Repository\MariaDB\GameRepository;
use App\Modules\SeasonTeam\Model\MariaDB\SeasonTeam;
use App\Modules\SeasonTeam\Model\SeasonTeamInterface;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: GameRepository::class)]
#[Table(name: 'game')]
#[HasLifecycleCallbacks]
class Game implements GameInterface
{
    use ModelUuidTrait;
    use TimestampableTrait;

    #[Column(type: 'datetime')]
    protected DateTimeInterface $date;

    #[Column(type: 'string', nullable: true)]
    protected ?string $stadium = null;

    #[Column(type: 'integer', nullable: true)]
    protected ?int $team1ScoreHalf = null;

    #[Column(type: 'integer', nullable: true)]
    protected ?int $team2ScoreHalf = null;

    #[Column(type: 'integer', nullable: true)]
    protected ?int $team1Score = null;

    #[Column(type: 'integer', nullable: true)]
    protected ?int $team2Score = null;

    #[Column(type: 'string', nullable: true)]
    protected ?string $result = null;

    #[Column(type: 'string', nullable: true)]
    protected ?string $viewers = null;

    #[Column(type: 'string', nullable: true)]
    protected ?string $annotation = null;

    #[ManyToOne(targetEntity: Round::class, inversedBy: 'games')]
    protected RoundInterface $round;

    #[ManyToOne(targetEntity: SeasonTeam::class, inversedBy: 'gamesAsTeam1')]
    #[JoinColumn(name: 'season_team1_id', referencedColumnName: 'id', onDelete: 'SET NULL')]
    protected ?SeasonTeamInterface $seasonTeam1;

    #[ManyToOne(targetEntity: SeasonTeam::class, inversedBy: 'gamesAsTeam2')]
    #[JoinColumn(name: 'season_team2_id', referencedColumnName: 'id', onDelete: 'SET NULL')]
    protected ?SeasonTeamInterface $seasonTeam2;

    #[OneToMany(targetEntity: GameEvent::class, mappedBy: 'game', cascade: ['all'], orphanRemoval: true)]
    #[JoinColumn(name: 'game_id', referencedColumnName: 'id')]
    protected Collection $gameEvents;

    public function __construct()
    {
        $this->gameEvents = new ArrayCollection();
    }

    public function getDate(): DateTimeInterface
    {
        return $this->date;
    }

    public function getStadium(): ?string
    {
        return $this->stadium;
    }

    public function getTeam1ScoreHalf(): ?int
    {
        return $this->team1ScoreHalf;
    }

    public function getTeam2ScoreHalf(): ?int
    {
        return $this->team2ScoreHalf;
    }

    public function getTeam1Score(): ?int
    {
        return $this->team1Score;
    }

    public function getTeam2Score(): ?int
    {
        return $this->team2Score;
    }

    public function getResult(): ?string
    {
        return $this->result;
    }

    public function getViewers(): ?string
    {
        return $this->viewers;
    }

    public function getAnnotation(): ?string
    {
        return $this->annotation;
    }

    public function getRound(): RoundInterface
    {
        return $this->round;
    }

    public function getSeasonTeam1(): ?SeasonTeamInterface
    {
        return $this->seasonTeam1;
    }

    public function getSeasonTeam2(): ?SeasonTeamInterface
    {
        return $this->seasonTeam2;
    }

    public function getGameEvents(): Collection
    {
        return $this->gameEvents;
    }

    public function setDate(DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function setStadium(?string $stadium): static
    {
        $this->stadium = $stadium;

        return $this;
    }

    public function setTeam1ScoreHalf(?int $team1ScoreHalf): static
    {
        $this->team1ScoreHalf = $team1ScoreHalf;

        return $this;
    }

    public function setTeam2ScoreHalf(?int $team2ScoreHalf): static
    {
        $this->team2ScoreHalf = $team2ScoreHalf;

        return $this;
    }

    public function setTeam1Score(?int $team1Score): static
    {
        $this->team1Score = $team1Score;

        return $this;
    }

    public function setTeam2Score(?int $team2Score): static
    {
        $this->team2Score = $team2Score;

        return $this;
    }

    public function setResult(?string $matchResult): static
    {
        $this->result = $matchResult;

        return $this;
    }

    public function setViewers(?string $viewers): static
    {
        $this->viewers = $viewers;

        return $this;
    }

    public function setAnnotation(?string $annotation): static
    {
        $this->annotation = $annotation;

        return $this;
    }

    public function setRound(RoundInterface $round): static
    {
        $this->round = $round;

        return $this;
    }

    public function setSeasonTeam1(?SeasonTeamInterface $seasonTeam): static
    {
        $this->seasonTeam1 = $seasonTeam;

        return $this;
    }

    public function setSeasonTeam2(?SeasonTeamInterface $seasonTeam): static
    {
        $this->seasonTeam2 = $seasonTeam;

        return $this;
    }
}
