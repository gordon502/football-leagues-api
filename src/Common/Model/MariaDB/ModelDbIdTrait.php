<?php

namespace App\Common\Model\MariaDB;

use Doctrine\ORM\Mapping as ORM;

trait ModelDbIdTrait
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    protected int $dbId;

    public function getDbId(): int
    {
        return $this->dbId;
    }
}
