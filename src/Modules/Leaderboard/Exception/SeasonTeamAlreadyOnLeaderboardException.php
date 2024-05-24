<?php

namespace App\Modules\Leaderboard\Exception;

use App\Common\Response\HttpCode;
use JsonSerializable;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class SeasonTeamAlreadyOnLeaderboardException extends HttpException implements JsonSerializable
{
    public function __construct(
        public $message = 'Season team is already on a leaderboard'
    ) {
        parent::__construct(
            statusCode: HttpCode::BAD_REQUEST,
            message: $this->message
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => 'SEASON_TEAM_ALREADY_ON_LEADERBOARD',
            'message' => $this->message,
        ];
    }
}
