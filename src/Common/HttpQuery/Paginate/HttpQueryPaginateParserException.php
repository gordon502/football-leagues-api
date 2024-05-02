<?php

namespace App\Common\HttpQuery\Paginate;

use App\Common\Response\HttpCode;
use JsonSerializable;
use Symfony\Component\HttpKernel\Exception\HttpException;

class HttpQueryPaginateParserException extends HttpException implements JsonSerializable
{
    public function __construct()
    {
        parent::__construct(
            statusCode: HttpCode::BAD_REQUEST,
            message:
                "Paginate query parser failed to parse params, page and limit must be positive integers if present."
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => 'QUERY_PAGINATE_EXCEPTION',
            'message' => $this->message,
        ];
    }
}
