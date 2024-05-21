<?php

namespace App\Modules\Game\Model;

use App\Modules\Round\Model\RoundInterface;
use App\Modules\SeasonTeam\Model\SeasonTeamInterface;
use DateTimeInterface;

interface GameGetInterface
{
    public function getDate(): DateTimeInterface;

    public function getStadium(): ?string;

    public function getTeam1ScoreHalf(): ?int;

    public function getTeam2ScoreHalf(): ?int;

    public function getTeam1Score(): ?int;

    public function getTeam2Score(): ?int;

    public function getResult(): ?string;

    public function getViewers(): ?string;

    public function getAnnotation(): ?string;

    public function getRound(): RoundInterface;

    public function getSeasonTeam1(): ?SeasonTeamInterface;

    public function getSeasonTeam2(): ?SeasonTeamInterface;
}
