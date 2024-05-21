<?php

namespace App\Modules\Round\Model;

use App\Modules\Season\Model\SeasonInterface;
use DateTimeInterface;
use Doctrine\Common\Collections\Collection;

interface RoundGetInterface
{
    public function getId(): string;

    public function getNumber(): int;

    public function getStandardStartDate(): DateTimeInterface;

    public function getStandardEndDate(): DateTimeInterface;

    public function getSeason(): SeasonInterface;

    public function getGames(): Collection;
}
