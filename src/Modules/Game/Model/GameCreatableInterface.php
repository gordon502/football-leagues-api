<?php

namespace App\Modules\Game\Model;

interface GameCreatableInterface
{
    public function getDate(): string | null;

    public function getStadium(): string | null;

    public function getTeam1ScoreHalf(): int | null;

    public function getTeam2ScoreHalf(): int | null;

    public function getTeam1Score(): int | null;

    public function getTeam2Score(): int | null;

    public function getResult(): string | null;

    public function getViewers(): string | null;

    public function getAnnotation(): string | null;

    public function getRoundId(): string | null;

    public function getSeasonTeam1Id(): string | null;

    public function getSeasonTeam2Id(): string | null;
}
