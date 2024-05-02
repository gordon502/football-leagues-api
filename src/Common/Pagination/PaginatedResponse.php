<?php

namespace App\Common\Pagination;

use JsonSerializable;

readonly class PaginatedResponse implements JsonSerializable
{
    public function __construct(
        public array $data,
        public Pagination $pagination,
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'data' => $this->data,
            'pagination' => $this->pagination,
        ];
    }
}
