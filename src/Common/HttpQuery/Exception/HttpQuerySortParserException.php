<?php

namespace App\Common\HttpQuery\Exception;

use App\Common\Response\HttpCode;
use JsonSerializable;
use Symfony\Component\HttpKernel\Exception\HttpException;

class HttpQuerySortParserException extends HttpException implements JsonSerializable
{
    public function __construct(string $query)
    {
        parent::__construct(
            statusCode: HttpCode::BAD_REQUEST,
            message:
                "Sort query parser failed to parse query, some fields are not available "
                . "or it must be in the right format: $query"
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => 'QUERY_SORT_EXCEPTION',
            'message' => $this->message,
        ];
    }
}
