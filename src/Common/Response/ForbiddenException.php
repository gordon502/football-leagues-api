<?php

namespace App\Common\Response;

use JsonSerializable;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class ForbiddenException extends HttpException implements JsonSerializable
{
    public function __construct(
        public $message = 'Forbidden.'
    ) {
        parent::__construct(
            statusCode: HttpCode::FORBIDDEN,
            message: $this->message
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => 'FORBIDDEN',
            'message' => $this->message,
        ];
    }
}
