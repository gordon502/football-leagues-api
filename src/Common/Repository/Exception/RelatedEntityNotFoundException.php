<?php

namespace App\Common\Repository\Exception;

use App\Common\Response\HttpCode;
use JsonSerializable;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class RelatedEntityNotFoundException extends HttpException implements JsonSerializable
{
    public function __construct(
        public $message = 'Related entity not found.'
    ) {
        parent::__construct(
            statusCode: HttpCode::BAD_REQUEST,
            message: $this->message
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => 'RELATED_ENTITY_NOT_FOUND',
            'message' => $this->message,
        ];
    }
}
