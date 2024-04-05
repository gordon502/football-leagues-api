<?php

namespace App\Common\Response;

use JsonSerializable;

final readonly class ErrorResponse implements JsonSerializable
{
    public function __construct(
        public int $code,
        public string $name,
        public string $message
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'message' => $this->message,
        ];
    }
}
