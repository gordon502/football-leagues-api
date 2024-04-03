<?php

namespace App\Common\Response;

use JsonSerializable;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class RouteNotFoundException extends HttpException implements JsonSerializable
{
    public function __construct(
        public $message = 'Route not found.'
    ) {
        parent::__construct(
            statusCode: HttpCode::NOT_FOUND,
            message: $this->message
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => 'ROUTE_NOT_FOUND',
            'message' => $this->message,
        ];
    }
}
