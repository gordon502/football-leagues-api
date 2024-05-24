<?php

namespace App\Modules\Leaderboard\Dto;

use App\Common\Dto\DtoPropertyRelatedToEntity;
use App\Common\Dto\NotIncludedInBody;
use App\Common\Dto\NotIncludedInBodyTrait;
use App\Common\OAAttributes\OARoleBasedProperty;
use App\Common\Serialization\RoleSerializationGroup;
use App\Modules\Leaderboard\Model\LeaderboardUpdatableInterface;
use App\Modules\Season\Model\SeasonInterface;
use App\Modules\SeasonTeam\Model\SeasonTeamInterface;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

readonly class LeaderboardUpdateDto implements LeaderboardUpdatableInterface
{
    use NotIncludedInBodyTrait;

    public function __construct(
        private string|null|NotIncludedInBody $place = new NotIncludedInBody(),
        private string|null|NotIncludedInBody $matchesPlayed = new NotIncludedInBody(),
        private string|null|NotIncludedInBody $points = new NotIncludedInBody(),
        private string|null|NotIncludedInBody $wins = new NotIncludedInBody(),
        private string|null|NotIncludedInBody $draws = new NotIncludedInBody(),
        private string|null|NotIncludedInBody $losses = new NotIncludedInBody(),
        private string|null|NotIncludedInBody $goalsScored = new NotIncludedInBody(),
        private string|null|NotIncludedInBody $goalsConceded = new NotIncludedInBody(),
        private string|null|NotIncludedInBody $homeGoalsScored = new NotIncludedInBody(),
        private string|null|NotIncludedInBody $homeGoalsConceded = new NotIncludedInBody(),
        private string|null|NotIncludedInBody $awayGoalsScored = new NotIncludedInBody(),
        private string|null|NotIncludedInBody $awayGoalsConceded = new NotIncludedInBody(),
        private string|null|NotIncludedInBody $promotedToHigherDivision = new NotIncludedInBody(),
        private string|null|NotIncludedInBody $eligibleForPromotionBargaining = new NotIncludedInBody(),
        private string|null|NotIncludedInBody $eligibleForRetentionBargaining = new NotIncludedInBody(),
        private string|null|NotIncludedInBody $relegatedToLowerDivision = new NotIncludedInBody(),
        private string|null|NotIncludedInBody $directMatchesPlayed = new NotIncludedInBody(),
        private string|null|NotIncludedInBody $directMatchesPoints = new NotIncludedInBody(),
        private string|null|NotIncludedInBody $directMatchesWins = new NotIncludedInBody(),
        private string|null|NotIncludedInBody $directMatchesDraws = new NotIncludedInBody(),
        private string|null|NotIncludedInBody $directMatchesLosses = new NotIncludedInBody(),
        private string|null|NotIncludedInBody $directMatchesGoalsScored = new NotIncludedInBody(),
        private string|null|NotIncludedInBody $directMatchesGoalsConceded = new NotIncludedInBody(),
        private string|null|NotIncludedInBody $annotation = new NotIncludedInBody(),
        private string|null|NotIncludedInBody $seasonId = new NotIncludedInBody(),
        private string|null|NotIncludedInBody $seasonTeamId = new NotIncludedInBody()
    ) {
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
        return $this->toValueOrNull($this->place);
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
        return $this->toValueOrNull($this->matchesPlayed);
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
        return $this->toValueOrNull($this->points);
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
        return $this->toValueOrNull($this->wins);
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
        return $this->toValueOrNull($this->draws);
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
        return $this->toValueOrNull($this->losses);
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
        return $this->toValueOrNull($this->goalsScored);
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
        return $this->toValueOrNull($this->goalsConceded);
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
        return $this->toValueOrNull($this->homeGoalsScored);
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
        return $this->toValueOrNull($this->homeGoalsConceded);
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
        return $this->toValueOrNull($this->awayGoalsScored);
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
        return $this->toValueOrNull($this->awayGoalsConceded);
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
        return $this->toValueOrNull($this->promotedToHigherDivision);
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
        return $this->toValueOrNull($this->eligibleForPromotionBargaining);
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
        return $this->toValueOrNull($this->eligibleForRetentionBargaining);
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
        return $this->toValueOrNull($this->relegatedToLowerDivision);
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
        return $this->toValueOrNull($this->directMatchesPlayed);
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
        return $this->toValueOrNull($this->directMatchesPoints);
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
        return $this->toValueOrNull($this->directMatchesWins);
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
        return $this->toValueOrNull($this->directMatchesDraws);
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
        return $this->toValueOrNull($this->directMatchesLosses);
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
        return $this->toValueOrNull($this->directMatchesGoalsScored);
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
        return $this->toValueOrNull($this->directMatchesGoalsConceded);
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
        return $this->toValueOrNull($this->annotation);
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
    #[DtoPropertyRelatedToEntity(SeasonInterface::class)]
    #[Assert\NotBlank]
    #[Assert\Uuid]
    public function getSeasonId(): string|null
    {
        return $this->toValueOrNull($this->seasonId);
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
    #[DtoPropertyRelatedToEntity(SeasonTeamInterface::class)]
    #[Assert\NotBlank]
    #[Assert\Uuid]
    public function getSeasonTeamId(): string|null
    {
        return $this->toValueOrNull($this->seasonTeamId);
    }
}
