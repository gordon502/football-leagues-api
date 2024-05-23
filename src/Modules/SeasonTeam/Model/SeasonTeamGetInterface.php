<?php

namespace App\Modules\SeasonTeam\Model;

use App\Modules\Season\Model\SeasonInterface;
use App\Modules\Team\Model\TeamInterface;
use Doctrine\Common\Collections\Collection;

interface SeasonTeamGetInterface
{
    public function getId(): string;

    public function getName(): string;

    public function getTeam(): TeamInterface;

    public function getSeason(): SeasonInterface;

    public function getArticles(): Collection;

    public function getGamesAsTeam1(): Collection;

    public function getGamesAsTeam2(): Collection;
}
