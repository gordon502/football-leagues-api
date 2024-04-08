<?php

namespace App\Common\Serialization;

interface RoleBasedSerializerInterface
{
    public function normalize($object): array;

    public function denormalize(array $data, string $classString): object;
}
