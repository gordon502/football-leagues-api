<?php

namespace App\Common\Pagination;

use JsonSerializable;

readonly class Pagination implements JsonSerializable
{
    public function __construct(
        public int $total,
        public int $limit,
        public int $currentPage,
        public int $totalPages,
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'total' => $this->total,
            'limit' => $this->limit,
            'currentPage' => $this->currentPage,
            'totalPages' => $this->totalPages,
        ];
    }
}
