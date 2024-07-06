<?php

namespace Tests\Modules\Article;

use DateTime;
use GuzzleHttp\Client;
use Tests\Modules\AbstractControllerTest;
use Tests\Util\TestDatabaseTypeEnum;

class ArticleControllerTest extends AbstractControllerTest
{
    public const DEFAULT_ENDPOINT = 'articles';

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
                    'title' => "Title $i",
                    'content' => "Content $i",
                    'draft' => false,
                    'postAt' => (new DateTime())->format('Y-m-d H:i:s'),
                    'seasonTeamsId' => $this->randomSeasonTeamsId()
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
                    'title' => "Title $i",
                    'content' => "Content $i",
                    'draft' => false,
                    'postAt' => (new DateTime())->format('Y-m-d H:i:s'),
                    'seasonTeamsId' => $this->randomSeasonTeamsId()
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
                    'title' => "Title $i",
                    'content' => "Content $i",
                    'draft' => false,
                    'postAt' => (new DateTime())->format('Y-m-d H:i:s'),
                    'seasonTeamsId' => $this->randomSeasonTeamsId()
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
                'title' => "Title",
                'content' => "Content",
                'draft' => false,
                'postAt' => (new DateTime())->format('Y-m-d H:i:s'),
                'seasonTeamsId' => $this->randomSeasonTeamsId()
            ],
        ]);

        $this->assertEquals(403, $response->getStatusCode());
    }

    protected function testShouldCheckIfGuestCanCreateResource(): void
    {
        $response = $this->client->post($this->endpoint, [
            'json' => [
                'title' => "Title",
                'content' => "Content",
                'draft' => false,
                'postAt' => (new DateTime())->format('Y-m-d H:i:s'),
                'seasonTeamsId' => $this->randomSeasonTeamsId()
            ],
        ]);

        $this->assertEquals(401, $response->getStatusCode());
    }

    protected function testShouldReturnPreviouslyCreatedResources(): void
    {
        $token = $this->loginUtil->loginAsAdmin();

        foreach ($this->availableResources->getArticles() as $resource) {
            $response = $this->client->get("{$this->endpoint}/{$resource['id']}", [
                'headers' => ['Authorization' => "Bearer $token"],
            ]);

            $this->assertEquals(200, $response->getStatusCode());
        }
    }

    protected function testShouldReturnCollectionOfAllCreatedResources(): void
    {
        $response = $this->client->get($this->endpoint);

        $this->assertEquals(200, $response->getStatusCode());

        $json = json_decode($response->getBody()->getContents(), true);

        $this->assertCount(count($this->availableResources->getArticles()), $json['data']);
    }

    protected function testShouldCheckIfAdminCanReadResource(): void
    {
        $readableFields = [
            'id',
            'title',
            'content',
            'draft',
            'postAt',
            'seasonTeamsId'
        ];

        $token = $this->loginUtil->loginAsAdmin();

        $id = $this->availableResources->getArticles()[0]['id'];

        $response = $this->client->get("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertReadableFieldsFromResponse($readableFields, $response);
    }

    protected function testShouldCheckIfModeratorCanReadResource(): void
    {
        $readableFields = [
            'id',
            'title',
            'content',
            'draft',
            'postAt',
            'seasonTeamsId'
        ];

        $token = $this->loginUtil->loginAsModerator();

        $id = $this->availableResources->getArticles()[0]['id'];

        $response = $this->client->get("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertReadableFieldsFromResponse($readableFields, $response);
    }

    protected function testShouldCheckIfEditorCanReadResource(): void
    {
        $readableFields = [
            'id',
            'title',
            'content',
            'draft',
            'postAt',
            'seasonTeamsId'
        ];

        $token = $this->loginUtil->loginAsEditor();

        $id = $this->availableResources->getArticles()[0]['id'];

        $response = $this->client->get("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertReadableFieldsFromResponse($readableFields, $response);
    }

    protected function testShouldCheckIfUserCanReadResource(): void
    {
        $readableFields = [
            'id',
            'title',
            'content',
            'postAt',
            'seasonTeamsId'
        ];

        $token = $this->loginUtil->loginWithEmailAndPassword($this->loginUtil->getFirstNonBlockedStandardUser());

        $id = $this->availableResources->getArticles()[0]['id'];

        $response = $this->client->get("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertReadableFieldsFromResponse($readableFields, $response);
    }

    protected function testShouldCheckIfGuestCanReadResource(): void
    {
        $readableFields = [
            'id',
            'title',
            'content',
            'postAt',
            'seasonTeamsId'
        ];

        $id = $this->availableResources->getArticles()[0]['id'];

        $response = $this->client->get("{$this->endpoint}/{$id}");

        $this->assertReadableFieldsFromResponse($readableFields, $response);
    }

    protected function testShouldCheckEditableFieldsByAdmin(): void
    {
        $token = $this->loginUtil->loginAsAdmin();

        $id = $this->availableResources->getArticles()[0]['id'];
        $response = $this->client->put("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
            'json' => [
                'title' => "Title Updated",
                'content' => "Content Updated",
                'draft' => false,
                'postAt' => (new DateTime())->format('Y-m-d H:i:s'),
                'seasonTeamsId' => $this->randomSeasonTeamsId()
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

        $id = $this->availableResources->getArticles()[0]['id'];

        $response = $this->client->put("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
            'json' => [
                'title' => "Title Updated",
                'content' => "Content Updated",
                'draft' => false,
                'postAt' => (new DateTime())->format('Y-m-d H:i:s'),
                'seasonTeamsId' => $this->randomSeasonTeamsId()
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

        $id = $this->availableResources->getArticles()[0]['id'];

        $response = $this->client->put("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
            'json' => [
                'title' => "Title Updated",
                'content' => "Content Updated",
                'draft' => false,
                'postAt' => (new DateTime())->format('Y-m-d H:i:s'),
                'seasonTeamsId' => $this->randomSeasonTeamsId()
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

        $id = $this->availableResources->getArticles()[0]['id'];

        $response = $this->client->put("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
            'json' => [
                'title' => "Title Updated",
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
        $id = $this->availableResources->getArticles()[0]['id'];

        $response = $this->client->put("{$this->endpoint}/{$id}", [
            'json' => [
                'title' => "Title Updated",
            ],
        ]);

        $this->assertEquals(401, $response->getStatusCode());
    }

    protected function testShouldCheckIfAdminCanDeleteResource(): void
    {
        $token = $this->loginUtil->loginAsAdmin();

        $article = $this->availableResources->getArticles()[0];
        $id = $article['id'];

        $response = $this->client->delete("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertEquals(204, $response->getStatusCode());
    }

    protected function testShouldCheckIfModeratorCanDeleteResource(): void
    {
        $token = $this->loginUtil->loginAsModerator();

        $article = $this->availableResources->getArticles()[0];
        $id = $article['id'];

        $response = $this->client->delete("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertEquals(204, $response->getStatusCode());
    }

    protected function testShouldCheckIfEditorCanDeleteResource(): void
    {
        $token = $this->loginUtil->loginAsEditor();

        $article = $this->availableResources->getArticles()[0];
        $id = $article['id'];

        $response = $this->client->delete("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertEquals(204, $response->getStatusCode());
    }

    protected function testShouldCheckIfUserCanDeleteResource(): void
    {
        $token = $this->loginUtil->loginWithEmailAndPassword($this->loginUtil->getFirstNonBlockedStandardUser());

        $id = $this->availableResources->getArticles()[0]['id'];

        $response = $this->client->delete("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertEquals(403, $response->getStatusCode());
    }

    protected function testShouldCheckIfGuestCanDeleteResource(): void
    {
        $id = $this->availableResources->getArticles()[0]['id'];

        $response = $this->client->delete("{$this->endpoint}/{$id}");

        $this->assertEquals(401, $response->getStatusCode());
    }

    protected function testShouldCheckIfDeletionOfSeasonTeamDeletesLinkToIt(): void
    {
        $token = $this->loginUtil->loginAsAdmin();

        $seasonTeamId = $this->availableResources->getArticles()[0]['seasonTeamsId'][0];
        $relatedArticles = array_filter(
            $this->availableResources->getArticles(),
            fn($a) => in_array($seasonTeamId, $a['seasonTeamsId'])
        );

        foreach ($relatedArticles as $relatedArticle) {
            $response = $this->client->get("{$this->endpoint}/{$relatedArticle['id']}");
            $this->assertEquals(200, $response->getStatusCode());
            $this->assertTrue(in_array($seasonTeamId, $relatedArticle['seasonTeamsId']));
        }

        $response = $this->client->delete(
            "season-teams/{$seasonTeamId}",
            ['headers' => ['Authorization' => "Bearer $token"]]
        );

        $this->assertEquals(204, $response->getStatusCode());

        foreach ($relatedArticles as $relatedArticle) {
            $response = $this->client->get("{$this->endpoint}/{$relatedArticle['id']}");
            $this->assertEquals(200, $response->getStatusCode());

            $contents = json_decode($response->getBody()->getContents(), true);
            $this->assertFalse(in_array($seasonTeamId, $contents['seasonTeamsId']));
        }
    }

    private function randomSeasonTeamsId(): array
    {
        $seasonTeams = $this->availableResources->getSeasonTeams();

        return [
            $seasonTeams[count($seasonTeams) - 2]['id'],
            $seasonTeams[count($seasonTeams) - 1]['id']
        ];
    }
}
