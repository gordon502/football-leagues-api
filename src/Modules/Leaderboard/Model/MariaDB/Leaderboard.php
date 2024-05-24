<?php

namespace App\Modules\Leaderboard\Model\MariaDB;

use App\Common\Model\MariaDB\ModelUuidTrait;
use App\Common\Timestamp\TimestampableTrait;
use App\Modules\League\Repository\MariaDB\LeagueRepository;
use App\Modules\Leaderboard\Model\LeaderboardInterface;
use App\Modules\Season\Model\MariaDB\Season;
use App\Modules\Season\Model\SeasonInterface;
use App\Modules\SeasonTeam\Model\MariaDB\SeasonTeam;
use App\Modules\SeasonTeam\Model\SeasonTeamInterface;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: LeagueRepository::class)]
#[Table(name: 'leaderboard')]
#[HasLifecycleCallbacks]
class Leaderboard implements LeaderboardInterface
{
    use ModelUuidTrait;
    use TimestampableTrait;

    #[Column(type: 'integer')]
    protected int $place;

    #[Column(type: 'integer')]
    protected int $matchesPlayed;

    #[Column(type: 'integer')]
    protected int $points;

    #[Column(type: 'integer')]
    protected int $wins;

    #[Column(type: 'integer')]
    protected int $draws;

    #[Column(type: 'integer')]
    protected int $losses;

    #[Column(type: 'integer')]
    protected int $goalsScored;

    #[Column(type: 'integer')]
    protected int $goalsConceded;

    #[Column(type: 'integer')]
    protected int $homeGoalsScored;

    #[Column(type: 'integer')]
    protected int $homeGoalsConceded;

    #[Column(type: 'integer')]
    protected int $awayGoalsScored;

    #[Column(type: 'integer')]
    protected int $awayGoalsConceded;

    #[Column(type: 'boolean')]
    protected bool $promotedToHigherDivision;

    #[Column(type: 'boolean')]
    protected bool $eligibleForPromotionBargaining;

    #[Column(type: 'boolean')]
    protected bool $eligibleForRetentionBargaining;

    #[Column(type: 'boolean')]
    protected bool $relegatedToLowerDivision;

    #[Column(type: 'integer', nullable: true)]
    protected ?int $directMatchesPlayed;

    #[Column(type: 'integer', nullable: true)]
    protected ?int $directMatchesPoints;

    #[Column(type: 'integer', nullable: true)]
    protected ?int $directMatchesWins;

    #[Column(type: 'integer', nullable: true)]
    protected ?int $directMatchesDraws;

    #[Column(type: 'integer', nullable: true)]
    protected ?int $directMatchesLosses;

    #[Column(type: 'integer', nullable: true)]
    protected ?int $directMatchesGoalsScored;

    #[Column(type: 'integer', nullable: true)]
    protected ?int $directMatchesGoalsConceded;

    #[Column(type: 'string', nullable: true)]
    protected ?string $annotation;

    #[ManyToOne(targetEntity: Season::class, inversedBy: 'leaderboard')]
    #[JoinColumn(name: 'season_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    protected SeasonInterface $season;

    #[OneToOne(targetEntity: SeasonTeam::class, inversedBy: 'leaderboard')]
    #[JoinColumn(name: 'season_team_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    protected SeasonTeamInterface $seasonTeam;

    public function getPlace(): int
    {
        return $this->place;
    }

    public function getMatchesPlayed(): int
    {
        return $this->matchesPlayed;
    }

    public function getPoints(): int
    {
        return $this->points;
    }

    public function getWins(): int
    {
        return $this->wins;
    }

    public function getDraws(): int
    {
        return $this->draws;
    }

    public function getLosses(): int
    {
        return $this->losses;
    }

    public function getGoalsScored(): int
    {
        return $this->goalsScored;
    }

    public function getGoalsConceded(): int
    {
        return $this->goalsConceded;
    }

    public function getHomeGoalsScored(): int
    {
        return $this->homeGoalsScored;
    }

    public function getHomeGoalsConceded(): int
    {
        return $this->homeGoalsConceded;
    }

    public function getAwayGoalsScored(): int
    {
        return $this->awayGoalsScored;
    }

    public function getAwayGoalsConceded(): int
    {
        return $this->awayGoalsConceded;
    }

    public function isPromotedToHigherDivision(): bool
    {
        return $this->promotedToHigherDivision;
    }

    public function isEligibleForPromotionBargaining(): bool
    {
        return $this->eligibleForPromotionBargaining;
    }

    public function isEligibleForRetentionBargaining(): bool
    {
        return $this->eligibleForRetentionBargaining;
    }

    public function isRelegatedToLowerDivision(): bool
    {
        return $this->relegatedToLowerDivision;
    }

    public function getDirectMatchesPlayed(): ?int
    {
        return $this->directMatchesPlayed;
    }

    public function getDirectMatchesPoints(): ?int
    {
        return $this->directMatchesPoints;
    }

    public function getDirectMatchesWins(): ?int
    {
        return $this->directMatchesWins;
    }

    public function getDirectMatchesDraws(): ?int
    {
        return $this->directMatchesDraws;
    }

    public function getDirectMatchesLosses(): ?int
    {
        return $this->directMatchesLosses;
    }

    public function getDirectMatchesGoalsScored(): ?int
    {
        return $this->directMatchesGoalsScored;
    }

    public function getDirectMatchesGoalsConceded(): ?int
    {
        return $this->directMatchesGoalsConceded;
    }

    public function getAnnotation(): ?string
    {
        return $this->annotation;
    }

    public function getSeason(): SeasonInterface
    {
        return $this->season;
    }

    public function getSeasonTeam(): SeasonTeamInterface
    {
        return $this->seasonTeam;
    }

    public function setPlace(int $place): static
    {
        $this->place = $place;

        return $this;
    }

    public function setMatchesPlayed(int $matchesPlayed): static
    {
        $this->matchesPlayed = $matchesPlayed;

        return $this;
    }

    public function setPoints(int $points): static
    {
        $this->points = $points;

        return $this;
    }

    public function setWins(int $wins): static
    {
        $this->wins = $wins;

        return $this;
    }

    public function setDraws(int $draws): static
    {
        $this->draws = $draws;

        return $this;
    }

    public function setLosses(int $losses): static
    {
        $this->losses = $losses;

        return $this;
    }

    public function setGoalsScored(int $goalsScored): static
    {
        $this->goalsScored = $goalsScored;

        return $this;
    }

    public function setGoalsConceded(int $goalsConceded): static
    {
        $this->goalsConceded = $goalsConceded;

        return $this;
    }

    public function setHomeGoalsScored(int $homeGoalsScored): static
    {
        $this->homeGoalsScored = $homeGoalsScored;

        return $this;
    }

    public function setHomeGoalsConceded(int $homeGoalsConceded): static
    {
        $this->homeGoalsConceded = $homeGoalsConceded;

        return $this;
    }

    public function setAwayGoalsScored(int $awayGoalsScored): static
    {
        $this->awayGoalsScored = $awayGoalsScored;

        return $this;
    }

    public function setAwayGoalsConceded(int $awayGoalsConceded): static
    {
        $this->awayGoalsConceded = $awayGoalsConceded;

        return $this;
    }

    public function setPromotedToHigherDivision(bool $promotedToHigherDivision): static
    {
        $this->promotedToHigherDivision = $promotedToHigherDivision;

        return $this;
    }

    public function setEligibleForPromotionBargaining(bool $eligibleForPromotionBargaining): static
    {
        $this->eligibleForPromotionBargaining = $eligibleForPromotionBargaining;

        return $this;
    }

    public function setEligibleForRetentionBargaining(bool $eligibleForRetentionBargaining): static
    {
        $this->eligibleForRetentionBargaining = $eligibleForRetentionBargaining;

        return $this;
    }

    public function setRelegatedToLowerDivision(bool $relegatedToLowerDivision): static
    {
        $this->relegatedToLowerDivision = $relegatedToLowerDivision;

        return $this;
    }

    public function setDirectMatchesPlayed(?int $directMatchesPlayed): static
    {
        $this->directMatchesPlayed = $directMatchesPlayed;

        return $this;
    }

    public function setDirectMatchesPoints(?int $directMatchesPoints): static
    {
        $this->directMatchesPoints = $directMatchesPoints;

        return $this;
    }

    public function setDirectMatchesWins(?int $directMatchesWins): static
    {
        $this->directMatchesWins = $directMatchesWins;

        return $this;
    }

    public function setDirectMatchesDraws(?int $directMatchesDraws): static
    {
        $this->directMatchesDraws = $directMatchesDraws;

        return $this;
    }

    public function setDirectMatchesLosses(?int $directMatchesLosses): static
    {
        $this->directMatchesLosses = $directMatchesLosses;

        return $this;
    }

    public function setDirectMatchesGoalsScored(?int $directMatchesGoalsScored): static
    {
        $this->directMatchesGoalsScored = $directMatchesGoalsScored;

        return $this;
    }

    public function setDirectMatchesGoalsConceded(?int $directMatchesGoalsConceded): static
    {
        $this->directMatchesGoalsConceded = $directMatchesGoalsConceded;

        return $this;
    }

    public function setAnnotation(?string $annotation): static
    {
        $this->annotation = $annotation;

        return $this;
    }

    public function setSeason(SeasonInterface $season): static
    {
        $this->season = $season;

        return $this;
    }

    public function setSeasonTeam(SeasonTeamInterface $seasonTeam): static
    {
        $this->seasonTeam = $seasonTeam;

        return $this;
    }
}
