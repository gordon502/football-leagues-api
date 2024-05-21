<?php

namespace App\Modules\GameEvent\Model;

interface GameEventCreatableInterface
{
    public function getMinute(): ?int;

    public function getPartOrHalf(): ?string;

    public function getTeamRelated(): ?string;

    public function getOrder(): ?int;

    public function getEventType(): ?string;

    public function getGameId(): ?string;
}
