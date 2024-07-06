<?php

namespace Tests\Util\TestAvailableResources;

use PDO;

final class TestAvailableResourcesMariaDB implements TestAvailableResourcesInterface
{
    private readonly PDO $pdo;

    public function __construct()
    {
        $this->pdo = $this->createPdo();
    }

    public function getUsers(): array
    {
        $sql = 'SELECT * FROM user';
        $stmt = $this->pdo->query($sql);
        $users = $stmt->fetchAll();

        return array_map(function ($userDb) {
            return [
                'id' => $userDb['id'],
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
        $sql = 'SELECT * FROM organizational_unit';
        $stmt = $this->pdo->query($sql);
        $units = $stmt->fetchAll();

        return array_map(function ($unitDb) {
            return [
                'id' => $unitDb['id'],
                'name' => $unitDb['name'],
                'country' => $unitDb['country'],
                'address' => $unitDb['address'],
                'city' => $unitDb['city'],
                'postalCode' => $unitDb['postal_code'],
                'phone' => $unitDb['phone'],
            ];
        }, $units);
    }

    public function getTeams(): array
    {
        $sql = 'SELECT * FROM team';
        $stmt = $this->pdo->query($sql);
        $teams = $stmt->fetchAll();

        return array_map(function ($teamDb) {
            return [
                'id' => $teamDb['id'],
                'organizationalUnitId' => $teamDb['organizational_unit_id'],
                'name' => $teamDb['name'],
                'yearEstablished' => $teamDb['year_established'],
                'colors' => $teamDb['colors'],
                'country' => $teamDb['country'],
                'address' => $teamDb['address'],
                'city' => $teamDb['city'],
                'postalCode' => $teamDb['postal_code'],
                'site' => $teamDb['site'],
                'stadium' => $teamDb['stadium'],
            ];
        }, $teams);
    }

    public function getLeagues(): array
    {
        $sql = 'SELECT * FROM league';
        $stmt = $this->pdo->query($sql);
        $leagues = $stmt->fetchAll();

        return array_map(function ($leagueDb) {
            return [
                'id' => $leagueDb['id'],
                'organizationalUnitId' => $leagueDb['organizational_unit_id'],
                'name' => $leagueDb['name'],
                'active' => (bool) $leagueDb['active'],
                'level' => $leagueDb['level'],
            ];
        }, $leagues);
    }

    public function getSeasons(): array
    {
        $sql = 'SELECT * FROM season';
        $stmt = $this->pdo->query($sql);
        $seasons = $stmt->fetchAll();

        return array_map(function ($seasonDb) {
            return [
                'id' => $seasonDb['id'],
                'leagueId' => $seasonDb['league_id'],
                'name' => $seasonDb['name'],
                'active' => (bool) $seasonDb['active'],
                'period' => $seasonDb['period'],
            ];
        }, $seasons);
    }

    public function getSeasonTeams(): array
    {
        $sql = 'SELECT * FROM season_team';
        $stmt = $this->pdo->query($sql);
        $seasonTeams = $stmt->fetchAll();

        return array_map(function ($seasonTeamDb) {
            return [
                'id' => $seasonTeamDb['id'],
                'teamId' => $seasonTeamDb['team_id'],
                'seasonId' => $seasonTeamDb['season_id'],
                'name' => $seasonTeamDb['name'],
            ];
        }, $seasonTeams);
    }

    public function getRounds(): array
    {
        $sql = 'SELECT * FROM round';
        $stmt = $this->pdo->query($sql);
        $rounds = $stmt->fetchAll();

        return array_map(function ($roundDb) {
            return [
                'id' => $roundDb['id'],
                'seasonId' => $roundDb['season_id'],
                'number' => $roundDb['number'],
                'standardStartDate' => $roundDb['standard_start_date'],
                'standardEndDate' => $roundDb['standard_end_date'],
            ];
        }, $rounds);
    }

    public function getGames(): array
    {
        $sql = 'SELECT * FROM game';
        $stmt = $this->pdo->query($sql);
        $games = $stmt->fetchAll();

        return array_map(function ($gameDb) {
            return [
                'id' => $gameDb['id'],
                'roundId' => $gameDb['round_id'],
                'seasonTeam1Id' => $gameDb['season_team1_id'],
                'date' => $gameDb['date'],
                'stadium' => $gameDb['stadium'],
                'team1ScoreHalf' => $gameDb['team1_score_half'],
                'team2ScoreHalf' => $gameDb['team2_score_half'],
                'team1Score' => $gameDb['team1_score'],
                'team2Score' => $gameDb['team2_score'],
                'result' => $gameDb['result'],
                'viewers' => $gameDb['viewers'],
                'annotation' => $gameDb['annotation'],
                'seasonTeam2Id' => $gameDb['season_team2_id'],
            ];
        }, $games);
    }

    public function getGameEvents(): array
    {
        $sql = 'SELECT * FROM game_event';
        $stmt = $this->pdo->query($sql);
        $gameEvents = $stmt->fetchAll();

        return array_map(function ($gameEventDb) {
            return [
                'id' => $gameEventDb['id'],
                'gameId' => $gameEventDb['game_id'],
                'minute' => $gameEventDb['minute'],
                'partOrHalf' => $gameEventDb['part_or_half'],
                'teamRelated' => $gameEventDb['team_related'],
                'order' => $gameEventDb['order'],
                'eventType' => $gameEventDb['event_type'],
            ];
        }, $gameEvents);
    }

    public function getArticles(): array
    {
        $sql = 'SELECT * FROM article';
        $stmt = $this->pdo->query($sql);
        $articles = $stmt->fetchAll();

        return array_map(function ($articleDb) {
            $seasonTeamsId = $this->pdo->query(
                sprintf(
                    'SELECT season_team_id FROM article_season_team WHERE article_id = "%s"',
                    $articleDb['id']
                )
            )->fetchAll(PDO::FETCH_COLUMN);

            return [
                'id' => $articleDb['id'],
                'title' => $articleDb['title'],
                'content' => $articleDb['content'],
                'draft' => (bool) $articleDb['draft'],
                'postAt' => $articleDb['post_at'],
                'seasonTeamsId' => $seasonTeamsId,
            ];
        }, $articles);
    }

    public function getLeaderboards(): array
    {
        $sql = 'SELECT * FROM leaderboard';
        $stmt = $this->pdo->query($sql);
        $leaderboards = $stmt->fetchAll();

        return array_map(function ($leaderboardDb) {
            return [
                'id' => $leaderboardDb['id'],
                'seasonId' => $leaderboardDb['season_id'],
                'seasonTeamId' => $leaderboardDb['season_team_id'],
                'place' => $leaderboardDb['place'],
                'matchesPlayed' => $leaderboardDb['matches_played'],
                'points' => $leaderboardDb['points'],
                'wins' => $leaderboardDb['wins'],
                'draws' => $leaderboardDb['draws'],
                'losses' => $leaderboardDb['losses'],
                'goalsScored' => $leaderboardDb['goals_scored'],
                'goalsConceded' => $leaderboardDb['goals_conceded'],
                'homeGoalsScored' => $leaderboardDb['home_goals_scored'],
                'homeGoalsConceded' => $leaderboardDb['home_goals_conceded'],
                'awayGoalsScored' => $leaderboardDb['away_goals_scored'],
                'awayGoalsConceded' => $leaderboardDb['away_goals_conceded'],
                'promotedToHigherDivision' => (bool) $leaderboardDb['promoted_to_higher_division'],
                'eligibleForPromotionBargaining' => (bool) $leaderboardDb['eligible_for_promotion_bargaining'],
                'eligibleForRetentionBargaining' => (bool) $leaderboardDb['eligible_for_retention_bargaining'],
                'relegatedToLowerDivision' => (bool) $leaderboardDb['relegated_to_lower_division'],
                'directMatchesPlayed' => $leaderboardDb['direct_matches_played'],
                'directMatchesPoints' => $leaderboardDb['direct_matches_points'],
                'directMatchesWins' => $leaderboardDb['direct_matches_wins'],
                'directMatchesDraws' => $leaderboardDb['direct_matches_draws'],
                'directMatchesLosses' => $leaderboardDb['direct_matches_losses'],
                'directMatchesGoalsScored' => $leaderboardDb['direct_matches_goals_scored'],
                'directMatchesGoalsConceded' => $leaderboardDb['direct_matches_goals_conceded'],
                'annotation' => $leaderboardDb['annotation']
            ];
        }, $leaderboards);
    }

    private function createPdo(): PDO
    {
        $dbUrl = $_ENV['DATABASE_URL'];
        $parsedDbUrl = parse_url($dbUrl);

        $host = $parsedDbUrl['host'];
        $dbname = ltrim($parsedDbUrl['path'], '/') . '_test';
        $user = $parsedDbUrl['user'];
        $pass = $parsedDbUrl['pass'];
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

        $pdo = new PDO($dsn, $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $pdo;
    }
}
