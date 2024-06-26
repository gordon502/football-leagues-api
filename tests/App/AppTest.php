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
use Tests\Modules\League\LeagueControllerTest;
use Tests\Modules\Round\RoundControllerTest;
use Tests\Modules\Season\SeasonControllerTest;
use Tests\Modules\OrganizationalUnit\OrganizationalUnitControllerTest;
use Tests\Modules\SeasonTeam\SeasonTeamControllerTest;
use Tests\Modules\Team\TeamControllerTest;
use Tests\Modules\User\UserControllerTest;

final class AppTest extends TestCase
{
    private static UserControllerTest $userControllerTest;
    private static OrganizationalUnitControllerTest $organizationalUnitControllerTest;
    private static TeamControllerTest $teamControllerTest;
    private static LeagueControllerTest $leagueControllerTest;
    private static SeasonControllerTest $seasonControllerTest;
    private static SeasonTeamControllerTest $seasonTeamControllerTest;
    private static RoundControllerTest $roundControllerTest;
    private static GameControllerTest $gameControllerTest;
    private static GameEventControllerTest $gameEventControllerTest;
    private static ArticleControllerTest $articleControllerTest;

    public static function setUpBeforeClass(): void
    {
        self::setUpMariaDB();
        self::setUpMongoDB();
        self::createAdminModeratorEditorUsers();

        $client = new Client([
            'base_uri' => 'http://nginx/api/',
            'http_errors' => false
        ]);

        self::$userControllerTest = new UserControllerTest($client);
        self::$organizationalUnitControllerTest = new OrganizationalUnitControllerTest($client);
        self::$teamControllerTest = new TeamControllerTest($client);
        self::$leagueControllerTest = new LeagueControllerTest($client);
        self::$seasonControllerTest = new SeasonControllerTest($client);
        self::$seasonTeamControllerTest = new SeasonTeamControllerTest($client);
        self::$roundControllerTest = new RoundControllerTest($client);
        self::$gameControllerTest = new GameControllerTest($client);
        self::$gameEventControllerTest = new GameEventControllerTest($client);
        self::$articleControllerTest = new ArticleControllerTest($client);
    }

    public static function tearDownAfterClass(): void
    {
        self::dropDatabase();
    }

    #[Test]
    public function userControllerTestMariaDB(): void
    {
        self::replaceDatabaseImplementation('MariaDB');

        $this->runTests();
        $this->clearAfterTests();
    }

    #[Test]
    #[Depends('userControllerTestMariaDB')]
    public function userControllerTestMongoDB()
    {
        self::replaceDatabaseImplementation('MongoDB');

        $this->runTests();
        $this->clearAfterTests();
    }

    private function runTests(): void
    {
        self::$userControllerTest->runTests();
        self::$organizationalUnitControllerTest->runTests();
        self::$teamControllerTest->runTests();
        self::$leagueControllerTest->runTests();
        self::$seasonControllerTest->runTests();
        self::$seasonTeamControllerTest->runTests();
        self::$articleControllerTest->runTests();
        self::$roundControllerTest->runTests();
        self::$gameControllerTest->runTests();
        self::$gameEventControllerTest->runTests();
    }

    private function clearAfterTests(): void
    {
        self::$gameEventControllerTest->clearAfterTests();
        self::$gameControllerTest->clearAfterTests();
        self::$roundControllerTest->clearAfterTests();
        self::$articleControllerTest->clearAfterTests();
        self::$seasonTeamControllerTest->clearAfterTests();
        self::$seasonControllerTest->clearAfterTests();
        self::$leagueControllerTest->clearAfterTests();
        self::$teamControllerTest->clearAfterTests();
        self::$organizationalUnitControllerTest->clearAfterTests();
        self::$userControllerTest->clearAfterTests();
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

    private static function replaceDatabaseImplementation(string $newImplementation): void
    {
        echo "\n\nReplacing database implementation with $newImplementation" . PHP_EOL;

        $filePath = __DIR__ . '/../../.env.test';

        $fileContents = file_get_contents($filePath);

        $patternMariaDB = '/^DATABASE_IMPLEMENTATION="MariaDB"/m';
        $patternMongoDB = '/^DATABASE_IMPLEMENTATION="MongoDB"/m';

        if ($newImplementation === 'MariaDB') {
            $replacement = 'DATABASE_IMPLEMENTATION="MariaDB"';
        } elseif ($newImplementation === 'MongoDB') {
            $replacement = 'DATABASE_IMPLEMENTATION="MongoDB"';
        } else {
            throw new InvalidArgumentException("Unsupported implementation: $newImplementation");
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
