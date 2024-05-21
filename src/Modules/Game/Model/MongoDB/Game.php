<?php

namespace App\Modules\Game\Model\MongoDB;

use App\Common\Model\MongoDB\ModelUuidTrait;
use App\Common\Timestamp\TimestampableTrait;
use App\Modules\Game\Model\GameInterface;
use App\Modules\GameEvent\Model\MongoDB\GameEvent;
use App\Modules\Round\Model\MongoDB\Round;
use App\Modules\Round\Model\RoundInterface;
use App\Modules\SeasonTeam\Model\MongoDB\SeasonTeam;
use App\Modules\SeasonTeam\Model\SeasonTeamInterface;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations\Field;
use Doctrine\ODM\MongoDB\Mapping\Annotations\HasLifecycleCallbacks;
use Doctrine\ODM\MongoDB\Mapping\Annotations\ReferenceMany;
use Doctrine\ODM\MongoDB\Mapping\Annotations\ReferenceOne;

#[Document(collection: 'game')]
#[HasLifecycleCallbacks]
class Game implements GameInterface
{
    use ModelUuidTrait;
    use TimestampableTrait;

    #[Field(type: 'date')]
    protected DateTimeInterface $date;

    #[Field(type: 'string', nullable: true)]
    protected ?string $stadium = null;

    #[Field(type: 'int', nullable: true)]
    protected ?int $team1ScoreHalf = null;

    #[Field(type: 'int', nullable: true)]
    protected ?int $team2ScoreHalf = null;

    #[Field(type: 'int', nullable: true)]
    protected ?int $team1Score = null;

    #[Field(type: 'int', nullable: true)]
    protected ?int $team2Score = null;

    #[Field(type: 'string', nullable: true)]
    protected ?string $result = null;

    #[Field(type: 'string', nullable: true)]
    protected ?string $viewers = null;

    #[Field(type: 'string', nullable: true)]
    protected ?string $annotation = null;

    #[ReferenceOne(targetDocument: Round::class, inversedBy: 'games')]
    protected RoundInterface $round;

    #[ReferenceOne(nullable: true, targetDocument: SeasonTeam::class)]
    protected ?SeasonTeamInterface $seasonTeam1;

    #[ReferenceOne(nullable: true, targetDocument: SeasonTeam::class)]
    protected ?SeasonTeamInterface $seasonTeam2;

    #[ReferenceMany(targetDocument: GameEvent::class, cascade: ['all'], orphanRemoval: true, mappedBy: 'game')]
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
