<?php

namespace App\Modules\Game\Enum;

enum GameResultEnum: string
{
    case TEAM_1 = 'team_1';
    case DRAW = 'draw';
    case TEAM_2 = 'team_2';
    case NOT_PLAYED = 'not_played';
    case CANCELLED = 'cancelled';
    case POSTPONED = 'postponed';
    case TEAM_1_WALKOVER = 'team_1_walkover';
    case TEAM_2_WALKOVER = 'team_2_walkover';
}
