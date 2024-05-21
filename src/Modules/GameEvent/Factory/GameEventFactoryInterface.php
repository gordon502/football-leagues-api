<?php

namespace App\Modules\GameEvent\Factory;

use App\Modules\GameEvent\Model\GameEventCreatableInterface;
use App\Modules\GameEvent\Model\GameEventInterface;

interface GameEventFactoryInterface
{
    public function create(
        GameEventCreatableInterface $gameEventCreatable,
        string $modelClass
    ): GameEventInterface;
}
