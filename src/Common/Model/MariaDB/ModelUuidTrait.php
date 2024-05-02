<?php

namespace App\Common\Model\MariaDB;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

trait ModelUuidTrait
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', unique: true)]
    protected string $id;

    public function getId(): string
    {
        return $this->id;
    }

    #[ORM\PrePersist]
    public function createId(): void
    {
        $this->id = Uuid::v4()->toRfc4122();
    }
}
