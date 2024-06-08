<?php

namespace Tests\Modules\Game;

use App\Modules\Game\Enum\GameResultEnum;
use DateTime;
use GuzzleHttp\Client;
use RuntimeException;
use Tests\Modules\AbstractControllerTest;
use Tests\Util\TestAvailableResources;
use Tests\Util\TestLoginUtil;

class GameControllerTest extends AbstractControllerTest
{
    public function __construct(Client $client)
    {
        parent::__construct($client);

        $this->endpoint = 'games';
    }

    public function clearAfterTests(): void
    {
        TestAvailableResources::$games = [];
    }

    protected function testShouldCheckIfAdminCanCreateResource(): void
    {
        $token = $this->loginUtil->loginAsAdmin();

        for ($i = 1; $i <= 5; $i++) {
            list($roundId, $seasonTeamId) = $this->randomRelatedRoundAndSeasonTeam();
            $response = $this->client->post($this->endpoint, [
                'headers' => ['Authorization' => "Bearer $token"],
                'json' => [
                    'date' => (new DateTime())->format('Y-m-d H:i:s'),
                    'stadium' => "Stadium $i",
                    'team1ScoreHalf' => 1,
                    'team2ScoreHalf' => 1,
                    'team1Score' => 2,
                    'team2Score' => 2,
                    'result' => GameResultEnum::DRAW->value,
                    'viewers' => '1000',
                    'annotation' => "Annotation $i",
                    'roundId' => $roundId,
                    'seasonTeam1Id' => $seasonTeamId,
                    'seasonTeam2Id' => null
                ],
            ]);

            $this->assertEquals(201, $response->getStatusCode());

            TestAvailableResources::$games[] = json_decode($response->getBody()->getContents(), true);
        }
    }

    protected function testShouldCheckIfModeratorCanCreateResource(): void
    {
        $token = $this->loginUtil->loginAsModerator();

        for ($i = 1; $i <= 5; $i++) {
            list($roundId, $seasonTeamId) = $this->randomRelatedRoundAndSeasonTeam();
            $response = $this->client->post($this->endpoint, [
                'headers' => ['Authorization' => "Bearer $token"],
                'json' => [
                    'date' => (new DateTime())->format('Y-m-d H:i:s'),
                    'stadium' => "Stadium $i",
                    'team1ScoreHalf' => 1,
                    'team2ScoreHalf' => 1,
                    'team1Score' => 2,
                    'team2Score' => 2,
                    'result' => GameResultEnum::DRAW->value,
                    'viewers' => '1000',
                    'annotation' => "Annotation $i",
                    'roundId' => $roundId,
                    'seasonTeam1Id' => $seasonTeamId,
                    'seasonTeam2Id' => null
                ],
            ]);

            $this->assertEquals(201, $response->getStatusCode());

            TestAvailableResources::$games[] = json_decode($response->getBody()->getContents(), true);
        }
    }

    protected function testShouldCheckIfEditorCanCreateResource(): void
    {
        $token = $this->loginUtil->loginAsEditor();

        for ($i = 1; $i <= 5; $i++) {
            list($roundId, $seasonTeamId) = $this->randomRelatedRoundAndSeasonTeam();
            $response = $this->client->post($this->endpoint, [
                'headers' => ['Authorization' => "Bearer $token"],
                'json' => [
                    'date' => (new DateTime())->format('Y-m-d H:i:s'),
                    'stadium' => "Stadium $i",
                    'team1ScoreHalf' => 1,
                    'team2ScoreHalf' => 1,
                    'team1Score' => 2,
                    'team2Score' => 2,
                    'result' => GameResultEnum::DRAW->value,
                    'viewers' => '1000',
                    'annotation' => "Annotation $i",
                    'roundId' => $roundId,
                    'seasonTeam1Id' => $seasonTeamId,
                    'seasonTeam2Id' => null
                ],
            ]);

            $this->assertEquals(201, $response->getStatusCode());

            TestAvailableResources::$games[] = json_decode($response->getBody()->getContents(), true);
        }
    }

    protected function testShouldCheckIfUserCanCreateResource(): void
    {
        $user = TestAvailableResources::$users[0];
        $token = $this->loginUtil->loginWithEmailAndPassword($user['email'], TestLoginUtil::DEFAULT_PASSWORD);

        list($roundId, $seasonTeamId) = $this->randomRelatedRoundAndSeasonTeam();
        $response = $this->client->post($this->endpoint, [
            'headers' => ['Authorization' => "Bearer $token"],
            'json' => [
                'date' => (new DateTime())->format('Y-m-d H:i:s'),
                'stadium' => "Stadium",
                'team1ScoreHalf' => 1,
                'team2ScoreHalf' => 1,
                'team1Score' => 2,
                'team2Score' => 2,
                'result' => GameResultEnum::DRAW->value,
                'viewers' => '1000',
                'annotation' => "Annotation",
                'roundId' => $roundId,
                'seasonTeam1Id' => $seasonTeamId,
                'seasonTeam2Id' => null
            ],
        ]);

        $this->assertEquals(403, $response->getStatusCode());
    }

    protected function testShouldCheckIfGuestCanCreateResource(): void
    {
        list($roundId, $seasonTeamId) = $this->randomRelatedRoundAndSeasonTeam();
        $response = $this->client->post($this->endpoint, [
            'json' => [
                'date' => (new DateTime())->format('Y-m-d H:i:s'),
                'stadium' => "Stadium",
                'team1ScoreHalf' => 1,
                'team2ScoreHalf' => 1,
                'team1Score' => 2,
                'team2Score' => 2,
                'result' => GameResultEnum::DRAW->value,
                'viewers' => '1000',
                'annotation' => "Annotation",
                'roundId' => $roundId,
                'seasonTeam1Id' => $seasonTeamId,
                'seasonTeam2Id' => null
            ],
        ]);

        $this->assertEquals(401, $response->getStatusCode());
    }

    protected function testShouldCheckIfCreateThrowErrorWhenSeasonTeamIsFromWrongSeason(): void
    {
        $token = $this->loginUtil->loginAsAdmin();

        list($roundId, $seasonTeamId) = $this->randomNotRelatedRoundAndSeasonTeam();
        $commonBody = [
            'date' => (new DateTime())->format('Y-m-d H:i:s'),
            'stadium' => "Stadium",
            'team1ScoreHalf' => 1,
            'team2ScoreHalf' => 1,
            'team1Score' => 2,
            'team2Score' => 2,
            'result' => GameResultEnum::DRAW->value,
            'viewers' => '1000',
            'annotation' => "Annotation",
            'roundId' => $roundId,
        ];

        $response = $this->client->post("{$this->endpoint}", [
            'headers' => ['Authorization' => "Bearer $token"],
            'json' => array_merge($commonBody, [
                'seasonTeam1Id' => $seasonTeamId,
                'seasonTeam2Id' => null
            ]),
        ]);
        $this->assertEquals(400, $response->getStatusCode());

        $response = $this->client->post("{$this->endpoint}", [
            'headers' => ['Authorization' => "Bearer $token"],
            'json' => array_merge($commonBody, [
                'seasonTeam1Id' => null,
                'seasonTeam2Id' => $seasonTeamId
            ]),
        ]);
        $this->assertEquals(400, $response->getStatusCode());
    }

    protected function testShouldReturnPreviouslyCreatedResources(): void
    {
        $token = $this->loginUtil->loginAsAdmin();

        foreach (TestAvailableResources::$games as $resource) {
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

        $this->assertCount(count(TestAvailableResources::$games), $json['data']);
    }

    protected function testShouldCheckIfAdminCanReadResource(): void
    {
        $readableFields = [
            'id',
            'date',
            'stadium',
            'team1ScoreHalf',
            'team2ScoreHalf',
            'team1Score',
            'team2Score',
            'result',
            'viewers',
            'annotation',
            'roundId',
            'seasonTeam1Id',
            'seasonTeam2Id'
        ];

        $token = $this->loginUtil->loginAsAdmin();

        $id = TestAvailableResources::$games[0]['id'];

        $response = $this->client->get("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertReadableFieldsFromResponse($readableFields, $response);
    }

    protected function testShouldCheckIfModeratorCanReadResource(): void
    {
        $readableFields = [
            'id',
            'date',
            'stadium',
            'team1ScoreHalf',
            'team2ScoreHalf',
            'team1Score',
            'team2Score',
            'result',
            'viewers',
            'annotation',
            'roundId',
            'seasonTeam1Id',
            'seasonTeam2Id'
        ];

        $token = $this->loginUtil->loginAsModerator();

        $id = TestAvailableResources::$games[0]['id'];

        $response = $this->client->get("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertReadableFieldsFromResponse($readableFields, $response);
    }

    protected function testShouldCheckIfEditorCanReadResource(): void
    {
        $readableFields = [
            'id',
            'date',
            'stadium',
            'team1ScoreHalf',
            'team2ScoreHalf',
            'team1Score',
            'team2Score',
            'result',
            'viewers',
            'annotation',
            'roundId',
            'seasonTeam1Id',
            'seasonTeam2Id'
        ];

        $token = $this->loginUtil->loginAsEditor();

        $id = TestAvailableResources::$games[0]['id'];

        $response = $this->client->get("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertReadableFieldsFromResponse($readableFields, $response);
    }

    protected function testShouldCheckIfUserCanReadResource(): void
    {
        $readableFields = [
            'id',
            'date',
            'stadium',
            'team1ScoreHalf',
            'team2ScoreHalf',
            'team1Score',
            'team2Score',
            'result',
            'viewers',
            'annotation',
            'roundId',
            'seasonTeam1Id',
            'seasonTeam2Id'
        ];

        $token = $this->loginUtil->loginWithEmailAndPassword(
            TestAvailableResources::$users[0]['email'],
            TestLoginUtil::DEFAULT_PASSWORD
        );

        $id = TestAvailableResources::$games[0]['id'];

        $response = $this->client->get("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertReadableFieldsFromResponse($readableFields, $response);
    }

    protected function testShouldCheckIfGuestCanReadResource(): void
    {
        $readableFields = [
            'id',
            'date',
            'stadium',
            'team1ScoreHalf',
            'team2ScoreHalf',
            'team1Score',
            'team2Score',
            'result',
            'viewers',
            'annotation',
            'roundId',
            'seasonTeam1Id',
            'seasonTeam2Id'
        ];

        $id = TestAvailableResources::$games[0]['id'];

        $response = $this->client->get("{$this->endpoint}/{$id}");

        $this->assertReadableFieldsFromResponse($readableFields, $response);
    }

    protected function testShouldCheckEditableFieldsByAdmin(): void
    {
        $token = $this->loginUtil->loginAsAdmin();

        $id = TestAvailableResources::$games[0]['id'];

        list($roundId, $seasonTeamId) = $this->randomRelatedRoundAndSeasonTeam();
        $response = $this->client->put("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
            'json' => [
                'date' => (new DateTime())->format('Y-m-d H:i:s'),
                'stadium' => "Stadium",
                'team1ScoreHalf' => 1,
                'team2ScoreHalf' => 1,
                'team1Score' => 2,
                'team2Score' => 2,
                'result' => GameResultEnum::DRAW->value,
                'viewers' => '1000',
                'annotation' => "Annotation",
                'roundId' => $roundId,
                'seasonTeam1Id' => $seasonTeamId,
                'seasonTeam2Id' => null
            ],
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        TestAvailableResources::$games[0] = json_decode($response->getBody()->getContents(), true);
    }

    protected function testShouldCheckNotEditableFieldsByAdmin(): void
    {
        $this->assertTrue(true);
    }

    protected function testShouldCheckEditableFieldsByModerator(): void
    {
        $token = $this->loginUtil->loginAsModerator();

        $id = TestAvailableResources::$games[0]['id'];

        list($roundId, $seasonTeamId) = $this->randomRelatedRoundAndSeasonTeam();
        $response = $this->client->put("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
            'json' => [
                'date' => (new DateTime())->format('Y-m-d H:i:s'),
                'stadium' => "Stadium",
                'team1ScoreHalf' => 1,
                'team2ScoreHalf' => 1,
                'team1Score' => 2,
                'team2Score' => 2,
                'result' => GameResultEnum::DRAW->value,
                'viewers' => '1000',
                'annotation' => "Annotation",
                'roundId' => $roundId,
                'seasonTeam1Id' => $seasonTeamId,
                'seasonTeam2Id' => null
            ],
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        TestAvailableResources::$games[0] = json_decode($response->getBody()->getContents(), true);
    }

    protected function testShouldCheckNotEditableFieldsByModerator(): void
    {
        $this->assertTrue(true);
    }

    protected function testShouldCheckEditableFieldsByEditor(): void
    {
        $token = $this->loginUtil->loginAsEditor();

        $id = TestAvailableResources::$games[0]['id'];

        list($roundId, $seasonTeamId) = $this->randomRelatedRoundAndSeasonTeam();
        $response = $this->client->put("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
            'json' => [
                'date' => (new DateTime())->format('Y-m-d H:i:s'),
                'stadium' => "Stadium",
                'team1ScoreHalf' => 1,
                'team2ScoreHalf' => 1,
                'team1Score' => 2,
                'team2Score' => 2,
                'result' => GameResultEnum::DRAW->value,
                'viewers' => '1000',
                'annotation' => "Annotation",
                'roundId' => $roundId,
                'seasonTeam1Id' => $seasonTeamId,
                'seasonTeam2Id' => null
            ],
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        TestAvailableResources::$games[0] = json_decode($response->getBody()->getContents(), true);
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

        $id = TestAvailableResources::$games[0]['id'];

        $response = $this->client->put("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
            'json' => [
                'team1Score' => 1,
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
        $id = TestAvailableResources::$games[0]['id'];

        $response = $this->client->put("{$this->endpoint}/{$id}", [
            'json' => [
                'team1Score' => 1,
            ],
        ]);

        $this->assertEquals(401, $response->getStatusCode());
    }

    protected function testShouldCheckIfUpdateThrowErrorWhenSeasonTeamIsFromWrongSeason(): void
    {
        $token = $this->loginUtil->loginAsAdmin();

        $id = TestAvailableResources::$games[0]['id'];

        list($roundId, $seasonTeamId) = $this->randomNotRelatedRoundAndSeasonTeam();

        $response = $this->client->put("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
            'json' => [
                'roundId' => $roundId,
                'seasonTeam1Id' => $seasonTeamId,
            ],
        ]);
        $this->assertEquals(400, $response->getStatusCode());

        $response = $this->client->put("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
            'json' => [
                'roundId' => $roundId,
                'seasonTeam2Id' => $seasonTeamId,
            ],
        ]);
        $this->assertEquals(400, $response->getStatusCode());
    }

    protected function testShouldCheckIfAdminCanDeleteResource(): void
    {
        $token = $this->loginUtil->loginAsAdmin();

        $round = array_pop(TestAvailableResources::$games);
        $id = $round['id'];

        $response = $this->client->delete("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertEquals(204, $response->getStatusCode());
    }

    protected function testShouldCheckIfModeratorCanDeleteResource(): void
    {
        $token = $this->loginUtil->loginAsModerator();

        $round = array_pop(TestAvailableResources::$games);
        $id = $round['id'];

        $response = $this->client->delete("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertEquals(204, $response->getStatusCode());
    }

    protected function testShouldCheckIfEditorCanDeleteResource(): void
    {
        $token = $this->loginUtil->loginAsEditor();

        $round = array_pop(TestAvailableResources::$games);
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

        $id = TestAvailableResources::$games[0]['id'];

        $response = $this->client->delete("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertEquals(403, $response->getStatusCode());
    }

    protected function testShouldCheckIfGuestCanDeleteResource(): void
    {
        $id = TestAvailableResources::$games[0]['id'];

        $response = $this->client->delete("{$this->endpoint}/{$id}");

        $this->assertEquals(401, $response->getStatusCode());
    }

    protected function testShouldCheckIfDeletionOfRoundDeletesGamesAlso(): void
    {
        $token = $this->loginUtil->loginAsAdmin();

        $game = array_pop(TestAvailableResources::$games);
        $relatedGames = [
            $game,
            ...array_filter(
                TestAvailableResources::$games,
                fn($g) => $g['roundId'] === $game['roundId']
            )
        ];

        foreach ($relatedGames as $relatedGame) {
            $response = $this->client->get("{$this->endpoint}/{$relatedGame['id']}");
            $this->assertEquals(200, $response->getStatusCode());
        }

        $response = $this->client->delete(
            "rounds/{$game['roundId']}",
            ['headers' => ['Authorization' => "Bearer $token"]]
        );
        $this->assertEquals(204, $response->getStatusCode());
        TestAvailableResources::$rounds = array_filter(
            TestAvailableResources::$rounds,
            fn($r) => $r['id'] !== $game['roundId']
        );

        foreach ($relatedGames as $relatedGame) {
            $response = $this->client->get("{$this->endpoint}/{$relatedGame['id']}");
            $this->assertEquals(404, $response->getStatusCode());
        }

        TestAvailableResources::$games = array_values(array_filter(
            TestAvailableResources::$games,
            fn($g) => $g['roundId'] !== $game['roundId']
        ));
    }

    public function testShouldCheckIfDeletionOfSeasonTeamSetsItToNull(): void
    {
        $game = TestAvailableResources::$games[0];

        $gameResponse = $this->client->get("{$this->endpoint}/{$game['id']}");

        $this->assertEquals(200, $gameResponse->getStatusCode());
        $gameJson = json_decode($gameResponse->getBody()->getContents(), true);
        $this->assertEquals($gameJson['seasonTeam1Id'], $game['seasonTeam1Id']);
        $this->assertNotNull($gameJson['seasonTeam1Id']);

        $token = $this->loginUtil->loginAsAdmin();
        $this->client->delete("season-teams/{$game['seasonTeam1Id']}", [
            'headers' => ['Authorization' => "Bearer $token"]
        ]);

        $gameResponse = $this->client->get("{$this->endpoint}/{$game['id']}");
        $gameJson = json_decode($gameResponse->getBody()->getContents(), true);
        $this->assertNull($gameJson['seasonTeam1Id']);

        TestAvailableResources::$games[0] = $gameJson;
    }

    private function randomRelatedRoundAndSeasonTeam(): array
    {
        shuffle(TestAvailableResources::$seasonTeams);
        foreach (TestAvailableResources::$seasonTeams as $seasonTeam) {
            $foundRounds = array_filter(
                TestAvailableResources::$rounds,
                fn($round) => $round['seasonId'] === $seasonTeam['seasonId']
            );

            if (count($foundRounds) > 0) {
                return [
                    $foundRounds[array_rand($foundRounds)]['id'],
                    $seasonTeam['id']
                ];
            }
        }

        throw new RuntimeException('No related round and season team found.');
    }

    private function randomNotRelatedRoundAndSeasonTeam(): array
    {
        shuffle(TestAvailableResources::$seasonTeams);
        foreach (TestAvailableResources::$seasonTeams as $seasonTeam) {
            $foundRounds = array_filter(
                TestAvailableResources::$rounds,
                fn($round) => $round['seasonId'] !== $seasonTeam['seasonId']
            );

            if (count($foundRounds) > 0) {
                return [
                    $foundRounds[array_rand($foundRounds)]['id'],
                    $seasonTeam['id']
                ];
            }
        }

        throw new RuntimeException('Only related round and season team are available.');
    }
}
