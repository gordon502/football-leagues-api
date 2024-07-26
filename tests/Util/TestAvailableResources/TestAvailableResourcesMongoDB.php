<?php

namespace Tests\Util\TestAvailableResources;

use DateTimeZone;
use MongoDB\BSON\UTCDateTime;
use MongoDB\Client;
use MongoDB\Database;
use MongoDB\Model\BSONArray;

final class TestAvailableResourcesMongoDB implements TestAvailableResourcesInterface
{
    private readonly Client $mongoClient;
    private readonly Database $database;

    public function __construct()
    {
        $this->mongoClient = new Client($_ENV['MONGODB_URL']);
        $this->database = $this->mongoClient->selectDatabase($_ENV['MONGODB_DB']);
    }

    public function getUsers(): array
    {
        $collection = $this->database->selectCollection('user');
        $users = $collection->find()->toArray();

        return array_map(function ($userDb) {
            return [
                'id' => $userDb['_id'],
                'email' => $userDb['email'],
                'name' => $userDb['name'],
                'role' => $userDb['role'],
                'avatar' => $userDb['avatar'],
                'blocked' => (bool) $userDb['blocked']
            ];
        }, $users);
    }

    public function getOrganizationalUnits(): array
    {
        $collection = $this->database->selectCollection('organizational_unit');
        $units = $collection->find()->toArray();

        return array_map(function ($unitDb) {
            return [
                'id' => $unitDb['_id'],
                'name' => $unitDb['name'],
                'country' => $unitDb['country'],
                'address' => $unitDb['address'],
                'city' => $unitDb['city'],
                'postalCode' => $unitDb['postalCode'],
                'phone' => $unitDb['phone'],
            ];
        }, $units);
    }

    public function getTeams(): array
    {
        $collection = $this->database->selectCollection('team');
        $teams = $collection->find()->toArray();

        return array_map(function ($teamDb) {
            return [
                'id' => $teamDb['_id'],
                'organizationalUnitId' => $teamDb['organizationalUnit']['$id'],
                'name' => $teamDb['name'],
                'yearEstablished' => $teamDb['yearEstablished'],
                'colors' => $teamDb['colors'],
                'country' => $teamDb['country'],
                'address' => $teamDb['address'],
                'city' => $teamDb['city'],
                'postalCode' => $teamDb['postalCode'],
                'site' => $teamDb['site'],
                'stadium' => $teamDb['stadium'],
            ];
        }, $teams);
    }

    public function getLeagues(): array
    {
        $collection = $this->database->selectCollection('league');
        $leagues = $collection->find()->toArray();

        return array_map(function ($leagueDb) {
            return [
                'id' => $leagueDb['_id'],
                'organizationalUnitId' => $leagueDb['organizationalUnit']['$id'],
                'name' => $leagueDb['name'],
                'active' => (bool) $leagueDb['active'],
                'level' => $leagueDb['level'],
            ];
        }, $leagues);
    }

    public function getSeasons(): array
    {
        $collection = $this->database->selectCollection('season');
        $seasons = $collection->find()->toArray();

        return array_map(function ($seasonDb) {
            return [
                'id' => $seasonDb['_id'],
                'leagueId' => $seasonDb['league']['$id'],
                'name' => $seasonDb['name'],
                'active' => (bool) $seasonDb['active'],
                'period' => $seasonDb['period'],
            ];
        }, $seasons);
    }

    public function getSeasonTeams(): array
    {
        $collection = $this->database->selectCollection('season_team');
        $seasonTeams = $collection->find()->toArray();

        return array_map(function ($seasonTeamDb) {
            return [
                'id' => $seasonTeamDb['_id'],
                'teamId' => $seasonTeamDb['team']['$id'],
                'seasonId' => $seasonTeamDb['season']['$id'],
                'name' => $seasonTeamDb['name'],
            ];
        }, $seasonTeams);
    }

    public function getRounds(): array
    {
        $collection = $this->database->selectCollection('round');
        $rounds = $collection->find()->toArray();

        return array_map(function ($roundDb) {
            /** @var UTCDateTime $startDate */
            $startDate = $roundDb['standardStartDate'];
            /** @var UTCDateTime $endDate */
            $endDate = $roundDb['standardEndDate'];

            return [
                'id' => $roundDb['_id'],
                'seasonId' => $roundDb['season']['$id'],
                'number' => $roundDb['number'],
                'standardStartDate' => $startDate->toDateTime()->format('Y-m-d'),
                'standardEndDate' => $endDate->toDateTime()->format('Y-m-d'),
            ];
        }, $rounds);
    }

    public function getGames(): array
    {
        $collection = $this->database->selectCollection('game');
        $games = $collection->find()->toArray();

        return array_map(function ($gameDb) {
            /** @var UTCDateTime $date */
            $date = $gameDb['date'];

            return [
                'id' => $gameDb['_id'],
                'roundId' => $gameDb['round']['$id'],
                'seasonTeam1Id' => $gameDb['seasonTeam1']['$id'],
                'seasonTeam2Id' => $gameDb['seasonTeam2']['$id'],
                'date' => $date->toDateTime()->setTimezone(new DateTimeZone(date_default_timezone_get()))->format('Y-m-d H:i:s'),
                'stadium' => $gameDb['stadium'],
                'team1ScoreHalf' => $gameDb['team1ScoreHalf'],
                'team2ScoreHalf' => $gameDb['team2ScoreHalf'],
                'team1Score' => $gameDb['team1Score'],
                'team2Score' => $gameDb['team2Score'],
                'result' => $gameDb['result'],
                'viewers' => $gameDb['viewers'],
                'annotation' => $gameDb['annotation'],
            ];
        }, $games);
    }

    public function getGameEvents(): array
    {
        $collection = $this->database->selectCollection('game_event');
        $gameEvents = $collection->find()->toArray();

        return array_map(function ($gameEventDb) {
            return [
                'id' => $gameEventDb['_id'],
                'gameId' => $gameEventDb['game']['$id'],
                'minute' => $gameEventDb['minute'],
                'partOrHalf' => $gameEventDb['partOrHalf'],
                'teamRelated' => $gameEventDb['teamRelated'],
                'order' => $gameEventDb['order'],
                'eventType' => $gameEventDb['eventType'],
            ];
        }, $gameEvents);
    }

    public function getArticles(): array
    {
        $collection = $this->database->selectCollection('article');
        $articles = $collection->find()->toArray();

        return array_map(function ($articleDb) {
            /** @var BSONArray $seasonTeams */
            $seasonTeams = $articleDb['seasonTeams'];

            /** @var UTCDateTime|null $postAt */
            $postAt = $articleDb['postAt'];

            return [
                'id' => $articleDb['_id'],
                'title' => $articleDb['title'],
                'content' => $articleDb['content'],
                'draft' => (bool) $articleDb['draft'],
                'postAt' => $postAt
                    ?->toDateTime()
                    ->setTimezone(new DateTimeZone(date_default_timezone_get()))
                    ->format('Y-m-d H:i:s'),
                'seasonTeamsId' => array_map(function ($seasonTeam) {
                    return $seasonTeam['$id'];
                }, $seasonTeams->getArrayCopy()),
            ];
        }, $articles);
    }

    public function getLeaderboards(): array
    {
        $collection = $this->database->selectCollection('leaderboard');
        $leaderboards = $collection->find()->toArray();

        return array_map(function ($leaderboardDb) {
            return [
                'id' => $leaderboardDb['_id'],
                'seasonId' => $leaderboardDb['season']['$id'],
                'seasonTeamId' => $leaderboardDb['seasonTeam']['$id'],
                'place' => $leaderboardDb['place'],
                'matchesPlayed' => $leaderboardDb['matchesPlayed'],
                'points' => $leaderboardDb['points'],
                'wins' => $leaderboardDb['wins'],
                'draws' => $leaderboardDb['draws'],
                'losses' => $leaderboardDb['losses'],
                'goalsScored' => $leaderboardDb['goalsScored'],
                'goalsConceded' => $leaderboardDb['goalsConceded'],
                'homeGoalsScored' => $leaderboardDb['homeGoalsScored'],
                'homeGoalsConceded' => $leaderboardDb['homeGoalsConceded'],
                'awayGoalsScored' => $leaderboardDb['awayGoalsScored'],
                'awayGoalsConceded' => $leaderboardDb['awayGoalsConceded'],
                'promotedToHigherDivision' => (bool) $leaderboardDb['promotedToHigherDivision'],
                'eligibleForPromotionBargaining' => (bool) $leaderboardDb['eligibleForPromotionBargaining'],
                'eligibleForRetentionBargaining' => (bool) $leaderboardDb['eligibleForRetentionBargaining'],
                'relegatedToLowerDivision' => (bool) $leaderboardDb['relegatedToLowerDivision'],
                'directMatchesPlayed' => $leaderboardDb['directMatchesPlayed'],
                'directMatchesPoints' => $leaderboardDb['directMatchesPoints'],
                'directMatchesWins' => $leaderboardDb['directMatchesWins'],
                'directMatchesDraws' => $leaderboardDb['directMatchesDraws'],
                'directMatchesLosses' => $leaderboardDb['directMatchesLosses'],
                'directMatchesGoalsScored' => $leaderboardDb['directMatchesGoalsScored'],
                'directMatchesGoalsConceded' => $leaderboardDb['directMatchesGoalsConceded'],
                'annotation' => $leaderboardDb['annotation']
            ];
        }, $leaderboards);
    }
}
