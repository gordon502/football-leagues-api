<?php

namespace App\Common\Response;

use JsonSerializable;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class UnprocessableEntityException extends HttpException implements JsonSerializable
{
    public function __construct(
        public $message = 'Check your DTO if all values matches documentation specification.'
    ) {
        parent::__construct(
            statusCode: HttpCode::UNPROCESSABLE_ENTITY,
            message: $this->message
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => 'UNPROCESSABLE_ENTITY',
            'message' => $this->message,
        ];
    }
}
