<?php

namespace Tests\Modules\GameEvent;

use App\Modules\GameEvent\Enum\GameEventEventTypeEnum;
use App\Modules\GameEvent\Enum\GameEventPartOrHalfEnum;
use App\Modules\GameEvent\Enum\GameEventTeamRelatedEnum;
use GuzzleHttp\Client;
use Tests\Modules\AbstractControllerTest;
use Tests\Util\TestDatabaseTypeEnum;

class GameEventControllerTest extends AbstractControllerTest
{
    public const DEFAULT_ENDPOINT = 'game-events';

    public function __construct(Client $client, TestDatabaseTypeEnum $databaseType)
    {
        parent::__construct($client, $databaseType);

        $this->endpoint = self::DEFAULT_ENDPOINT;
    }

    protected function testShouldCheckIfAdminCanCreateResource(): void
    {
        $token = $this->loginUtil->loginAsAdmin();

        for ($i = 1; $i <= 10; $i++) {
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
        }
    }

    protected function testShouldCheckIfModeratorCanCreateResource(): void
    {
        $token = $this->loginUtil->loginAsModerator();

        for ($i = 1; $i <= 10; $i++) {
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
        }
    }

    protected function testShouldCheckIfEditorCanCreateResource(): void
    {
        $token = $this->loginUtil->loginAsEditor();

        for ($i = 1; $i <= 10; $i++) {
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
        }
    }

    protected function testShouldCheckIfUserCanCreateResource(): void
    {
        $token = $this->loginUtil->loginWithEmailAndPassword($this->loginUtil->getFirstNonBlockedStandardUser());

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

        foreach ($this->availableResources->getGameEvents() as $resource) {
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

        $this->assertCount(count($this->availableResources->getGameEvents()), $json['data']);
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

        $id = $this->availableResources->getGameEvents()[0]['id'];

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

        $id = $this->availableResources->getGameEvents()[0]['id'];

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

        $id = $this->availableResources->getGameEvents()[0]['id'];

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

        $token = $this->loginUtil->loginWithEmailAndPassword($this->loginUtil->getFirstNonBlockedStandardUser());

        $id = $this->availableResources->getGameEvents()[0]['id'];

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

        $id = $this->availableResources->getGameEvents()[0]['id'];

        $response = $this->client->get("{$this->endpoint}/{$id}");

        $this->assertReadableFieldsFromResponse($readableFields, $response);
    }

    protected function testShouldCheckEditableFieldsByAdmin(): void
    {
        $token = $this->loginUtil->loginAsAdmin();

        $id = $this->availableResources->getGameEvents()[0]['id'];

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
    }

    protected function testShouldCheckNotEditableFieldsByAdmin(): void
    {
        $this->assertTrue(true);
    }

    protected function testShouldCheckEditableFieldsByModerator(): void
    {
        $token = $this->loginUtil->loginAsModerator();

        $id = $this->availableResources->getGameEvents()[0]['id'];

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
    }

    protected function testShouldCheckNotEditableFieldsByModerator(): void
    {
        $this->assertTrue(true);
    }

    protected function testShouldCheckEditableFieldsByEditor(): void
    {
        $token = $this->loginUtil->loginAsEditor();

        $id = $this->availableResources->getGameEvents()[0]['id'];

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
        $token = $this->loginUtil->loginWithEmailAndPassword($this->loginUtil->getFirstNonBlockedStandardUser());

        $id = $this->availableResources->getGameEvents()[0]['id'];

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
        $id = $this->availableResources->getGameEvents()[0]['id'];

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

        $round = $this->availableResources->getGameEvents()[0];
        $id = $round['id'];

        $response = $this->client->delete("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertEquals(204, $response->getStatusCode());
    }

    protected function testShouldCheckIfModeratorCanDeleteResource(): void
    {
        $token = $this->loginUtil->loginAsModerator();

        $round = $this->availableResources->getGameEvents()[0];
        $id = $round['id'];

        $response = $this->client->delete("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertEquals(204, $response->getStatusCode());
    }

    protected function testShouldCheckIfEditorCanDeleteResource(): void
    {
        $token = $this->loginUtil->loginAsEditor();

        $round = $this->availableResources->getGameEvents()[0];
        $id = $round['id'];

        $response = $this->client->delete("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertEquals(204, $response->getStatusCode());
    }

    protected function testShouldCheckIfUserCanDeleteResource(): void
    {
        $token = $this->loginUtil->loginWithEmailAndPassword($this->loginUtil->getFirstNonBlockedStandardUser());

        $id = $this->availableResources->getGameEvents()[0]['id'];

        $response = $this->client->delete("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertEquals(403, $response->getStatusCode());
    }

    protected function testShouldCheckIfGuestCanDeleteResource(): void
    {
        $id = $this->availableResources->getGameEvents()[0]['id'];

        $response = $this->client->delete("{$this->endpoint}/{$id}");

        $this->assertEquals(401, $response->getStatusCode());
    }

    protected function testShouldCheckIfDeletionOfGameDeletesGameEventsAlso(): void
    {
        $token = $this->loginUtil->loginAsAdmin();

        $gameEvent = $this->availableResources->getGameEvents()[0];
        $relatedGameEvents = [
            $gameEvent,
            ...array_filter(
                $this->availableResources->getGameEvents(),
                fn($g) => $g['gameId'] === $gameEvent['gameId']
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

        foreach ($relatedGameEvents as $relatedGame) {
            $response = $this->client->get("{$this->endpoint}/{$relatedGame['id']}");
            $this->assertEquals(404, $response->getStatusCode());
        }
    }

    private function randomGameId(): string
    {
        $games = $this->availableResources->getGames();
        return $games[array_rand($games)]['id'];
    }
}
