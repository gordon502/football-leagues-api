<?php

namespace Tests\App;

use GuzzleHttp\Client;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Tests\Modules\OrganizationalUnit\OrganizationalUnitControllerTest;
use Tests\Modules\Team\TeamControllerTest;
use Tests\Modules\User\UserControllerTest;

final class AppTest extends TestCase
{
    private static UserControllerTest $userControllerTest;
    private static OrganizationalUnitControllerTest $organizationalUnitControllerTest;
    private static TeamControllerTest $teamControllerTest;

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
    }

    private function clearAfterTests(): void
    {
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
