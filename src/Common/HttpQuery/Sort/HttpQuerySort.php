<?php

namespace App\Common\HttpQuery\Sort;

readonly class HttpQuerySort
{
    public function __construct(
        public string $field,
        public string $direction,
        public bool $isFieldReference,
    ) {
    }
}
