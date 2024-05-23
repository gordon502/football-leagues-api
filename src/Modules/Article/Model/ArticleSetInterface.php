<?php

namespace App\Modules\Article\Model;

use App\Modules\SeasonTeam\Model\SeasonTeamInterface;
use DateTimeInterface;

interface ArticleSetInterface
{
    public function setTitle(string $title): static;

    public function setContent(string $content): static;

    public function setDraft(bool $draft): static;

    public function setPostAt(?DateTimeInterface $postAt): static;

    public function addSeasonTeams(SeasonTeamInterface $seasonTeam): static;

    public function removeSeasonTeam(SeasonTeamInterface $seasonTeam): static;

    public function clearSeasonTeams(): static;
}
