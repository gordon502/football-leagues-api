<?php

namespace App\Common\Model;

use DateTimeInterface;

interface SoftDeletableModelInterface
{
    public function getDeletedAt(): ?DateTimeInterface;

    public function setDeletedAt(DateTimeInterface $deletedAt): static;
}
