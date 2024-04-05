<?php

namespace App\Common\Model;

use DateTimeInterface;

interface TimestampableModelInterface
{
    public function getCreatedAt(): DateTimeInterface;

    public function setCreatedAt(DateTimeInterface $createdAt): static;

    public function getUpdatedAt(): DateTimeInterface;

    public function setUpdatedAt(DateTimeInterface $updatedAt): static;
}
