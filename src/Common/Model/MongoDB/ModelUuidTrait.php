<?php

namespace App\Common\Model\MongoDB;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Uid\Uuid;

trait ModelUuidTrait
{
    #[MongoDB\Field(type: 'string')]
    #[MongoDB\UniqueIndex(order: 'asc')]
    protected string $id;

    public function getId(): string
    {
        return $this->id;
    }

    #[MongoDB\PrePersist]
    public function createId(): void
    {
        $this->id = Uuid::v4()->toRfc4122();
    }
}
