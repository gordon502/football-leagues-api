<?php

namespace App\Common\Model;

use DateTimeInterface;

interface TimestampableModelInterface
{
    public function getCreatedAt(): DateTimeInterface;

    public function getUpdatedAt(): DateTimeInterface;
}
