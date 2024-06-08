<?php

namespace Tests\Modules\Round;

use DateTime;
use GuzzleHttp\Client;
use Tests\Modules\AbstractControllerTest;
use Tests\Util\TestAvailableResources;
use Tests\Util\TestLoginUtil;

class RoundControllerTest extends AbstractControllerTest
{
    public function __construct(Client $client)
    {
        parent::__construct($client);

        $this->endpoint = 'rounds';
    }

    public function clearAfterTests(): void
    {
        TestAvailableResources::$rounds = [];
    }

    protected function testShouldCheckIfAdminCanCreateResource(): void
    {
        $token = $this->loginUtil->loginAsAdmin();

        for ($i = 1; $i <= 5; $i++) {
            $response = $this->client->post($this->endpoint, [
                'headers' => ['Authorization' => "Bearer $token"],
                'json' => [
                    'number' => $i,
                    'standardStartDate' => (new DateTime())->format('Y-m-d'),
                    'standardEndDate' => (new DateTime())->format('Y-m-d'),
                    'seasonId' => $this->randomSeasonId()
                ],
            ]);

            $this->assertEquals(201, $response->getStatusCode());

            TestAvailableResources::$rounds[] = json_decode($response->getBody()->getContents(), true);
        }
    }

    protected function testShouldCheckIfModeratorCanCreateResource(): void
    {
        $token = $this->loginUtil->loginAsModerator();

        for ($i = 1; $i <= 5; $i++) {
            $response = $this->client->post($this->endpoint, [
                'headers' => ['Authorization' => "Bearer $token"],
                'json' => [
                    'number' => $i,
                    'standardStartDate' => (new DateTime())->format('Y-m-d'),
                    'standardEndDate' => (new DateTime())->format('Y-m-d'),
                    'seasonId' => $this->randomSeasonId()
                ],
            ]);

            $this->assertEquals(201, $response->getStatusCode());

            TestAvailableResources::$rounds[] = json_decode($response->getBody()->getContents(), true);
        }
    }

    protected function testShouldCheckIfEditorCanCreateResource(): void
    {
        $token = $this->loginUtil->loginAsEditor();

        for ($i = 1; $i <= 5; $i++) {
            $response = $this->client->post($this->endpoint, [
                'headers' => ['Authorization' => "Bearer $token"],
                'json' => [
                    'number' => $i,
                    'standardStartDate' => (new DateTime())->format('Y-m-d'),
                    'standardEndDate' => (new DateTime())->format('Y-m-d'),
                    'seasonId' => $this->randomSeasonId()
                ],
            ]);

            $this->assertEquals(201, $response->getStatusCode());

            TestAvailableResources::$rounds[] = json_decode($response->getBody()->getContents(), true);
        }
    }

    protected function testShouldCheckIfUserCanCreateResource(): void
    {
        $user = TestAvailableResources::$users[0];
        $token = $this->loginUtil->loginWithEmailAndPassword($user['email'], TestLoginUtil::DEFAULT_PASSWORD);

        $response = $this->client->post($this->endpoint, [
            'headers' => ['Authorization' => "Bearer $token"],
            'json' => [
                'number' => 1,
                'standardStartDate' => (new DateTime())->format('Y-m-d'),
                'standardEndDate' => (new DateTime())->format('Y-m-d'),
                'seasonId' => $this->randomSeasonId()
            ],
        ]);

        $this->assertEquals(403, $response->getStatusCode());
    }

    protected function testShouldCheckIfGuestCanCreateResource(): void
    {
        $response = $this->client->post($this->endpoint, [
            'json' => [
                'number' => 1,
                'standardStartDate' => (new DateTime())->format('Y-m-d'),
                'standardEndDate' => (new DateTime())->format('Y-m-d'),
                'seasonId' => $this->randomSeasonId()
            ],
        ]);

        $this->assertEquals(401, $response->getStatusCode());
    }

    protected function testShouldReturnPreviouslyCreatedResources(): void
    {
        $token = $this->loginUtil->loginAsAdmin();

        foreach (TestAvailableResources::$rounds as $resource) {
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

        $this->assertCount(count(TestAvailableResources::$rounds), $json['data']);
    }

    protected function testShouldCheckIfAdminCanReadResource(): void
    {
        $readableFields = [
            'id',
            'number',
            'standardStartDate',
            'standardEndDate',
            'seasonId'
        ];

        $token = $this->loginUtil->loginAsAdmin();

        $id = TestAvailableResources::$rounds[0]['id'];

        $response = $this->client->get("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertReadableFieldsFromResponse($readableFields, $response);
    }

    protected function testShouldCheckIfModeratorCanReadResource(): void
    {
        $readableFields = [
            'id',
            'number',
            'standardStartDate',
            'standardEndDate',
            'seasonId'
        ];

        $token = $this->loginUtil->loginAsModerator();

        $id = TestAvailableResources::$rounds[0]['id'];

        $response = $this->client->get("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertReadableFieldsFromResponse($readableFields, $response);
    }

    protected function testShouldCheckIfEditorCanReadResource(): void
    {
        $readableFields = [
            'id',
            'number',
            'standardStartDate',
            'standardEndDate',
            'seasonId'
        ];

        $token = $this->loginUtil->loginAsEditor();

        $id = TestAvailableResources::$rounds[0]['id'];

        $response = $this->client->get("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertReadableFieldsFromResponse($readableFields, $response);
    }

    protected function testShouldCheckIfUserCanReadResource(): void
    {
        $readableFields = [
            'id',
            'number',
            'standardStartDate',
            'standardEndDate',
            'seasonId'
        ];

        $token = $this->loginUtil->loginWithEmailAndPassword(
            TestAvailableResources::$users[0]['email'],
            TestLoginUtil::DEFAULT_PASSWORD
        );

        $id = TestAvailableResources::$rounds[0]['id'];

        $response = $this->client->get("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertReadableFieldsFromResponse($readableFields, $response);
    }

    protected function testShouldCheckIfGuestCanReadResource(): void
    {
        $readableFields = [
            'id',
            'number',
            'standardStartDate',
            'standardEndDate',
            'seasonId'
        ];

        $id = TestAvailableResources::$rounds[0]['id'];

        $response = $this->client->get("{$this->endpoint}/{$id}");

        $this->assertReadableFieldsFromResponse($readableFields, $response);
    }

    protected function testShouldCheckEditableFieldsByAdmin(): void
    {
        $token = $this->loginUtil->loginAsAdmin();

        $id = TestAvailableResources::$rounds[0]['id'];

        $response = $this->client->put("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
            'json' => [
                'number' => 1,
                'standardStartDate' => (new DateTime())->format('Y-m-d'),
                'standardEndDate' => (new DateTime())->format('Y-m-d'),
                'seasonId' => $this->randomSeasonId()
            ],
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        TestAvailableResources::$rounds[0] = json_decode($response->getBody()->getContents(), true);
    }

    protected function testShouldCheckNotEditableFieldsByAdmin(): void
    {
        $this->assertTrue(true);
    }

    protected function testShouldCheckEditableFieldsByModerator(): void
    {
        $token = $this->loginUtil->loginAsModerator();

        $id = TestAvailableResources::$rounds[0]['id'];

        $response = $this->client->put("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
            'json' => [
                'number' => 1,
                'standardStartDate' => (new DateTime())->format('Y-m-d'),
                'standardEndDate' => (new DateTime())->format('Y-m-d'),
                'seasonId' => $this->randomSeasonId()
            ],
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        TestAvailableResources::$rounds[0] = json_decode($response->getBody()->getContents(), true);
    }

    protected function testShouldCheckNotEditableFieldsByModerator(): void
    {
        $this->assertTrue(true);
    }

    protected function testShouldCheckEditableFieldsByEditor(): void
    {
        $token = $this->loginUtil->loginAsEditor();

        $id = TestAvailableResources::$rounds[0]['id'];

        $response = $this->client->put("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
            'json' => [
                'number' => 1,
                'standardStartDate' => (new DateTime())->format('Y-m-d'),
                'standardEndDate' => (new DateTime())->format('Y-m-d'),
                'seasonId' => $this->randomSeasonId()
            ],
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        TestAvailableResources::$rounds[0] = json_decode($response->getBody()->getContents(), true);
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

        $id = TestAvailableResources::$rounds[0]['id'];

        $response = $this->client->put("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
            'json' => [
                'number' => 2,
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
        $id = TestAvailableResources::$rounds[0]['id'];

        $response = $this->client->put("{$this->endpoint}/{$id}", [
            'json' => [
                'number' => 2,
            ],
        ]);

        $this->assertEquals(401, $response->getStatusCode());
    }

    protected function testShouldCheckIfAdminCanDeleteResource(): void
    {
        $token = $this->loginUtil->loginAsAdmin();

        $round = array_pop(TestAvailableResources::$rounds);
        $id = $round['id'];

        $response = $this->client->delete("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertEquals(204, $response->getStatusCode());
    }

    protected function testShouldCheckIfModeratorCanDeleteResource(): void
    {
        $token = $this->loginUtil->loginAsModerator();

        $round = array_pop(TestAvailableResources::$rounds);
        $id = $round['id'];

        $response = $this->client->delete("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertEquals(204, $response->getStatusCode());
    }

    protected function testShouldCheckIfEditorCanDeleteResource(): void
    {
        $token = $this->loginUtil->loginAsEditor();

        $round = array_pop(TestAvailableResources::$rounds);
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

        $id = TestAvailableResources::$rounds[0]['id'];

        $response = $this->client->delete("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertEquals(403, $response->getStatusCode());
    }

    protected function testShouldCheckIfGuestCanDeleteResource(): void
    {
        $id = TestAvailableResources::$rounds[0]['id'];

        $response = $this->client->delete("{$this->endpoint}/{$id}");

        $this->assertEquals(401, $response->getStatusCode());
    }

    protected function testShouldCheckIfDeletionOfSeasonDeletesRoundsAlso(): void
    {
        $token = $this->loginUtil->loginAsAdmin();

        $round = array_pop(TestAvailableResources::$rounds);
        $relatedRounds = [
            $round,
            ...array_filter(
                TestAvailableResources::$rounds,
                fn($s) => $s['seasonId'] === $round['seasonId']
            )
        ];

        foreach ($relatedRounds as $relatedRound) {
            $response = $this->client->get("{$this->endpoint}/{$relatedRound['id']}");
            $this->assertEquals(200, $response->getStatusCode());
        }

        $response = $this->client->delete(
            "seasons/{$round['seasonId']}",
            ['headers' => ['Authorization' => "Bearer $token"]]
        );
        $this->assertEquals(204, $response->getStatusCode());
        TestAvailableResources::$seasons = array_values(array_filter(
            TestAvailableResources::$seasons,
            fn($s) => $s['id'] !== $round['seasonId']
        ));

        foreach ($relatedRounds as $relatedRound) {
            $response = $this->client->get("{$this->endpoint}/{$relatedRound['id']}");
            $this->assertEquals(404, $response->getStatusCode());
        }

        TestAvailableResources::$rounds = array_values(array_filter(
            TestAvailableResources::$rounds,
            fn($r) => $r['seasonId'] !== $round['seasonId']
        ));
    }

    private function randomSeasonId(): string
    {
        return TestAvailableResources::$seasons[
            array_rand(TestAvailableResources::$seasons)
        ]['id'];
    }
}
