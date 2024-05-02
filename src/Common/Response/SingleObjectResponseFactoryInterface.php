<?php

namespace App\Common\Response;

interface SingleObjectResponseFactoryInterface
{
    public function fromObject(
        object $object,
        string $getDtoClass,
    ): array;
}
