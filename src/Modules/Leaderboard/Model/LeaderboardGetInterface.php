<?php

namespace App\Modules\Leaderboard\Model;

use App\Modules\Season\Model\SeasonInterface;
use App\Modules\SeasonTeam\Model\SeasonTeamInterface;

interface LeaderboardGetInterface
{
    public function getId(): string;

    public function getPlace(): int;

    public function getMatchesPlayed(): int;

    public function getPoints(): int;

    public function getWins(): int;

    public function getDraws(): int;

    public function getLosses(): int;

    public function getGoalsScored(): int;

    public function getGoalsConceded(): int;

    public function getHomeGoalsScored(): int;

    public function getHomeGoalsConceded(): int;

    public function getAwayGoalsScored(): int;

    public function getAwayGoalsConceded(): int;

    public function isPromotedToHigherDivision(): bool;

    public function isEligibleForPromotionBargaining(): bool;

    public function isEligibleForRetentionBargaining(): bool;

    public function isRelegatedToLowerDivision(): bool;

    public function getDirectMatchesPlayed(): ?int;

    public function getDirectMatchesPoints(): ?int;

    public function getDirectMatchesWins(): ?int;

    public function getDirectMatchesDraws(): ?int;

    public function getDirectMatchesLosses(): ?int;

    public function getDirectMatchesGoalsScored(): ?int;

    public function getDirectMatchesGoalsConceded(): ?int;

    public function getAnnotation(): ?string;

    public function getSeasonTeam(): SeasonTeamInterface;

    public function getSeason(): SeasonInterface;
}
