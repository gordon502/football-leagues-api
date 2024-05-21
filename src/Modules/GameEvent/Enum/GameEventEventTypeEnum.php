<?php

namespace App\Modules\GameEvent\Enum;

enum GameEventEventTypeEnum: string
{
    case YELLOW_CARD = 'yellow_card';
    case RED_CARD = 'red_card';
    case GOAL = 'goal';
    case PENALTY = 'penalty';
}
