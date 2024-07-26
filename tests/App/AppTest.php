<?php

namespace Tests\App;

use GuzzleHttp\Client;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Tests\Modules\Article\ArticleControllerTest;
use Tests\Modules\Game\GameControllerTest;
use Tests\Modules\GameEvent\GameEventControllerTest;
use Tests\Modules\Leaderboard\LeaderboardControllerTest;
use Tests\Modules\League\LeagueControllerTest;
use Tests\Modules\Round\RoundControllerTest;
use Tests\Modules\Season\SeasonControllerTest;
use Tests\Modules\OrganizationalUnit\OrganizationalUnitControllerTest;
use Tests\Modules\SeasonTeam\SeasonTeamControllerTest;
use Tests\Modules\Team\TeamControllerTest;
use Tests\Modules\User\UserControllerTest;
use Tests\Pagination\Filter\FilterTest;
use Tests\Pagination\Sort\SortTest;
use Tests\Util\TestDatabaseTypeEnum;

final class AppTest extends TestCase
{
    private static Client $client;

    public static function setUpBeforeClass(): void
    {
        self::setUpMariaDB();
        self::setUpMongoDB();
        self::createAdminModeratorEditorUsers();

        self::$client = new Client([
            'base_uri' => 'http://nginx/api/',
            'http_errors' => false
        ]);
    }

    public static function tearDownAfterClass(): void
    {
        self::dropDatabase();
    }

    #[Test]
    public function testsMariaDB(): void
    {
        self::replaceDatabaseImplementation(TestDatabaseTypeEnum::MariaDB);

        $this->runControllerTests(TestDatabaseTypeEnum::MariaDB);
        $this->runPaginationTests(TestDatabaseTypeEnum::MariaDB);
    }

    #[Test]
    #[Depends('testsMariaDB')]
    public function testsMongoDB()
    {
        self::replaceDatabaseImplementation(TestDatabaseTypeEnum::MongoDB);

        $this->runControllerTests(TestDatabaseTypeEnum::MongoDB);
        $this->runPaginationTests(TestDatabaseTypeEnum::MongoDB);
    }

    private function runControllerTests(TestDatabaseTypeEnum $databaseTypeEnum): void
    {
        $userControllerTest = new UserControllerTest(self::$client, $databaseTypeEnum);
        $organizationalUnitControllerTest = new OrganizationalUnitControllerTest(self::$client, $databaseTypeEnum);
        $teamControllerTest = new TeamControllerTest(self::$client, $databaseTypeEnum);
        $leagueControllerTest = new LeagueControllerTest(self::$client, $databaseTypeEnum);
        $seasonControllerTest = new SeasonControllerTest(self::$client, $databaseTypeEnum);
        $seasonTeamControllerTest = new SeasonTeamControllerTest(self::$client, $databaseTypeEnum);
        $roundControllerTest = new RoundControllerTest(self::$client, $databaseTypeEnum);
        $gameControllerTest = new GameControllerTest(self::$client, $databaseTypeEnum);
        $gameEventControllerTest = new GameEventControllerTest(self::$client, $databaseTypeEnum);
        $articleControllerTest = new ArticleControllerTest(self::$client, $databaseTypeEnum);
        $leaderboardControllerTest = new LeaderboardControllerTest(self::$client, $databaseTypeEnum);

        $userControllerTest->runTests();
        $organizationalUnitControllerTest->runTests();
        $teamControllerTest->runTests();
        $leagueControllerTest->runTests();
        $seasonControllerTest->runTests();
        $seasonTeamControllerTest->runTests();
        $articleControllerTest->runTests();
        $roundControllerTest->runTests();
        $gameControllerTest->runTests();
        $gameEventControllerTest->runTests();
        $leaderboardControllerTest->runTests();
    }

    private function runPaginationTests(TestDatabaseTypeEnum $databaseTypeEnum): void
    {
        $filterTest = new FilterTest(self::$client, $databaseTypeEnum);
        $sortTest = new SortTest(self::$client, $databaseTypeEnum);

        $filterTest->runTests();
        $sortTest->runTests();
    }

    private static function setUpMariaDB(): void
    {
        exec(sprintf(
            'php %s/../../bin/console doctrine:database:drop --force --env=test 2> /dev/null',
            __DIR__
        ));
        exec(sprintf(
            'php %s/../../bin/console doctrine:database:create --env=test 2> /dev/null',
            __DIR__
        ));
        exec(sprintf(
            'php %s/../../bin/console doctrine:schema:create --env=test 2> /dev/null',
            __DIR__
        ));
    }

    private static function setUpMongoDB(): void
    {
        exec(sprintf(
            'php %s/../../bin/console doctrine:mongodb:schema:drop --env=test 2> /dev/null',
            __DIR__
        ));
        exec(sprintf(
            'php %s/../../bin/console doctrine:mongodb:schema:create --env=test 2> /dev/null',
            __DIR__
        ));
    }

    private static function createAdminModeratorEditorUsers(): void
    {
        exec(sprintf(
            'php %s/../../bin/console test:insert-users 2> /dev/null',
            __DIR__,
        ));
    }

    private static function replaceDatabaseImplementation(TestDatabaseTypeEnum $newImplementation): void
    {
        echo "\n\nReplacing database implementation with $newImplementation->value" . PHP_EOL;

        $filePath = __DIR__ . '/../../.env.test';

        $fileContents = file_get_contents($filePath);

        $patternMariaDB = '/^DATABASE_IMPLEMENTATION="MariaDB"/m';
        $patternMongoDB = '/^DATABASE_IMPLEMENTATION="MongoDB"/m';

        if ($newImplementation->value === TestDatabaseTypeEnum::MariaDB->value) {
            $replacement = 'DATABASE_IMPLEMENTATION="MariaDB"';
        } elseif ($newImplementation->value === TestDatabaseTypeEnum::MongoDB->value) {
            $replacement = 'DATABASE_IMPLEMENTATION="MongoDB"';
        } else {
            throw new InvalidArgumentException("Unsupported implementation: $newImplementation->value");
        }

        if (preg_match($patternMariaDB, $fileContents) || preg_match($patternMongoDB, $fileContents)) {
            $fileContents = preg_replace($patternMariaDB, $replacement, $fileContents);
            $fileContents = preg_replace($patternMongoDB, $replacement, $fileContents);
        } else {
            $fileContents .= PHP_EOL . $replacement;
        }

        // Write the modified contents back to the file
        file_put_contents($filePath, $fileContents);
    }

    private static function dropDatabase(): void
    {
        exec(sprintf(
            'php %s/../../bin/console doctrine:database:drop --force --env=test 2> /dev/null',
            __DIR__
        ));

        exec(sprintf(
            'php %s/../../bin/console doctrine:mongodb:schema:drop --env=test 2> /dev/null',
            __DIR__
        ));
    }
}
