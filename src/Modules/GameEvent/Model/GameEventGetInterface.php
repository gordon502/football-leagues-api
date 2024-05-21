<?php

namespace App\Modules\GameEvent\Model;

use App\Modules\Game\Model\GameInterface;

interface GameEventGetInterface
{
    public function getId(): string;

    public function getMinute(): int;

    public function getPartOrHalf(): string;

    public function getTeamRelated(): string;

    public function getOrder(): int;

    public function getEventType(): string;

    public function getGame(): GameInterface;
}
