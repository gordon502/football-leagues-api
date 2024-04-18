<?php

namespace App\Common\Model\MongoDB;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

trait ModelDbIdTrait
{
    #[MongoDB\Id(type: 'string')]
    protected string $dbId;

    public function getDbId(): string
    {
        return $this->dbId;
    }
}
