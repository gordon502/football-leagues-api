<?php

namespace App\Modules\Leaderboard\Model;

use App\Modules\Season\Model\SeasonInterface;
use App\Modules\SeasonTeam\Model\SeasonTeamInterface;

interface LeaderboardSetInterface
{
    public function setPlace(int $place): static;

    public function setMatchesPlayed(int $matchesPlayed): static;

    public function setPoints(int $points): static;

    public function setWins(int $wins): static;

    public function setDraws(int $draws): static;

    public function setLosses(int $losses): static;

    public function setGoalsScored(int $goalsScored): static;

    public function setGoalsConceded(int $goalsConceded): static;

    public function setHomeGoalsScored(int $homeGoalsScored): static;

    public function setHomeGoalsConceded(int $homeGoalsConceded): static;

    public function setAwayGoalsScored(int $awayGoalsScored): static;

    public function setAwayGoalsConceded(int $awayGoalsConceded): static;

    public function setPromotedToHigherDivision(bool $promotedToHigherDivision): static;

    public function setEligibleForPromotionBargaining(bool $eligibleForPromotionBargaining): static;

    public function setEligibleForRetentionBargaining(bool $eligibleForRetentionBargaining): static;

    public function setRelegatedToLowerDivision(bool $relegatedToLowerDivision): static;

    public function setDirectMatchesPlayed(?int $directMatchesPlayed): static;

    public function setDirectMatchesPoints(?int $directMatchesPoints): static;

    public function setDirectMatchesWins(?int $directMatchesWins): static;

    public function setDirectMatchesDraws(?int $directMatchesDraws): static;

    public function setDirectMatchesLosses(?int $directMatchesLosses): static;

    public function setDirectMatchesGoalsScored(?int $directMatchesGoalsScored): static;

    public function setDirectMatchesGoalsConceded(?int $directMatchesGoalsConceded): static;

    public function setAnnotation(?string $annotation): static;

    public function setSeasonTeam(SeasonTeamInterface $seasonTeam): static;

    public function setSeason(SeasonInterface $season): static;
}
