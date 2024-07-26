<?php

namespace App\Common\HttpQuery\Filter;

readonly class HttpQueryFilter
{
    public function __construct(
        public string $field,
        public HttpQueryFilterOperatorEnum $operator,
        public string|int|bool|null $value,
        public bool $isFieldReference,
        public bool $isValueDateTimeString,
    ) {
    }
}
