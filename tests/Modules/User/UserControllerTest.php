<?php

namespace Tests\Modules\User;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Tests\Modules\AbstractControllerTest;
use Tests\Util\TestLoginUtil;

// TODO: Collection tests!
class UserControllerTest extends AbstractControllerTest
{
    private array $createdUsers = [];

    public function __construct(Client $client)
    {
        $this->endpoint = 'users';

        parent::__construct($client);
    }

    public function clearAfterTests(): void
    {
        $this->createdUsers = [];
    }

    protected function testShouldReturnInitialCollection(): void
    {
        $response = $this->client->get($this->endpoint);

        $this->assertEquals(200, $response->getStatusCode());

        $json = json_decode($response->getBody()->getContents(), true);

        $this->assertCount(3, $json['data']);
        $this->assertEquals(3, $json['pagination']['total']);
        $this->assertEquals(1, $json['pagination']['currentPage']);
    }

    protected function testShouldCheckIfAdminCanCreateResource(): void
    {
        $token = $this->loginUtil->loginAsAdmin();

        $response = $this->registerUserRequest($token, 'admin');

        $this->assertEquals(201, $response->getStatusCode());

        $this->createdUsers[] = json_decode($response->getBody()->getContents(), true);
    }

    protected function testShouldCheckIfModeratorCanCreateResource(): void
    {
        $token = $this->loginUtil->loginAsModerator();

        $response = $this->registerUserRequest($token, 'moderator');

        $this->assertEquals(201, $response->getStatusCode());

        $this->createdUsers[] = json_decode($response->getBody()->getContents(), true);
    }

    protected function testShouldCheckIfEditorCanCreateResource(): void
    {
        $token = $this->loginUtil->loginAsEditor();

        $response = $this->registerUserRequest($token, 'editor');

        $this->assertEquals(201, $response->getStatusCode());

        $this->createdUsers[] = json_decode($response->getBody()->getContents(), true);
    }

    protected function testShouldCheckIfUserCanCreateResource(): void
    {
        $token = $this->loginUtil->loginWithEmailAndPassword(
            email: $this->createdUsers[0]['email'],
            password: TestLoginUtil::DEFAULT_PASSWORD
        );

        $response = $this->registerUserRequest($token, 'user');

        $this->assertEquals(201, $response->getStatusCode());

        $this->createdUsers[] = json_decode($response->getBody()->getContents(), true);
    }

    protected function testShouldCheckIfGuestCanCreateResource(): void
    {
        $response = $this->registerUserRequest(null, 'guest');

        $this->assertEquals(201, $response->getStatusCode());

        $this->createdUsers[] = json_decode($response->getBody()->getContents(), true);
    }

    protected function testShouldReturnConflictIfUserIsAlreadyRegistered(): void
    {
        $response = $this->client->post('users', [
            'json' => [
                'email' => $this->createdUsers[0]['email'],
                'name' => 'Test User',
                'password' => TestLoginUtil::DEFAULT_PASSWORD
            ]
        ]);

        $this->assertEquals(409, $response->getStatusCode());
    }

    protected function testShouldReturnPreviouslyCreatedResources(): void
    {
        foreach ($this->createdUsers as $user) {
            $response = $this->client->get("{$this->endpoint}/{$user['id']}");

            $this->assertEquals(200, $response->getStatusCode());

            $json = json_decode($response->getBody()->getContents(), true);

            $this->assertEquals($user, $json);
        }
    }

    protected function testShouldReturnCollectionOfAllCreatedResources(): void
    {
        $response = $this->client->get($this->endpoint);

        $this->assertEquals(200, $response->getStatusCode());

        $json = json_decode($response->getBody()->getContents(), true);

        $this->assertCount(count($this->createdUsers) + 3, $json['data']);
        $this->assertEquals(count($this->createdUsers) + 3, $json['pagination']['total']);
    }

    protected function testShouldCheckIfAdminCanReadResource(): void
    {
        $readableFields = [
            'id',
            'email',
            'name',
            'role',
            'avatar',
            'blocked',
        ];

        $token = $this->loginUtil->loginAsAdmin();

        $response = $this->client->get("{$this->endpoint}/{$this->createdUsers[0]['id']}", [
            'headers' => [
                'Authorization' => "Bearer $token"
            ]
        ]);

        $this->assertReadableFieldsFromResponse($readableFields, $response);
    }

    protected function testShouldCheckIfModeratorCanReadResource(): void
    {
        $readableFields = [
            'id',
            'email',
            'name',
            'role',
            'avatar',
            'blocked',
        ];

        $token = $this->loginUtil->loginAsModerator();

        $response = $this->client->get("{$this->endpoint}/{$this->createdUsers[0]['id']}", [
            'headers' => [
                'Authorization' => "Bearer $token"
            ]
        ]);

        $this->assertReadableFieldsFromResponse($readableFields, $response);
    }

    protected function testShouldCheckIfEditorCanReadResource(): void
    {
        $readableFields = [
            'id',
            'email',
            'name',
            'role',
            'avatar',
            'blocked',
        ];

        $token = $this->loginUtil->loginAsEditor();

        $response = $this->client->get("{$this->endpoint}/{$this->createdUsers[0]['id']}", [
            'headers' => [
                'Authorization' => "Bearer $token"
            ]
        ]);

        $this->assertReadableFieldsFromResponse($readableFields, $response);
    }

    protected function testShouldCheckIfUserCanReadResource(): void
    {
        $readableFields = [
            'id',
            'email',
            'name',
            'role',
            'avatar',
            'blocked',
        ];

        $token = $this->loginUtil->loginWithEmailAndPassword(
            email: $this->createdUsers[0]['email'],
            password: TestLoginUtil::DEFAULT_PASSWORD
        );

        $response = $this->client->get("{$this->endpoint}/{$this->createdUsers[1]['id']}", [
            'headers' => [
                'Authorization' => "Bearer $token"
            ]
        ]);

        $this->assertReadableFieldsFromResponse($readableFields, $response);
    }

    protected function testShouldCheckIfGuestCanReadResource(): void
    {
        $readableFields = [
            'id',
            'email',
            'name',
            'role',
            'avatar',
            'blocked',
        ];

        $response = $this->client->get("{$this->endpoint}/{$this->createdUsers[0]['id']}");

        $this->assertReadableFieldsFromResponse($readableFields, $response);
    }

    protected function testShouldCheckEditableFieldsByAdmin(): void
    {
        $response = $this->updateUserRequest(
            0,
            $this->loginUtil->loginAsAdmin(),
            ['name' => 'Updated by ADMIN']
        );
        $this->assertEquals(200, $response->getStatusCode());

        $response = $this->updateUserRequest(
            0,
            $this->loginUtil->loginAsAdmin(),
            ['role' => 'USER']
        );
        $this->assertEquals(200, $response->getStatusCode());

        $response = $this->updateUserRequest(
            0,
            $this->loginUtil->loginAsAdmin(),
            ['avatar' => 'https://example.com/avatar.jpg']
        );
        $this->assertEquals(200, $response->getStatusCode());

        $response = $this->updateUserRequest(
            0,
            $this->loginUtil->loginAsAdmin(),
            ['blocked' => true]
        );
        $this->assertEquals(200, $response->getStatusCode());
    }

    protected function testShouldCheckNotEditableFieldsByAdmin(): void
    {
        $response = $this->updateUserRequest(
            0,
            $this->loginUtil->loginAsAdmin(),
            ['email' => 'updated_by_admin@email.com']
        );
        $this->assertEquals(422, $response->getStatusCode());

        $response = $this->updateUserRequest(
            0,
            $this->loginUtil->loginAsAdmin(),
            ['password' => 'test123123']
        );
        $this->assertEquals(422, $response->getStatusCode());
    }

    protected function testShouldCheckEditableFieldsByModerator(): void
    {
        $response = $this->updateUserRequest(
            0,
            $this->loginUtil->loginAsModerator(),
            ['name' => 'Updated by ADMIN']
        );
        $this->assertEquals(200, $response->getStatusCode());

        $response = $this->updateUserRequest(
            0,
            $this->loginUtil->loginAsModerator(),
            ['avatar' => 'https://example.com/avatar.jpg']
        );
        $this->assertEquals(200, $response->getStatusCode());

        $response = $this->updateUserRequest(
            0,
            $this->loginUtil->loginAsModerator(),
            ['blocked' => true]
        );
        $this->assertEquals(200, $response->getStatusCode());
    }

    protected function testShouldCheckNotEditableFieldsByModerator(): void
    {
        $response = $this->updateUserRequest(
            0,
            $this->loginUtil->loginAsModerator(),
            ['email' => 'updated_by_moderator@email.com']
        );
        $this->assertEquals(422, $response->getStatusCode());

        $response = $this->updateUserRequest(
            0,
            $this->loginUtil->loginAsModerator(),
            ['role' => 'USER']
        );
        $this->assertEquals(422, $response->getStatusCode());

        $response = $this->updateUserRequest(
            0,
            $this->loginUtil->loginAsModerator(),
            ['password' => 'test123123']
        );
        $this->assertEquals(422, $response->getStatusCode());
    }

    protected function testShouldCheckEditableFieldsByEditor(): void
    {
        $this->assertTrue(true);
    }

    protected function testShouldCheckNotEditableFieldsByEditor(): void
    {
        $response = $this->updateUserRequest(
            0,
            $this->loginUtil->loginAsEditor(),
            ['email' => 'updated_by_editor@email.com']
        );
        $this->assertEquals(422, $response->getStatusCode());

        $response = $this->updateUserRequest(
            0,
            $this->loginUtil->loginAsEditor(),
            ['name' => 'Updated by EDITOR']
        );
        $this->assertEquals(422, $response->getStatusCode());

        $response = $this->updateUserRequest(
            0,
            $this->loginUtil->loginAsEditor(),
            ['avatar' => 'https://example.com/avatar.jpg']
        );
        $this->assertEquals(422, $response->getStatusCode());

        $response = $this->updateUserRequest(
            0,
            $this->loginUtil->loginAsEditor(),
            ['blocked' => true]
        );
        $this->assertEquals(422, $response->getStatusCode());

        $response = $this->updateUserRequest(
            0,
            $this->loginUtil->loginAsEditor(),
            ['role' => 'USER']
        );
        $this->assertEquals(422, $response->getStatusCode());

        $response = $this->updateUserRequest(
            0,
            $this->loginUtil->loginAsEditor(),
            ['password' => 'test123123']
        );
        $this->assertEquals(422, $response->getStatusCode());
    }

    protected function testShouldCheckEditableFieldsByUser(): void
    {
        $this->assertTrue(true);
    }

    protected function testShouldCheckNotEditableFieldsByUser(): void
    {
        $token = $this->loginUtil->loginWithEmailAndPassword(
            email: $this->createdUsers[1]['email'],
            password: TestLoginUtil::DEFAULT_PASSWORD
        );

        $response = $this->updateUserRequest(
            0,
            $token,
            ['email' => 'updated_by_user@email.com']
        );
        $this->assertEquals(422, $response->getStatusCode());
        $response = $this->updateUserRequest(
            0,
            $token,
            ['name' => 'Updated by EDITOR']
        );
        $this->assertEquals(422, $response->getStatusCode());

        $response = $this->updateUserRequest(
            0,
            $token,
            ['avatar' => 'https://example.com/avatar.jpg']
        );
        $this->assertEquals(422, $response->getStatusCode());

        $response = $this->updateUserRequest(
            0,
            $token,
            ['blocked' => true]
        );
        $this->assertEquals(422, $response->getStatusCode());

        $response = $this->updateUserRequest(
            0,
            $token,
            ['role' => 'USER']
        );
        $this->assertEquals(422, $response->getStatusCode());

        $response = $this->updateUserRequest(
            0,
            $token,
            ['password' => 'test123123']
        );
        $this->assertEquals(422, $response->getStatusCode());
    }

    protected function testShouldCheckEditableFieldsByGuest(): void
    {
        $this->assertTrue(true);
    }

    protected function testShouldCheckNotEditableFieldsByGuest(): void
    {
        $response = $this->updateUserRequest(
            0,
            null,
            ['email' => 'updated_by_guest@email.com']
        );
        $this->assertEquals(422, $response->getStatusCode());
        $response = $this->updateUserRequest(
            0,
            null,
            ['name' => 'Updated by GUEST']
        );
        $this->assertEquals(422, $response->getStatusCode());

        $response = $this->updateUserRequest(
            0,
            null,
            ['avatar' => 'https://example.com/avatar.jpg']
        );
        $this->assertEquals(422, $response->getStatusCode());

        $response = $this->updateUserRequest(
            0,
            null,
            ['blocked' => true]
        );
        $this->assertEquals(422, $response->getStatusCode());

        $response = $this->updateUserRequest(
            0,
            null,
            ['role' => 'USER']
        );
        $this->assertEquals(422, $response->getStatusCode());

        $response = $this->updateUserRequest(
            0,
            null,
            ['password' => 'test123123']
        );
        $this->assertEquals(422, $response->getStatusCode());
    }

    protected function testShouldCheckEditableFieldsByOwner(): void
    {
        $response = $this->updateUserRequest(
            0,
            $this->loginUtil->loginWithEmailAndPassword(
                email: $this->createdUsers[0]['email'],
                password: TestLoginUtil::DEFAULT_PASSWORD
            ),
            [
                'email' => $this->createdUsers[0]['email'],
                'name' => 'Updated by OWNER',
                'password' => TestLoginUtil::DEFAULT_PASSWORD,
                'avatar' => 'https://example.com/avatar.jpg',
            ]
        );
        $this->assertEquals(200, $response->getStatusCode());
    }

    protected function testShouldCheckNotEditableFieldsByOwner(): void
    {
        $token = $this->loginUtil->loginWithEmailAndPassword(
            email: $this->createdUsers[0]['email'],
            password: TestLoginUtil::DEFAULT_PASSWORD
        );

        $response = $this->updateUserRequest(
            0,
            $token,
            ['role' => 'USER']
        );
        $this->assertEquals(422, $response->getStatusCode());

        $response = $this->updateUserRequest(
            0,
            $token,
            ['blocked' => true]
        );
        $this->assertEquals(422, $response->getStatusCode());
    }

    protected function testShouldCheckIfUpdateEmailToExistingOneIsImpossible(): void
    {
        $token = $this->loginUtil->loginWithEmailAndPassword(
            email: $this->createdUsers[0]['email'],
            password: TestLoginUtil::DEFAULT_PASSWORD
        );

        $response = $this->updateUserRequest(
            0,
            $token,
            ['email' => $this->createdUsers[1]['email']]
        );

        $this->assertEquals(409, $response->getStatusCode());
    }

    protected function testShouldCheckIfAdminCanDeleteResource(): void
    {
        $user = array_pop($this->createdUsers);

        $response = $this->client->delete("{$this->endpoint}/{$user['id']}", [
            'headers' => [
                'Authorization' => "Bearer {$this->loginUtil->loginAsAdmin()}"
            ]
        ]);

        $this->assertEquals(204, $response->getStatusCode());
    }

    protected function testShouldCheckIfModeratorCanDeleteResource(): void
    {
        $user = array_pop($this->createdUsers);

        $response = $this->client->delete("{$this->endpoint}/{$user['id']}", [
            'headers' => [
                'Authorization' => "Bearer {$this->loginUtil->loginAsModerator()}"
            ]
        ]);

        $this->assertEquals(204, $response->getStatusCode());
    }

    protected function testShouldCheckIfEditorCanDeleteResource(): void
    {
        $user = $this->createdUsers[0];

        $response = $this->client->delete("{$this->endpoint}/{$user['id']}", [
            'headers' => [
                'Authorization' => "Bearer {$this->loginUtil->loginAsEditor()}"
            ]
        ]);

        $this->assertEquals(403, $response->getStatusCode());
    }

    protected function testShouldCheckIfUserCanDeleteResource(): void
    {
        $token = $this->loginUtil->loginWithEmailAndPassword(
            email: $this->createdUsers[1]['email'],
            password: TestLoginUtil::DEFAULT_PASSWORD
        );

        $user = $this->createdUsers[0];

        $response = $this->client->delete("{$this->endpoint}/{$user['id']}", [
            'headers' => [
                'Authorization' => "Bearer $token"
            ]
        ]);

        $this->assertEquals(403, $response->getStatusCode());
    }

    protected function testShouldCheckIfGuestCanDeleteResource(): void
    {
        $user = $this->createdUsers[0];

        $response = $this->client->delete("{$this->endpoint}/{$user['id']}");

        $this->assertEquals(401, $response->getStatusCode());
    }

    protected function testShouldCheckIfOwnerCanDeleteResource(): void
    {
        $user = array_pop($this->createdUsers);

        $token = $this->loginUtil->loginWithEmailAndPassword(
            email: $user['email'],
            password: TestLoginUtil::DEFAULT_PASSWORD
        );

        $response = $this->client->delete("{$this->endpoint}/{$user['id']}", [
            'headers' => [
                'Authorization' => "Bearer $token"
            ]
        ]);

        $this->assertEquals(204, $response->getStatusCode());
    }

    private function registerUserRequest(?string $token, string $source): ResponseInterface
    {
        $headers = [
            'Content-Type' => 'application/json'
        ];
        if ($token) {
            $headers['Authorization'] = "Bearer $token";
        }

        return $this->client->post('users', [
            'headers' => $headers,
            'json' => [
                'email' => "created_by_$source@mail.com",
                'name' => "Created By $source",
                'password' => TestLoginUtil::DEFAULT_PASSWORD
            ]
        ]);
    }

    private function updateUserRequest(int $userIndex, ?string $token, array $data): ResponseInterface
    {
        $headers = [];
        if ($token) {
            $headers['Authorization'] = "Bearer $token";
        }

        return $this->client->put(
            "{$this->endpoint}/{$this->createdUsers[$userIndex]['id']}",
            ['headers' => $headers, 'json' => $data]
        );
    }
}
