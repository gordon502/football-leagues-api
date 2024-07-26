<?php

namespace Tests\Pagination\Filter\Operator;

use GuzzleHttp\Client;
use Tests\Modules\Article\ArticleControllerTest;
use Tests\Modules\Round\RoundControllerTest;
use Tests\Modules\Team\TeamControllerTest;
use Tests\Modules\User\UserControllerTest;
use Tests\Pagination\Filter\AbstractFilterOperatorTest;
use Tests\Util\TestAvailableResources\TestAvailableResourcesInterface;

class FilterLikeOperatorTest extends AbstractFilterOperatorTest
{
    public function __construct(
        Client $client,
        TestAvailableResourcesInterface $availableResources
    ) {
        parent::__construct($client, $availableResources);
    }

    protected function testShouldAllowFilterForOwnStringField(): void
    {
        $availableUsers = $this->availableResources->getUsers();

        $sampleId = $availableUsers[0]['id'];
        $sampleStartPartId = substr($sampleId, 0, 8);
        $sampleMiddlePartId = substr($sampleId, 3, 12);
        $sampleEndPartId = substr($sampleId, -8);
        $sampleUuidPattern = '________-____-____-____-____________';

        $responseStartSubstring = $this->client->get(UserControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "id:like:'" . $sampleStartPartId . "%'"
            ]
        ]);
        $items = json_decode($responseStartSubstring->getBody()->getContents(), true)['data'];
        $this->assertCount(1, $items);

        $responseMiddleSubstring = $this->client->get(UserControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "id:like:'%" . $sampleMiddlePartId . "%'"
            ]
        ]);
        $items = json_decode($responseMiddleSubstring->getBody()->getContents(), true)['data'];
        $this->assertCount(1, $items);

        $responseEndSubstring = $this->client->get(UserControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "id:like:'%" . $sampleEndPartId . "'"
            ]
        ]);
        $items = json_decode($responseEndSubstring->getBody()->getContents(), true)['data'];
        $this->assertCount(1, $items);

        $responseUuidPattern = $this->client->get(UserControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "id:like:'" . $sampleUuidPattern . "'"
            ]
        ]);
        $items = json_decode($responseUuidPattern->getBody()->getContents(), true)['data'];
        $this->assertCount(count($availableUsers), $items);
    }

    protected function testShouldAllowFilterForOwnBoolField(): void
    {
        $availableResources = $this->availableResources->getUsers();

        $blockedUsersCount = count(array_filter($availableResources, fn($user) => $user['blocked'] === true));
        $freeUsersCount = count(array_filter($availableResources, fn($user) => $user['blocked'] === false));

        $response = $this->client->get(UserControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "blocked:like:'true'"
            ]
        ]);

        $items = json_decode($response->getBody()->getContents(), true)['data'];
        $this->assertCount($blockedUsersCount, $items);

        $response = $this->client->get(UserControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "blocked:like:'false'"
            ]
        ]);

        $items = json_decode($response->getBody()->getContents(), true)['data'];
        $this->assertCount($freeUsersCount, $items);
    }

    protected function testShouldAllowFilterForOwnNullableField(): void
    {
        $availableResources = $this->availableResources->getUsers();

        $usersWithNullAvatarCount = count(array_filter($availableResources, fn($user) => $user['avatar'] === null));

        $response = $this->client->get(UserControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "avatar:like:'null'"
            ]
        ]);

        $items = json_decode($response->getBody()->getContents(), true)['data'];
        $this->assertCount($usersWithNullAvatarCount, $items);
    }

    protected function testShouldAllowFilterForOwnIntegerField(): void
    {
        $availableResources = $this->availableResources->getTeams();

        $sampleYearEstablished = $availableResources[0]['yearEstablished'];
        $sampleYearEstablishedBeginning = substr($sampleYearEstablished, 0, 2);
        $sampleYearEstablishedMiddle = substr($sampleYearEstablished, 2, 2);
        $sampleYearEstablishedEnd = substr($sampleYearEstablished, -2);
        $sampleYearDigitPattern = '____';

        $resourcesWithSameYearEstablished = count(array_filter(
            $availableResources,
            fn($team) => $team['yearEstablished'] === $sampleYearEstablished
        ));
        $resourcesWithSameBeginningCharactersOfYearEstablished = count(array_filter(
            $availableResources,
            fn($team) => str_starts_with($team['yearEstablished'], $sampleYearEstablishedBeginning)
        ));
        $resourcesWithSameMiddleCharactersOfYearEstablished = count(array_filter(
            $availableResources,
            fn($team) => str_contains($team['yearEstablished'], $sampleYearEstablishedMiddle)
        ));
        $resourcesWithSameEndCharactersOfYearEstablished = count(array_filter(
            $availableResources,
            fn($team) => str_ends_with($team['yearEstablished'], $sampleYearEstablishedEnd)
        ));
        $resourceWithFourDigitsYearPattern = count(array_filter(
            $availableResources,
            fn($team) => preg_match('/\d{4}/', $team['yearEstablished'])
        ));

        $responseSameYearEstablished = $this->client->get(TeamControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "yearEstablished:like:'$sampleYearEstablished'"
            ]
        ]);

        $items = json_decode($responseSameYearEstablished->getBody()->getContents(), true)['data'];
        $this->assertCount($resourcesWithSameYearEstablished, $items);

        $responseSameBeginningCharactersOfYearEstablished = $this->client->get(TeamControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "yearEstablished:like:'$sampleYearEstablishedBeginning%'"
            ]
        ]);
        $items = json_decode($responseSameBeginningCharactersOfYearEstablished->getBody()->getContents(), true)['data'];
        $this->assertCount($resourcesWithSameBeginningCharactersOfYearEstablished, $items);

        $responseSameMiddleCharactersOfYearEstablished = $this->client->get(TeamControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "yearEstablished:like:'%$sampleYearEstablishedMiddle%'"
            ]
        ]);
        $items = json_decode($responseSameMiddleCharactersOfYearEstablished->getBody()->getContents(), true)['data'];
        $this->assertCount($resourcesWithSameMiddleCharactersOfYearEstablished, $items);

        $responseSameEndCharactersOfYearEstablished = $this->client->get(TeamControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "yearEstablished:like:'%$sampleYearEstablishedEnd'"
            ]
        ]);
        $items = json_decode($responseSameEndCharactersOfYearEstablished->getBody()->getContents(), true)['data'];
        $this->assertCount($resourcesWithSameEndCharactersOfYearEstablished, $items);

        $responseFourDigitsYearPattern = $this->client->get(TeamControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "yearEstablished:like:'$sampleYearDigitPattern'"
            ]
        ]);
        $items = json_decode($responseFourDigitsYearPattern->getBody()->getContents(), true)['data'];
        $this->assertCount($resourceWithFourDigitsYearPattern, $items);
    }

    protected function testShouldAllowFilterForDateField(): void
    {
        $rounds = $this->availableResources->getRounds();
        $sampleDate = $rounds[0]['standardStartDate'];
        $sampleDateBeginning = substr($sampleDate, 0, 10);
        $sampleDateMiddle = substr($sampleDate, 5, 5);
        $sampleDateEnd = substr($sampleDate, -10);

        $roundsWithSameStartDate = count(array_filter(
            $rounds,
            fn($round) => $round['standardStartDate'] === $sampleDate
        ));
        $roundsWithSameStartDateBeginning = count(array_filter(
            $rounds,
            fn($round) => str_starts_with($round['standardStartDate'], $sampleDateBeginning)
        ));
        $roundsWithSameStartDateMiddle = count(array_filter(
            $rounds,
            fn($round) => str_contains($round['standardStartDate'], $sampleDateMiddle)
        ));
        $roundsWithSameStartDateEnd = count(array_filter(
            $rounds,
            fn($round) => str_ends_with($round['standardStartDate'], $sampleDateEnd)
        ));

        $responseSameStartDate = $this->client->get(RoundControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "standardStartDate:like:'$sampleDate'"
            ]
        ]);

        $items = json_decode($responseSameStartDate->getBody()->getContents(), true)['data'];
        $this->assertCount($roundsWithSameStartDate, $items);

        $responseSameStartDateBeginning = $this->client->get(RoundControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "standardStartDate:like:'$sampleDateBeginning%'"
            ]
        ]);
        $items = json_decode($responseSameStartDateBeginning->getBody()->getContents(), true)['data'];
        $this->assertCount($roundsWithSameStartDateBeginning, $items);

        $responseSameStartDateMiddle = $this->client->get(RoundControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "standardStartDate:like:'%$sampleDateMiddle%'"
            ]
        ]);
        $items = json_decode($responseSameStartDateMiddle->getBody()->getContents(), true)['data'];
        $this->assertCount($roundsWithSameStartDateMiddle, $items);

        $responseSameStartDateEnd = $this->client->get(RoundControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "standardStartDate:like:'%$sampleDateEnd'"
            ]
        ]);
        $items = json_decode($responseSameStartDateEnd->getBody()->getContents(), true)['data'];
        $this->assertCount($roundsWithSameStartDateEnd, $items);
    }

    protected function testShouldAllowFilterForDateTimeField(): void
    {
        $articles = $this->availableResources->getArticles();

        $sampleDateTime = $articles[0]['postAt'];
        $sampleDateTimeBeginning = substr($sampleDateTime, 0, 13);
        $sampleDateTimeMiddle = substr($sampleDateTime, 5, 9);
        $sampleDateTimeEnd = substr($sampleDateTime, -13);
        $sampleDateTimePattern = '____-__-__ __:__:__';

        $articlesWithSamePostAt = count(array_filter(
            $articles,
            fn($article) => $article['postAt'] === $sampleDateTime
        ));
        $articlesWithSamePostAtBeginning = count(array_filter(
            $articles,
            fn($article) => str_starts_with($article['postAt'], $sampleDateTimeBeginning)
        ));
        $articlesWithSamePostAtMiddle = count(array_filter(
            $articles,
            fn($article) => str_contains($article['postAt'], $sampleDateTimeMiddle)
        ));
        $articlesWithSamePostAtEnd = count(array_filter(
            $articles,
            fn($article) => str_ends_with($article['postAt'], $sampleDateTimeEnd)
        ));
        $articlesWithDateTimePattern = count(array_filter(
            $articles,
            fn($article) => preg_match('/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/', $article['postAt'])
        ));

        $responseSamePostAt = $this->client->get(ArticleControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "postAt:like:'$sampleDateTime'"
            ]
        ]);
        $items = json_decode($responseSamePostAt->getBody()->getContents(), true)['data'];
        $this->assertCount($articlesWithSamePostAt, $items);

        $responseSamePostAtBeginning = $this->client->get(ArticleControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "postAt:like:'$sampleDateTimeBeginning%'"
            ]
        ]);
        $items = json_decode($responseSamePostAtBeginning->getBody()->getContents(), true)['data'];
        $this->assertCount($articlesWithSamePostAtBeginning, $items);

        $responseSamePostAtMiddle = $this->client->get(ArticleControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "postAt:like:'%$sampleDateTimeMiddle%'",
            ]
        ]);
        $items = json_decode($responseSamePostAtMiddle->getBody()->getContents(), true)['data'];
        $this->assertCount($articlesWithSamePostAtMiddle, $items);


        $responseSamePostAtEnd = $this->client->get(ArticleControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "postAt:like:'%$sampleDateTimeEnd'"
            ]
        ]);
        $items = json_decode($responseSamePostAtEnd->getBody()->getContents(), true)['data'];
        $this->assertCount($articlesWithSamePostAtEnd, $items);

        $responseDateTimePattern = $this->client->get(ArticleControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "postAt:like:'$sampleDateTimePattern'"
            ]
        ]);
        $items = json_decode($responseDateTimePattern->getBody()->getContents(), true)['data'];
        $this->assertCount($articlesWithDateTimePattern, $items);
    }

    protected function testShouldAllowFilterForReferenceStringField(): void
    {
        $availableResources = $this->availableResources->getTeams();

        $sampleOrganizationalUnitId = $availableResources[0]['organizationalUnitId'];
        $sampleOrganizationalUnitIdBeginning = substr($sampleOrganizationalUnitId, 0, 8);
        $sampleOrganizationalUnitIdMiddle = substr($sampleOrganizationalUnitId, 3, 12);
        $sampleOrganizationalUnitIdEnd = substr($sampleOrganizationalUnitId, -8);
        $sampleOrganizationalUnitIdPattern = '________-____-____-____-____________';

        $resourcesWithSameOrganizationalUnitId = count(array_filter(
            $availableResources,
            fn($team) => $team['organizationalUnitId'] === $sampleOrganizationalUnitId
        ));
        $resourcesWithSameBeginningCharactersOfOrganizationalUnitId = count(array_filter(
            $availableResources,
            fn($team) => str_starts_with($team['organizationalUnitId'], $sampleOrganizationalUnitIdBeginning)
        ));
        $resourcesWithSameMiddleCharactersOfOrganizationalUnitId = count(array_filter(
            $availableResources,
            fn($team) => str_contains($team['organizationalUnitId'], $sampleOrganizationalUnitIdMiddle)
        ));
        $resourcesWithSameEndCharactersOfOrganizationalUnitId = count(array_filter(
            $availableResources,
            fn($team) => str_ends_with($team['organizationalUnitId'], $sampleOrganizationalUnitIdEnd)
        ));
        $resourcesWithOrganizationalUnitIdPattern = count(array_filter(
            $availableResources,
            fn($team) => preg_match('/\w{8}-\w{4}-\w{4}-\w{4}-\w{12}/', $team['organizationalUnitId'])
        ));

        $responseSameOrganizationalUnitId = $this->client->get(TeamControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "organizationalUnitId:like:'$sampleOrganizationalUnitId'"
            ]
        ]);
        $items = json_decode($responseSameOrganizationalUnitId->getBody()->getContents(), true)['data'];
        $this->assertCount($resourcesWithSameOrganizationalUnitId, $items);

        $responseSameBeginningCharactersOfOrganizationalUnitId = $this->client->get(
            TeamControllerTest::DEFAULT_ENDPOINT,
            [
                'query' => [
                    'filter' => "organizationalUnitId:like:'$sampleOrganizationalUnitIdBeginning%'"
                ]
            ]
        );
        $items = json_decode(
            $responseSameBeginningCharactersOfOrganizationalUnitId->getBody()->getContents(),
            true
        )['data'];
        $this->assertCount($resourcesWithSameBeginningCharactersOfOrganizationalUnitId, $items);

        $responseSameMiddleCharactersOfOrganizationalUnitId = $this->client->get(TeamControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "organizationalUnitId:like:'%$sampleOrganizationalUnitIdMiddle%'"
            ]
        ]);
        $items = json_decode(
            $responseSameMiddleCharactersOfOrganizationalUnitId->getBody()->getContents(),
            true
        )['data'];
        $this->assertCount($resourcesWithSameMiddleCharactersOfOrganizationalUnitId, $items);

        $responseSameEndCharactersOfOrganizationalUnitId = $this->client->get(TeamControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "organizationalUnitId:like:'%$sampleOrganizationalUnitIdEnd'"
            ]
        ]);
        $items = json_decode($responseSameEndCharactersOfOrganizationalUnitId->getBody()->getContents(), true)['data'];
        $this->assertCount($resourcesWithSameEndCharactersOfOrganizationalUnitId, $items);

        $responseOrganizationalUnitIdPattern = $this->client->get(TeamControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "organizationalUnitId:like:'$sampleOrganizationalUnitIdPattern'"
            ]
        ]);
        $items = json_decode($responseOrganizationalUnitIdPattern->getBody()->getContents(), true)['data'];
        $this->assertCount($resourcesWithOrganizationalUnitIdPattern, $items);
    }

    protected function testShouldAllowFilterForReferenceArrayField(): void
    {
        $availableResources = $this->availableResources->getArticles();
        $articleWithSeasonTeams = array_filter(
            $availableResources,
            fn($article) => count($article['seasonTeamsId']) > 0
        );

        $sampleSeasonTeamId = $articleWithSeasonTeams[0]['seasonTeamsId'][0];
        $sampleSeasonTeamIdBeginning = substr($sampleSeasonTeamId, 0, 8);
        $sampleSeasonTeamIdMiddle = substr($sampleSeasonTeamId, 3, 12);
        $sampleSeasonTeamIdEnd = substr($sampleSeasonTeamId, -8);
        $sampleSeasonTeamIdPattern = '________-____-____-____-____________';

        $articlesWithSameSeasonTeamId = count(array_filter(
            $availableResources,
            fn($article) => count(array_filter(
                $article['seasonTeamsId'],
                fn($seasonTeam) => $seasonTeam === $sampleSeasonTeamId
            )) > 0
        ));
        $articlesWithSameBeginningCharactersOfSeasonTeamId = count(array_filter(
            $availableResources,
            fn($article) => count(array_filter(
                $article['seasonTeamsId'],
                fn($seasonTeam) => str_starts_with($seasonTeam, $sampleSeasonTeamIdBeginning)
            )) > 0
        ));
        $articlesWithSameMiddleCharactersOfSeasonTeamId = count(array_filter(
            $availableResources,
            fn($article) => count(array_filter(
                $article['seasonTeamsId'],
                fn($seasonTeam) => str_contains($seasonTeam, $sampleSeasonTeamIdMiddle)
            )) > 0
        ));
        $articlesWithSameEndCharactersOfSeasonTeamId = count(array_filter(
            $availableResources,
            fn($article) => count(array_filter(
                $article['seasonTeamsId'],
                fn($seasonTeam) => str_ends_with($seasonTeam, $sampleSeasonTeamIdEnd)
            )) > 0
        ));
        $articlesWithSeasonTeamIdPattern = count(array_filter(
            $availableResources,
            fn($article) => count(array_filter(
                $article['seasonTeamsId'],
                fn($seasonTeam) => preg_match('/\w{8}-\w{4}-\w{4}-\w{4}-\w{12}/', $seasonTeam)
            )) > 0
        ));

        $responseSameSeasonTeamId = $this->client->get(ArticleControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "seasonTeamsId:like:'$sampleSeasonTeamId'"
            ]
        ]);
        $items = json_decode($responseSameSeasonTeamId->getBody()->getContents(), true)['data'];
        $this->assertCount($articlesWithSameSeasonTeamId, $items);

        $responseSameBeginningCharactersOfSeasonTeamId = $this->client->get(ArticleControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "seasonTeamsId:like:'$sampleSeasonTeamIdBeginning%'"
            ]
        ]);
        $items = json_decode($responseSameBeginningCharactersOfSeasonTeamId->getBody()->getContents(), true)['data'];
        $this->assertCount($articlesWithSameBeginningCharactersOfSeasonTeamId, $items);

        $responseSameMiddleCharactersOfSeasonTeamId = $this->client->get(ArticleControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "seasonTeamsId:like:'%$sampleSeasonTeamIdMiddle%'"
            ]
        ]);
        $items = json_decode($responseSameMiddleCharactersOfSeasonTeamId->getBody()->getContents(), true)['data'];
        $this->assertCount($articlesWithSameMiddleCharactersOfSeasonTeamId, $items);

        $responseSameEndCharactersOfSeasonTeamId = $this->client->get(ArticleControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "seasonTeamsId:like:'%$sampleSeasonTeamIdEnd'"
            ]
        ]);
        $items = json_decode($responseSameEndCharactersOfSeasonTeamId->getBody()->getContents(), true)['data'];
        $this->assertCount($articlesWithSameEndCharactersOfSeasonTeamId, $items);

        $responseSeasonTeamIdPattern = $this->client->get(ArticleControllerTest::DEFAULT_ENDPOINT, [
            'query' => [
                'filter' => "seasonTeamsId:like:'$sampleSeasonTeamIdPattern'"
            ]
        ]);
        $items = json_decode($responseSeasonTeamIdPattern->getBody()->getContents(), true)['data'];
        $this->assertCount($articlesWithSeasonTeamIdPattern, $items);
    }
}
