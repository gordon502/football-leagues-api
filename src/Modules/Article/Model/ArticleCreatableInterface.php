<?php

namespace App\Modules\Article\Model;

interface ArticleCreatableInterface
{
    public function getTitle(): ?string;

    public function getContent(): ?string;

    public function isDraft(): ?bool;

    public function getPostAt(): ?string;

    public function getSeasonTeamsId(): ?array;
}
