<?php

namespace App\Common\Dto;

#[\Attribute(\Attribute::TARGET_METHOD)]
class DtoPropertyRelatedToEntity
{
    public function __construct(
        public string $entityInterface,
    ) {
    }
}
