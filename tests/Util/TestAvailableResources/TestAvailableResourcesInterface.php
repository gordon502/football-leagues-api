<?php

namespace Tests\Util\TestAvailableResources;

interface TestAvailableResourcesInterface
{
    public function getUsers(): array;

    public function getOrganizationalUnits(): array;

    public function getTeams(): array;

    public function getLeagues(): array;

    public function getSeasons(): array;

    public function getSeasonTeams(): array;

    public function getRounds(): array;

    public function getGames(): array;

    public function getGameEvents(): array;

    public function getArticles(): array;
}