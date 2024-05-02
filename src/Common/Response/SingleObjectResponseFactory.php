<?php

namespace App\Common\Response;

use App\Common\Serialization\RoleBasedSerializerInterface;

readonly class SingleObjectResponseFactory implements SingleObjectResponseFactoryInterface
{
    public function __construct(
        private RoleBasedSerializerInterface $serializer,
    ) {
    }

    public function fromObject(object $object, string $getDtoClass): array
    {
        return $this->serializer->normalize(new $getDtoClass($object));
    }
}
