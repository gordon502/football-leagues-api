<?php

namespace Tests\Modules\GameEvent;

use App\Modules\GameEvent\Enum\GameEventEventTypeEnum;
use App\Modules\GameEvent\Enum\GameEventPartOrHalfEnum;
use App\Modules\GameEvent\Enum\GameEventTeamRelatedEnum;
use GuzzleHttp\Client;
use Tests\Modules\AbstractControllerTest;
use Tests\Util\TestAvailableResources;
use Tests\Util\TestLoginUtil;

class GameEventControllerTest extends AbstractControllerTest
{
    public function __construct(Client $client)
    {
        parent::__construct($client);

        $this->endpoint = 'game-events';
    }

    public function clearAfterTests(): void
    {
        TestAvailableResources::$gameEvents = [];
    }

    protected function testShouldCheckIfAdminCanCreateResource(): void
    {
        $token = $this->loginUtil->loginAsAdmin();

        for ($i = 1; $i <= 5; $i++) {
            $response = $this->client->post($this->endpoint, [
                'headers' => ['Authorization' => "Bearer $token"],
                'json' => [
                    'minute' => 1,
                    'partOrHalf' => GameEventPartOrHalfEnum::FIRST_HALF->value,
                    'teamRelated' => GameEventTeamRelatedEnum::TEAM_1->value,
                    'order' => 1,
                    'eventType' => GameEventEventTypeEnum::GOAL->value,
                    'gameId' => $this->randomGameId(),
                ],
            ]);

            $this->assertEquals(201, $response->getStatusCode());

            TestAvailableResources::$gameEvents[] = json_decode($response->getBody()->getContents(), true);
        }
    }

    protected function testShouldCheckIfModeratorCanCreateResource(): void
    {
        $token = $this->loginUtil->loginAsModerator();

        for ($i = 1; $i <= 5; $i++) {
            $response = $this->client->post($this->endpoint, [
                'headers' => ['Authorization' => "Bearer $token"],
                'json' => [
                    'minute' => 1,
                    'partOrHalf' => GameEventPartOrHalfEnum::FIRST_HALF->value,
                    'teamRelated' => GameEventTeamRelatedEnum::TEAM_1->value,
                    'order' => 1,
                    'eventType' => GameEventEventTypeEnum::GOAL->value,
                    'gameId' => $this->randomGameId(),
                ],
            ]);

            $this->assertEquals(201, $response->getStatusCode());

            TestAvailableResources::$gameEvents[] = json_decode($response->getBody()->getContents(), true);
        }
    }

    protected function testShouldCheckIfEditorCanCreateResource(): void
    {
        $token = $this->loginUtil->loginAsEditor();

        for ($i = 1; $i <= 5; $i++) {
            $response = $this->client->post($this->endpoint, [
                'headers' => ['Authorization' => "Bearer $token"],
                'json' => [
                    'minute' => 1,
                    'partOrHalf' => GameEventPartOrHalfEnum::FIRST_HALF->value,
                    'teamRelated' => GameEventTeamRelatedEnum::TEAM_1->value,
                    'order' => 1,
                    'eventType' => GameEventEventTypeEnum::GOAL->value,
                    'gameId' => $this->randomGameId(),
                ],
            ]);

            $this->assertEquals(201, $response->getStatusCode());

            TestAvailableResources::$gameEvents[] = json_decode($response->getBody()->getContents(), true);
        }
    }

    protected function testShouldCheckIfUserCanCreateResource(): void
    {
        $user = TestAvailableResources::$users[0];
        $token = $this->loginUtil->loginWithEmailAndPassword($user['email'], TestLoginUtil::DEFAULT_PASSWORD);

        $response = $this->client->post($this->endpoint, [
            'headers' => ['Authorization' => "Bearer $token"],
            'json' => [
                'minute' => 1,
                'partOrHalf' => GameEventPartOrHalfEnum::FIRST_HALF->value,
                'teamRelated' => GameEventTeamRelatedEnum::TEAM_1->value,
                'order' => 1,
                'eventType' => GameEventEventTypeEnum::GOAL->value,
                'gameId' => $this->randomGameId(),
            ],
        ]);

        $this->assertEquals(403, $response->getStatusCode());
    }

    protected function testShouldCheckIfGuestCanCreateResource(): void
    {
        $response = $this->client->post($this->endpoint, [
            'json' => [
                'minute' => 1,
                'partOrHalf' => GameEventPartOrHalfEnum::FIRST_HALF->value,
                'teamRelated' => GameEventTeamRelatedEnum::TEAM_1->value,
                'order' => 1,
                'eventType' => GameEventEventTypeEnum::GOAL->value,
                'gameId' => $this->randomGameId(),
            ],
        ]);

        $this->assertEquals(401, $response->getStatusCode());
    }

    protected function testShouldReturnPreviouslyCreatedResources(): void
    {
        $token = $this->loginUtil->loginAsAdmin();

        foreach (TestAvailableResources::$gameEvents as $resource) {
            $response = $this->client->get("{$this->endpoint}/{$resource['id']}", [
                'headers' => ['Authorization' => "Bearer $token"],
            ]);

            $this->assertEquals(200, $response->getStatusCode());

            $json = json_decode($response->getBody()->getContents(), true);

            $this->assertEquals($resource, $json);
        }
    }

    protected function testShouldReturnCollectionOfAllCreatedResources(): void
    {
        $response = $this->client->get($this->endpoint);

        $this->assertEquals(200, $response->getStatusCode());

        $json = json_decode($response->getBody()->getContents(), true);

        $this->assertCount(count(TestAvailableResources::$gameEvents), $json['data']);
    }

    protected function testShouldCheckIfAdminCanReadResource(): void
    {
        $readableFields = [
            'id',
            'minute',
            'partOrHalf',
            'teamRelated',
            'order',
            'eventType',
            'gameId'
        ];

        $token = $this->loginUtil->loginAsAdmin();

        $id = TestAvailableResources::$gameEvents[0]['id'];

        $response = $this->client->get("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertReadableFieldsFromResponse($readableFields, $response);
    }

    protected function testShouldCheckIfModeratorCanReadResource(): void
    {
        $readableFields = [
            'id',
            'minute',
            'partOrHalf',
            'teamRelated',
            'order',
            'eventType',
            'gameId'
        ];

        $token = $this->loginUtil->loginAsModerator();

        $id = TestAvailableResources::$gameEvents[0]['id'];

        $response = $this->client->get("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertReadableFieldsFromResponse($readableFields, $response);
    }

    protected function testShouldCheckIfEditorCanReadResource(): void
    {
        $readableFields = [
            'id',
            'minute',
            'partOrHalf',
            'teamRelated',
            'order',
            'eventType',
            'gameId'
        ];

        $token = $this->loginUtil->loginAsEditor();

        $id = TestAvailableResources::$gameEvents[0]['id'];

        $response = $this->client->get("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertReadableFieldsFromResponse($readableFields, $response);
    }

    protected function testShouldCheckIfUserCanReadResource(): void
    {
        $readableFields = [
            'id',
            'minute',
            'partOrHalf',
            'teamRelated',
            'order',
            'eventType',
            'gameId'
        ];

        $token = $this->loginUtil->loginWithEmailAndPassword(
            TestAvailableResources::$users[0]['email'],
            TestLoginUtil::DEFAULT_PASSWORD
        );

        $id = TestAvailableResources::$gameEvents[0]['id'];

        $response = $this->client->get("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertReadableFieldsFromResponse($readableFields, $response);
    }

    protected function testShouldCheckIfGuestCanReadResource(): void
    {
        $readableFields = [
            'id',
            'minute',
            'partOrHalf',
            'teamRelated',
            'order',
            'eventType',
            'gameId'
        ];

        $id = TestAvailableResources::$gameEvents[0]['id'];

        $response = $this->client->get("{$this->endpoint}/{$id}");

        $this->assertReadableFieldsFromResponse($readableFields, $response);
    }

    protected function testShouldCheckEditableFieldsByAdmin(): void
    {
        $token = $this->loginUtil->loginAsAdmin();

        $id = TestAvailableResources::$gameEvents[0]['id'];

        $response = $this->client->put("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
            'json' => [
                'minute' => 1,
                'partOrHalf' => GameEventPartOrHalfEnum::FIRST_HALF->value,
                'teamRelated' => GameEventTeamRelatedEnum::TEAM_1->value,
                'order' => 1,
                'eventType' => GameEventEventTypeEnum::GOAL->value,
                'gameId' => $this->randomGameId(),
            ],
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        TestAvailableResources::$gameEvents[0] = json_decode($response->getBody()->getContents(), true);
    }

    protected function testShouldCheckNotEditableFieldsByAdmin(): void
    {
        $this->assertTrue(true);
    }

    protected function testShouldCheckEditableFieldsByModerator(): void
    {
        $token = $this->loginUtil->loginAsModerator();

        $id = TestAvailableResources::$gameEvents[0]['id'];

        $response = $this->client->put("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
            'json' => [
                'minute' => 1,
                'partOrHalf' => GameEventPartOrHalfEnum::FIRST_HALF->value,
                'teamRelated' => GameEventTeamRelatedEnum::TEAM_1->value,
                'order' => 1,
                'eventType' => GameEventEventTypeEnum::GOAL->value,
                'gameId' => $this->randomGameId(),
            ],
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        TestAvailableResources::$gameEvents[0] = json_decode($response->getBody()->getContents(), true);
    }

    protected function testShouldCheckNotEditableFieldsByModerator(): void
    {
        $this->assertTrue(true);
    }

    protected function testShouldCheckEditableFieldsByEditor(): void
    {
        $token = $this->loginUtil->loginAsEditor();

        $id = TestAvailableResources::$gameEvents[0]['id'];

        $response = $this->client->put("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
            'json' => [
                'minute' => 1,
                'partOrHalf' => GameEventPartOrHalfEnum::FIRST_HALF->value,
                'teamRelated' => GameEventTeamRelatedEnum::TEAM_1->value,
                'order' => 1,
                'eventType' => GameEventEventTypeEnum::GOAL->value,
                'gameId' => $this->randomGameId(),
            ],
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        TestAvailableResources::$gameEvents[0] = json_decode($response->getBody()->getContents(), true);
    }

    protected function testShouldCheckNotEditableFieldsByEditor(): void
    {
        $this->assertTrue(true);
    }

    protected function testShouldCheckEditableFieldsByUser(): void
    {
        $this->assertTrue(true);
    }

    protected function testShouldCheckNotEditableFieldsByUser(): void
    {
        $token = $this->loginUtil->loginWithEmailAndPassword(
            TestAvailableResources::$users[0]['email'],
            TestLoginUtil::DEFAULT_PASSWORD
        );

        $id = TestAvailableResources::$gameEvents[0]['id'];

        $response = $this->client->put("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
            'json' => [
                'minute' => 2,
            ],
        ]);

        $this->assertEquals(403, $response->getStatusCode());
    }

    protected function testShouldCheckEditableFieldsByGuest(): void
    {
        $this->assertTrue(true);
    }

    protected function testShouldCheckNotEditableFieldsByGuest(): void
    {
        $id = TestAvailableResources::$gameEvents[0]['id'];

        $response = $this->client->put("{$this->endpoint}/{$id}", [
            'json' => [
                'minute' => 2,
            ],
        ]);

        $this->assertEquals(401, $response->getStatusCode());
    }

    protected function testShouldCheckIfAdminCanDeleteResource(): void
    {
        $token = $this->loginUtil->loginAsAdmin();

        $round = array_pop(TestAvailableResources::$gameEvents);
        $id = $round['id'];

        $response = $this->client->delete("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertEquals(204, $response->getStatusCode());
    }

    protected function testShouldCheckIfModeratorCanDeleteResource(): void
    {
        $token = $this->loginUtil->loginAsModerator();

        $round = array_pop(TestAvailableResources::$gameEvents);
        $id = $round['id'];

        $response = $this->client->delete("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertEquals(204, $response->getStatusCode());
    }

    protected function testShouldCheckIfEditorCanDeleteResource(): void
    {
        $token = $this->loginUtil->loginAsEditor();

        $round = array_pop(TestAvailableResources::$gameEvents);
        $id = $round['id'];

        $response = $this->client->delete("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertEquals(204, $response->getStatusCode());
    }

    protected function testShouldCheckIfUserCanDeleteResource(): void
    {
        $token = $this->loginUtil->loginWithEmailAndPassword(
            TestAvailableResources::$users[0]['email'],
            TestLoginUtil::DEFAULT_PASSWORD
        );

        $id = TestAvailableResources::$gameEvents[0]['id'];

        $response = $this->client->delete("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertEquals(403, $response->getStatusCode());
    }

    protected function testShouldCheckIfGuestCanDeleteResource(): void
    {
        $id = TestAvailableResources::$gameEvents[0]['id'];

        $response = $this->client->delete("{$this->endpoint}/{$id}");

        $this->assertEquals(401, $response->getStatusCode());
    }

    protected function testShouldCheckIfDeletionOfGameDeletesGameEventsAlso(): void
    {
        $token = $this->loginUtil->loginAsAdmin();

        $gameEvent = array_pop(TestAvailableResources::$gameEvents);
        $relatedGameEvents = [
            $gameEvent,
            ...array_filter(
                TestAvailableResources::$gameEvents,
                fn($g) => $g['roundId'] === $gameEvent['gameId']
            )
        ];

        foreach ($relatedGameEvents as $relatedGameEvent) {
            $response = $this->client->get("{$this->endpoint}/{$relatedGameEvent['id']}");
            $this->assertEquals(200, $response->getStatusCode());
        }

        $response = $this->client->delete(
            "games/{$gameEvent['gameId']}",
            ['headers' => ['Authorization' => "Bearer $token"]]
        );
        $this->assertEquals(204, $response->getStatusCode());
        TestAvailableResources::$games = array_values(array_filter(
            TestAvailableResources::$games,
            fn($g) => $g['id'] !== $gameEvent['gameId']
        ));

        foreach ($relatedGameEvents as $relatedGame) {
            $response = $this->client->get("{$this->endpoint}/{$relatedGame['id']}");
            $this->assertEquals(404, $response->getStatusCode());
        }

        TestAvailableResources::$gameEvents = array_values(array_filter(
            TestAvailableResources::$gameEvents,
            fn($ge) => $ge['gameId'] !== $gameEvent['gameId']
        ));
    }

    private function randomGameId(): string
    {
        return TestAvailableResources::$games[array_rand(TestAvailableResources::$games)]['id'];
    }
}
