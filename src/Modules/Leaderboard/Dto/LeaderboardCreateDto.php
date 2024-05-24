<?php

namespace App\Modules\Leaderboard\Dto;

use App\Common\OAAttributes\OARoleBasedProperty;
use App\Common\Serialization\RoleSerializationGroup;
use App\Modules\Leaderboard\Model\LeaderboardCreatableInterface;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class LeaderboardCreateDto implements LeaderboardCreatableInterface
{
    private int|null $place;
    private int|null $matchesPlayed;
    private int|null $points;
    private int|null $wins;
    private int|null $draws;
    private int|null $losses;
    private int|null $goalsScored;
    private int|null $goalsConceded;
    private int|null $homeGoalsScored;
    private int|null $homeGoalsConceded;
    private int|null $awayGoalsScored;
    private int|null $awayGoalsConceded;
    protected bool|null $promotedToHigherDivision;
    protected bool|null $eligibleForPromotionBargaining;
    protected bool|null $eligibleForRetentionBargaining;
    protected bool|null $relegatedToLowerDivision;
    protected int|null $directMatchesPlayed;
    protected int|null $directMatchesPoints;
    protected int|null $directMatchesWins;
    protected int|null $directMatchesDraws;
    protected int|null $directMatchesLosses;
    protected int|null $directMatchesGoalsScored;
    protected int|null $directMatchesGoalsConceded;
    protected string|null $annotation;
    protected string|null $seasonId;
    private string|null $seasonTeamId;

    public function __construct(
        int|null $place,
        int|null $matchesPlayed,
        int|null $points,
        int|null $wins,
        int|null $draws,
        int|null $losses,
        int|null $goalsScored,
        int|null $goalsConceded,
        int|null $homeGoalsScored,
        int|null $homeGoalsConceded,
        int|null $awayGoalsScored,
        int|null $awayGoalsConceded,
        bool|null $promotedToHigherDivision,
        bool|null $eligibleForPromotionBargaining,
        bool|null $eligibleForRetentionBargaining,
        bool|null $relegatedToLowerDivision,
        int|null $directMatchesPlayed,
        int|null $directMatchesPoints,
        int|null $directMatchesWins,
        int|null $directMatchesDraws,
        int|null $directMatchesLosses,
        int|null $directMatchesGoalsScored,
        int|null $directMatchesGoalsConceded,
        string|null $annotation,
        string|null $seasonId,
        string|null $seasonTeamId
    ) {
        $this->place = $place;
        $this->matchesPlayed = $matchesPlayed;
        $this->points = $points;
        $this->wins = $wins;
        $this->draws = $draws;
        $this->losses = $losses;
        $this->goalsScored = $goalsScored;
        $this->goalsConceded = $goalsConceded;
        $this->homeGoalsScored = $homeGoalsScored;
        $this->homeGoalsConceded = $homeGoalsConceded;
        $this->awayGoalsScored = $awayGoalsScored;
        $this->awayGoalsConceded = $awayGoalsConceded;
        $this->promotedToHigherDivision = $promotedToHigherDivision;
        $this->eligibleForPromotionBargaining = $eligibleForPromotionBargaining;
        $this->eligibleForRetentionBargaining = $eligibleForRetentionBargaining;
        $this->relegatedToLowerDivision = $relegatedToLowerDivision;
        $this->directMatchesPlayed = $directMatchesPlayed;
        $this->directMatchesPoints = $directMatchesPoints;
        $this->directMatchesWins = $directMatchesWins;
        $this->directMatchesDraws = $directMatchesDraws;
        $this->directMatchesLosses = $directMatchesLosses;
        $this->directMatchesGoalsScored = $directMatchesGoalsScored;
        $this->directMatchesGoalsConceded = $directMatchesGoalsConceded;
        $this->annotation = $annotation;
        $this->seasonId = $seasonId;
        $this->seasonTeamId = $seasonTeamId;
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Place.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\NotBlank]
    #[Assert\Type('int')]
    #[Assert\Positive]
    public function getPlace(): int|null
    {
        return $this->place;
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Matches played.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\NotBlank]
    #[Assert\Type('int')]
    #[Assert\PositiveOrZero]
    public function getMatchesPlayed(): int|null
    {
        return $this->matchesPlayed;
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Points.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\NotBlank]
    #[Assert\Type('int')]
    #[Assert\PositiveOrZero]
    public function getPoints(): int|null
    {
        return $this->points;
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Wins.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\NotBlank]
    #[Assert\Type('int')]
    #[Assert\PositiveOrZero]
    public function getWins(): int|null
    {
        return $this->wins;
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Draws.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\NotBlank]
    #[Assert\Type('int')]
    #[Assert\PositiveOrZero]
    public function getDraws(): int|null
    {
        return $this->draws;
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Losses.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\NotBlank]
    #[Assert\Type('int')]
    #[Assert\PositiveOrZero]
    public function getLosses(): int|null
    {
        return $this->losses;
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Goals scored.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\NotBlank]
    #[Assert\Type('int')]
    #[Assert\PositiveOrZero]
    public function getGoalsScored(): int|null
    {
        return $this->goalsScored;
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Goals conceded.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\NotBlank]
    #[Assert\Type('int')]
    #[Assert\PositiveOrZero]
    public function getGoalsConceded(): int|null
    {
        return $this->goalsConceded;
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Home goals scored.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\NotBlank]
    #[Assert\Type('int')]
    #[Assert\PositiveOrZero]
    public function getHomeGoalsScored(): int|null
    {
        return $this->homeGoalsScored;
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Home goals conceded.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\NotBlank]
    #[Assert\Type('int')]
    #[Assert\PositiveOrZero]
    public function getHomeGoalsConceded(): int|null
    {
        return $this->homeGoalsConceded;
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Away goals scored.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\NotBlank]
    #[Assert\Type('int')]
    #[Assert\PositiveOrZero]
    public function getAwayGoalsScored(): int|null
    {
        return $this->awayGoalsScored;
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Away goals conceded.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\NotBlank]
    #[Assert\Type('int')]
    #[Assert\PositiveOrZero]
    public function getAwayGoalsConceded(): int|null
    {
        return $this->awayGoalsConceded;
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Promoted to higher division.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\NotBlank]
    #[Assert\Type('bool')]
    public function isPromotedToHigherDivision(): bool|null
    {
        return $this->promotedToHigherDivision;
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Eligible for promotion bargaining.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\NotBlank]
    #[Assert\Type('bool')]
    public function isEligibleForPromotionBargaining(): bool|null
    {
        return $this->eligibleForPromotionBargaining;
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Eligible for retention bargaining.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\NotBlank]
    #[Assert\Type('bool')]
    public function isEligibleForRetentionBargaining(): bool|null
    {
        return $this->eligibleForRetentionBargaining;
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Relegated to lower division.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\NotBlank]
    #[Assert\Type('bool')]
    public function isRelegatedToLowerDivision(): bool|null
    {
        return $this->relegatedToLowerDivision;
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Direct matches played.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\Type(['int', 'null'])]
    #[Assert\PositiveOrZero]
    public function getDirectMatchesPlayed(): int|null
    {
        return $this->directMatchesPlayed;
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Direct matches points.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\Type(['int', 'null'])]
    #[Assert\PositiveOrZero]
    public function getDirectMatchesPoints(): int|null
    {
        return $this->directMatchesPoints;
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Direct matches wins.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\Type(['int', 'null'])]
    #[Assert\PositiveOrZero]
    public function getDirectMatchesWins(): int|null
    {
        return $this->directMatchesWins;
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Direct matches draws.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\Type(['int', 'null'])]
    #[Assert\PositiveOrZero]
    public function getDirectMatchesDraws(): int|null
    {
        return $this->directMatchesDraws;
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Direct matches losses.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\Type(['int', 'null'])]
    #[Assert\PositiveOrZero]
    public function getDirectMatchesLosses(): int|null
    {
        return $this->directMatchesLosses;
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Direct matches goals scored.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\Type(['int', 'null'])]
    #[Assert\PositiveOrZero]
    public function getDirectMatchesGoalsScored(): int|null
    {
        return $this->directMatchesGoalsScored;
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Direct matches goals conceded.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\Type(['int', 'null'])]
    #[Assert\PositiveOrZero]
    public function getDirectMatchesGoalsConceded(): int|null
    {
        return $this->directMatchesGoalsConceded;
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Annotation.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\Type('string')]
    #[Assert\Length(max: 255)]
    public function getAnnotation(): string|null
    {
        return $this->annotation;
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Season ID.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\NotBlank]
    #[Assert\Uuid]
    public function getSeasonId(): string|null
    {
        return $this->seasonId;
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Season team ID.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\NotBlank]
    #[Assert\Uuid]
    public function getSeasonTeamId(): string|null
    {
        return $this->seasonTeamId;
    }
}
