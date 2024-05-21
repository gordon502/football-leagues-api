<?php

namespace App\Modules\Game\Factory;

use App\Modules\Game\Model\GameCreatableInterface;
use App\Modules\Game\Model\GameInterface;

interface GameFactoryInterface
{
    public function create(
        GameCreatableInterface $matchCreatable,
        string $modelClass
    ): GameInterface;
}
