<?php

namespace App\Common\Dto;

trait NotIncludedInBodyTrait
{
    private function toValueOrNull(mixed $value)
    {
        return $value instanceof NotIncludedInBody ? null : $value;
    }
}
