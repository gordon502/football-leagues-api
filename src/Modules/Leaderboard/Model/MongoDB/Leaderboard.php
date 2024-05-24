<?php

namespace App\Modules\Leaderboard\Model\MongoDB;

use App\Common\Model\MongoDB\ModelUuidTrait;
use App\Common\Timestamp\TimestampableTrait;
use App\Modules\Leaderboard\Model\LeaderboardInterface;
use App\Modules\Season\Model\MongoDB\Season;
use App\Modules\Season\Model\SeasonInterface;
use App\Modules\SeasonTeam\Model\MongoDB\SeasonTeam;
use App\Modules\SeasonTeam\Model\SeasonTeamInterface;
use Doctrine\ODM\MongoDB\Mapping\Annotations\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations\Field;
use Doctrine\ODM\MongoDB\Mapping\Annotations\HasLifecycleCallbacks;
use Doctrine\ODM\MongoDB\Mapping\Annotations\ReferenceOne;

#[Document(collection: 'leaderboard')]
#[HasLifecycleCallbacks]
class Leaderboard implements LeaderboardInterface
{
    use ModelUuidTrait;
    use TimestampableTrait;

    #[Field(type: 'int')]
    protected int $place;

    #[Field(type: 'int')]
    protected int $matchesPlayed;

    #[Field(type: 'int')]
    protected int $points;

    #[Field(type: 'int')]
    protected int $wins;

    #[Field(type: 'int')]
    protected int $draws;

    #[Field(type: 'int')]
    protected int $losses;

    #[Field(type: 'int')]
    protected int $goalsScored;

    #[Field(type: 'int')]
    protected int $goalsConceded;

    #[Field(type: 'int')]
    protected int $homeGoalsScored;

    #[Field(type: 'int')]
    protected int $homeGoalsConceded;

    #[Field(type: 'int')]
    protected int $awayGoalsScored;

    #[Field(type: 'int')]
    protected int $awayGoalsConceded;

    #[Field(type: 'boolean')]
    protected bool $promotedToHigherDivision;

    #[Field(type: 'boolean')]
    protected bool $eligibleForPromotionBargaining;

    #[Field(type: 'boolean')]
    protected bool $eligibleForRetentionBargaining;

    #[Field(type: 'boolean')]
    protected bool $relegatedToLowerDivision;

    #[Field(type: 'integer', nullable: true)]
    protected ?int $directMatchesPlayed;

    #[Field(type: 'integer', nullable: true)]
    protected ?int $directMatchesPoints;

    #[Field(type: 'integer', nullable: true)]
    protected ?int $directMatchesWins;

    #[Field(type: 'integer', nullable: true)]
    protected ?int $directMatchesDraws;

    #[Field(type: 'integer', nullable: true)]
    protected ?int $directMatchesLosses;

    #[Field(type: 'integer', nullable: true)]
    protected ?int $directMatchesGoalsScored;

    #[Field(type: 'integer', nullable: true)]
    protected ?int $directMatchesGoalsConceded;

    #[Field(type: 'string', nullable: true)]
    protected ?string $annotation;

    #[ReferenceOne(targetDocument: Season::class, inversedBy: 'leaderboards')]
    protected SeasonInterface $season;

    #[ReferenceOne(targetDocument: SeasonTeam::class, inversedBy: 'leaderboard')]
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
