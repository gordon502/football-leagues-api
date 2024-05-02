<?php

namespace App\Common\Pagination;

use App\Common\Response\HttpCode;
use JsonSerializable;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PaginationOutOfBoundException extends HttpException implements JsonSerializable
{
    public function __construct()
    {
        parent::__construct(
            statusCode: HttpCode::BAD_REQUEST,
            message:
            'Provided page is out of bound, '
            . 'page must be a positive integer if present and cannot exceed total queried pages.'
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => 'PAGINATION_OUT_OF_BOUND_EXCEPTION',
            'message' => $this->message,
        ];
    }
}
