<?php

namespace App\Modules\Round\Model;

use App\Modules\Season\Model\SeasonInterface;
use DateTimeInterface;

interface RoundSetInterface
{
    public function setNumber(int $number): static;

    public function setStandardStartDate(DateTimeInterface $standardStartDate): static;

    public function setStandardEndDate(DateTimeInterface $standardEndDate): static;

    public function setSeason(SeasonInterface $season): static;
}
