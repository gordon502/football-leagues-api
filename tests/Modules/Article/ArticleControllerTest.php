<?php

namespace Tests\Modules\Article;

use DateTime;
use GuzzleHttp\Client;
use Tests\Modules\AbstractControllerTest;
use Tests\Util\TestAvailableResources;
use Tests\Util\TestLoginUtil;

class ArticleControllerTest extends AbstractControllerTest
{
    public function __construct(Client $client)
    {
        parent::__construct($client);

        $this->endpoint = 'articles';
    }

    public function clearAfterTests(): void
    {
        TestAvailableResources::$articles = [];
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

            TestAvailableResources::$articles[] = json_decode($response->getBody()->getContents(), true);
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

            TestAvailableResources::$articles[] = json_decode($response->getBody()->getContents(), true);
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

            TestAvailableResources::$articles[] = json_decode($response->getBody()->getContents(), true);
        }
    }

    protected function testShouldCheckIfUserCanCreateResource(): void
    {
        $user = TestAvailableResources::$users[0];
        $token = $this->loginUtil->loginWithEmailAndPassword($user['email'], TestLoginUtil::DEFAULT_PASSWORD);

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

        foreach (TestAvailableResources::$articles as $resource) {
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

        $this->assertCount(count(TestAvailableResources::$articles), $json['data']);
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

        $id = TestAvailableResources::$articles[0]['id'];

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

        $id = TestAvailableResources::$articles[0]['id'];

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

        $id = TestAvailableResources::$articles[0]['id'];

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

        $token = $this->loginUtil->loginWithEmailAndPassword(
            TestAvailableResources::$users[0]['email'],
            TestLoginUtil::DEFAULT_PASSWORD
        );

        $id = TestAvailableResources::$articles[0]['id'];

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

        $id = TestAvailableResources::$articles[0]['id'];

        $response = $this->client->get("{$this->endpoint}/{$id}");

        $this->assertReadableFieldsFromResponse($readableFields, $response);
    }

    protected function testShouldCheckEditableFieldsByAdmin(): void
    {
        $token = $this->loginUtil->loginAsAdmin();

        $id = TestAvailableResources::$articles[0]['id'];
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

        TestAvailableResources::$articles[0] = json_decode($response->getBody()->getContents(), true);
    }

    protected function testShouldCheckNotEditableFieldsByAdmin(): void
    {
        $this->assertTrue(true);
    }

    protected function testShouldCheckEditableFieldsByModerator(): void
    {
        $token = $this->loginUtil->loginAsModerator();

        $id = TestAvailableResources::$articles[0]['id'];

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

        TestAvailableResources::$articles[0] = json_decode($response->getBody()->getContents(), true);
    }

    protected function testShouldCheckNotEditableFieldsByModerator(): void
    {
        $this->assertTrue(true);
    }

    protected function testShouldCheckEditableFieldsByEditor(): void
    {
        $token = $this->loginUtil->loginAsEditor();

        $id = TestAvailableResources::$articles[0]['id'];

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

        TestAvailableResources::$articles[0] = json_decode($response->getBody()->getContents(), true);
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

        $id = TestAvailableResources::$articles[0]['id'];

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
        $id = TestAvailableResources::$articles[0]['id'];

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

        $article = array_pop(TestAvailableResources::$articles);
        $id = $article['id'];

        $response = $this->client->delete("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertEquals(204, $response->getStatusCode());
    }

    protected function testShouldCheckIfModeratorCanDeleteResource(): void
    {
        $token = $this->loginUtil->loginAsModerator();

        $article = array_pop(TestAvailableResources::$articles);
        $id = $article['id'];

        $response = $this->client->delete("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertEquals(204, $response->getStatusCode());
    }

    protected function testShouldCheckIfEditorCanDeleteResource(): void
    {
        $token = $this->loginUtil->loginAsEditor();

        $article = array_pop(TestAvailableResources::$articles);
        $id = $article['id'];

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

        $id = TestAvailableResources::$articles[0]['id'];

        $response = $this->client->delete("{$this->endpoint}/{$id}", [
            'headers' => ['Authorization' => "Bearer $token"],
        ]);

        $this->assertEquals(403, $response->getStatusCode());
    }

    protected function testShouldCheckIfGuestCanDeleteResource(): void
    {
        $id = TestAvailableResources::$articles[0]['id'];

        $response = $this->client->delete("{$this->endpoint}/{$id}");

        $this->assertEquals(401, $response->getStatusCode());
    }

    protected function testShouldCheckIfDeletionOfSeasonTeamDeletesLinkToIt(): void
    {
        $token = $this->loginUtil->loginAsAdmin();

        $seasonTeamId = TestAvailableResources::$articles[0]['seasonTeamsId'][0];
        $relatedArticles = array_filter(
            TestAvailableResources::$articles,
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
//        var_dump($response->getBody()->getContents());
        $this->assertEquals(204, $response->getStatusCode());
        TestAvailableResources::$seasonTeams = array_values(array_filter(
            TestAvailableResources::$seasonTeams,
            fn($st) => $st['id'] !== $seasonTeamId
        ));

        foreach ($relatedArticles as $relatedArticle) {
            $response = $this->client->get("{$this->endpoint}/{$relatedArticle['id']}");
            $this->assertEquals(200, $response->getStatusCode());

            $contents = json_decode($response->getBody()->getContents(), true);
            $this->assertFalse(in_array($seasonTeamId, $contents['seasonTeamsId']));
            TestAvailableResources::$articles = array_values(array_map(
                fn($a) => $a['id'] === $relatedArticle['id'] ? $contents : $a,
                TestAvailableResources::$articles
            ));
        }
    }

    private function randomSeasonTeamsId(): array
    {
        return [
            TestAvailableResources::$seasonTeams[0]['id'],
            TestAvailableResources::$seasonTeams[1]['id']
        ];
    }
}
