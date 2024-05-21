<?php

namespace App\Modules\GameEvent\Model;

use App\Modules\Game\Model\GameInterface;

interface GameEventSetInterface
{
    public function setMinute(int $minute): static;

    public function setPartOrHalf(string $partOrHalf): static;

    public function setTeamRelated(string $teamRelated): static;

    public function setOrder(int $order): static;

    public function setEventType(string $eventType): static;

    public function setGame(GameInterface $game): static;
}
