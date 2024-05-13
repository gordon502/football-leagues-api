<?php

namespace App\Modules\Round\Model;

interface RoundCreatableInterface
{
    public function getNumber(): int | null;

    public function getStandardStartDate(): string | null;

    public function getStandardEndDate(): string | null;

    public function getSeasonId(): string | null;
}
