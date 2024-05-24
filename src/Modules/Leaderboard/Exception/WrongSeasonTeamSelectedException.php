<?php

namespace App\Modules\Leaderboard\Exception;

use App\Common\Response\HttpCode;
use JsonSerializable;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class WrongSeasonTeamSelectedException extends HttpException implements JsonSerializable
{
    public function __construct(
        public $message = 'Season team does not belong to the season assigned with given leaderboard'
    ) {
        parent::__construct(
            statusCode: HttpCode::BAD_REQUEST,
            message: $this->message
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => 'WRONG_SEASON_TEAM_SELECTED',
            'message' => $this->message,
        ];
    }
}
