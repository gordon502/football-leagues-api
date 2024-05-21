<?php

namespace App\Modules\GameEvent\Enum;

enum GameEventPartOrHalfEnum: string
{
    case FIRST_HALF = 'first_half';
    case SECOND_HALF = 'second_half';
}
