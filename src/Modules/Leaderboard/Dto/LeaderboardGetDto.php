<?php

namespace App\Modules\Leaderboard\Dto;

use App\Common\OAAttributes\OARoleBasedProperty;
use App\Common\Serialization\RoleSerializationGroup;
use App\Modules\Leaderboard\Model\LeaderboardGetInterface;
use Symfony\Component\Serializer\Attribute\Groups;

readonly class LeaderboardGetDto
{
    public function __construct(
        private LeaderboardGetInterface $leaderboard
    ) {
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Leaderboard identifier.', RoleSerializationGroup::ALL)]
    public function getId(): string
    {
        return $this->leaderboard->getId();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Leaderboard place.', RoleSerializationGroup::ALL)]
    public function getPlace(): int
    {
        return $this->leaderboard->getPlace();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Leaderboard matches played.', RoleSerializationGroup::ALL)]
    public function getMatchesPlayed(): int
    {
        return $this->leaderboard->getMatchesPlayed();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Leaderboard points.', RoleSerializationGroup::ALL)]
    public function getPoints(): int
    {
        return $this->leaderboard->getPoints();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Leaderboard wins.', RoleSerializationGroup::ALL)]
    public function getWins(): int
    {
        return $this->leaderboard->getWins();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Leaderboard draws.', RoleSerializationGroup::ALL)]
    public function getDraws(): int
    {
        return $this->leaderboard->getDraws();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Leaderboard losses.', RoleSerializationGroup::ALL)]
    public function getLosses(): int
    {
        return $this->leaderboard->getLosses();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Leaderboard goals scored.', RoleSerializationGroup::ALL)]
    public function getGoalsScored(): int
    {
        return $this->leaderboard->getGoalsScored();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Leaderboard goals conceded.', RoleSerializationGroup::ALL)]
    public function getGoalsConceded(): int
    {
        return $this->leaderboard->getGoalsConceded();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Leaderboard home goals scored.', RoleSerializationGroup::ALL)]
    public function getHomeGoalsScored(): int
    {
        return $this->leaderboard->getHomeGoalsScored();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Leaderboard home goals conceded.', RoleSerializationGroup::ALL)]
    public function getHomeGoalsConceded(): int
    {
        return $this->leaderboard->getHomeGoalsConceded();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Leaderboard away goals scored.', RoleSerializationGroup::ALL)]
    public function getAwayGoalsScored(): int
    {
        return $this->leaderboard->getAwayGoalsScored();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Leaderboard away goals conceded.', RoleSerializationGroup::ALL)]
    public function getAwayGoalsConceded(): int
    {
        return $this->leaderboard->getAwayGoalsConceded();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Leaderboard promoted to higher division.', RoleSerializationGroup::ALL)]
    public function isPromotedToHigherDivision(): bool
    {
        return $this->leaderboard->isPromotedToHigherDivision();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Leaderboard eligible for promotion bargaining.', RoleSerializationGroup::ALL)]
    public function isEligibleForPromotionBargaining(): bool
    {
        return $this->leaderboard->isEligibleForPromotionBargaining();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Leaderboard eligible for retention bargaining.', RoleSerializationGroup::ALL)]
    public function isEligibleForRetentionBargaining(): bool
    {
        return $this->leaderboard->isEligibleForRetentionBargaining();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Leaderboard relegated to lower division.', RoleSerializationGroup::ALL)]
    public function isRelegatedToLowerDivision(): bool
    {
        return $this->leaderboard->isRelegatedToLowerDivision();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Leaderboard direct matches played.', RoleSerializationGroup::ALL)]
    public function getDirectMatchesPlayed(): ?int
    {
        return $this->leaderboard->getDirectMatchesPlayed();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Leaderboard direct matches points.', RoleSerializationGroup::ALL)]
    public function getDirectMatchesPoints(): ?int
    {
        return $this->leaderboard->getDirectMatchesPoints();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Leaderboard direct matches wins.', RoleSerializationGroup::ALL)]
    public function getDirectMatchesWins(): ?int
    {
        return $this->leaderboard->getDirectMatchesWins();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Leaderboard direct matches draws.', RoleSerializationGroup::ALL)]
    public function getDirectMatchesDraws(): ?int
    {
        return $this->leaderboard->getDirectMatchesDraws();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Leaderboard direct matches losses.', RoleSerializationGroup::ALL)]
    public function getDirectMatchesLosses(): ?int
    {
        return $this->leaderboard->getDirectMatchesLosses();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Leaderboard direct matches goals scored.', RoleSerializationGroup::ALL)]
    public function getDirectMatchesGoalsScored(): ?int
    {
        return $this->leaderboard->getDirectMatchesGoalsScored();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Leaderboard direct matches goals conceded.', RoleSerializationGroup::ALL)]
    public function getDirectMatchesGoalsConceded(): ?int
    {
        return $this->leaderboard->getDirectMatchesGoalsConceded();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Leaderboard annotation.', RoleSerializationGroup::ALL)]
    public function getAnnotation(): ?string
    {
        return $this->leaderboard->getAnnotation();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Leaderboard season team identifier.', RoleSerializationGroup::ALL)]
    public function getSeasonTeamId(): string
    {
        return $this->leaderboard->getSeasonTeam()->getId();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Leaderboard season identifier.', RoleSerializationGroup::ALL)]
    public function getSeasonId(): string
    {
        return $this->leaderboard->getSeason()->getId();
    }
}
