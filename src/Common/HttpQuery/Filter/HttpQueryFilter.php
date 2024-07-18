<?php

namespace App\Common\HttpQuery\Filter;

readonly class HttpQueryFilter
{
    public function __construct(
        public string $field,
        public string $operator,
        public string|int|null $value,
        public bool $isFieldReference,
        public bool $isValueDateTimeString,
    ) {
    }
}
