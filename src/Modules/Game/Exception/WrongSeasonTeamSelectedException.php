<?php

namespace App\Modules\Game\Exception;

use App\Common\Response\HttpCode;
use JsonSerializable;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class WrongSeasonTeamSelectedException extends HttpException implements JsonSerializable
{
    public function __construct(
        public $message = 'Season team does not belong to the season assigned with given round'
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
