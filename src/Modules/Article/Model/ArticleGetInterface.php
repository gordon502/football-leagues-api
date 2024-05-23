<?php

namespace App\Modules\Article\Model;

use DateTimeInterface;
use Doctrine\Common\Collections\Collection;

interface ArticleGetInterface
{
    public function getId(): string;

    public function getTitle(): string;

    public function getContent(): string;

    public function isDraft(): bool;

    public function getPostAt(): ?DateTimeInterface;

    public function getSeasonTeams(): Collection;
}
