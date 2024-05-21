<?php

namespace App\Modules\Game\Model;

use App\Modules\Game\Enum\GameResultEnum;
use App\Modules\Round\Model\RoundInterface;
use App\Modules\SeasonTeam\Model\SeasonTeamInterface;
use DateTimeInterface;

interface GameSetInterface
{
    public function setDate(DateTimeInterface $date): static;

    public function setStadium(?string $stadium): static;

    public function setTeam1ScoreHalf(?int $team1ScoreHalf): static;

    public function setTeam2ScoreHalf(?int $team2ScoreHalf): static;

    public function setTeam1Score(?int $team1Score): static;

    public function setTeam2Score(?int $team2Score): static;

    public function setResult(?GameResultEnum $matchResult): static;

    public function setViewers(?string $viewers): static;

    public function setAnnotation(?string $annotation): static;

    public function setRound(RoundInterface $round): static;

    public function setSeasonTeam1(?SeasonTeamInterface $seasonTeam): static;

    public function setSeasonTeam2(?SeasonTeamInterface $seasonTeam): static;
}
