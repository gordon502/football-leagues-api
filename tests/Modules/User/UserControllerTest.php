<?php

namespace Tests\Modules\User;

use App\Modules\User\Role\UserRole;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Tests\Modules\AbstractControllerTest;
use Tests\Util\TestDatabaseTypeEnum;
use Tests\Util\TestLoginUtil;

class UserControllerTest extends AbstractControllerTest
{
    public const DEFAULT_ENDPOINT = 'users';

    public function __construct(Client $client, TestDatabaseTypeEnum $databaseType)
    {
        $this->endpoint = self::DEFAULT_ENDPOINT;

        parent::__construct($client, $databaseType);
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
    }

    protected function testShouldCheckIfModeratorCanCreateResource(): void
    {
        $token = $this->loginUtil->loginAsModerator();

        $response = $this->registerUserRequest($token, 'moderator');

        $this->assertEquals(201, $response->getStatusCode());
    }

    protected function testShouldCheckIfEditorCanCreateResource(): void
    {
        $token = $this->loginUtil->loginAsEditor();

        $response = $this->registerUserRequest($token, 'editor');

        $this->assertEquals(201, $response->getStatusCode());
    }

    protected function testShouldCheckIfUserCanCreateResource(): void
    {
        $token = $this->loginUtil->loginWithEmailAndPassword($this->loginUtil->getFirstNonBlockedStandardUser());

        $response = $this->registerUserRequest($token, 'user');

        $this->assertEquals(201, $response->getStatusCode());
    }

    protected function testShouldCheckIfGuestCanCreateResource(): void
    {
        $response = $this->registerUserRequest(null, 'guest');

        $this->assertEquals(201, $response->getStatusCode());
    }

    protected function testShouldReturnConflictIfUserIsAlreadyRegistered(): void
    {
        $response = $this->client->post('users', [
            'json' => [
                'email' => $this->availableResources->getUsers()[0]['email'],
                'name' => 'Test User',
                'password' => TestLoginUtil::DEFAULT_PASSWORD
            ]
        ]);

        $this->assertEquals(409, $response->getStatusCode());
    }

    protected function testShouldReturnPreviouslyCreatedResources(): void
    {
        foreach ($this->availableResources->getUsers() as $user) {
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

        $this->assertCount(count($this->availableResources->getUsers()), $json['data']);
        $this->assertEquals(count($this->availableResources->getUsers()), $json['pagination']['total']);
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

        $id = $this->availableResources->getUsers()[0]['id'];

        $response = $this->client->get("{$this->endpoint}/{$id}", [
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

        $id = $this->availableResources->getUsers()[0]['id'];

        $response = $this->client->get("{$this->endpoint}/{$id}", [
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

        $id = $this->availableResources->getUsers()[0]['id'];

        $response = $this->client->get("{$this->endpoint}/{$id}", [
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

        $token = $this->loginUtil->loginWithEmailAndPassword($this->loginUtil->getFirstNonBlockedStandardUser());

        $id = $this->availableResources->getUsers()[0]['id'];

        $response = $this->client->get("{$this->endpoint}/{$id}", [
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

        $id = $this->availableResources->getUsers()[0]['id'];

        $response = $this->client->get("{$this->endpoint}/{$id}");

        $this->assertReadableFieldsFromResponse($readableFields, $response);
    }

    protected function testShouldCheckEditableFieldsByAdmin(): void
    {
        $response = $this->updateUserRequest(
            $this->loginUtil->loginAsAdmin(),
            ['name' => 'Updated by ADMIN']
        );
        $this->assertEquals(200, $response->getStatusCode());

        $response = $this->updateUserRequest(
            $this->loginUtil->loginAsAdmin(),
            ['role' => 'USER']
        );
        $this->assertEquals(200, $response->getStatusCode());

        $response = $this->updateUserRequest(
            $this->loginUtil->loginAsAdmin(),
            ['avatar' => 'https://example.com/avatar.jpg']
        );
        $this->assertEquals(200, $response->getStatusCode());

        $response = $this->updateUserRequest(
            $this->loginUtil->loginAsAdmin(),
            ['blocked' => false]
        );
        $this->assertEquals(200, $response->getStatusCode());
    }

    protected function testShouldCheckNotEditableFieldsByAdmin(): void
    {
        $response = $this->updateUserRequest(
            $this->loginUtil->loginAsAdmin(),
            ['email' => 'updated_by_admin@email.com']
        );
        $this->assertEquals(422, $response->getStatusCode());

        $response = $this->updateUserRequest(
            $this->loginUtil->loginAsAdmin(),
            ['password' => 'test123123']
        );
        $this->assertEquals(422, $response->getStatusCode());
    }

    protected function testShouldCheckEditableFieldsByModerator(): void
    {
        $response = $this->updateUserRequest(
            $this->loginUtil->loginAsModerator(),
            ['name' => 'Updated by ADMIN']
        );
        $this->assertEquals(200, $response->getStatusCode());

        $response = $this->updateUserRequest(
            $this->loginUtil->loginAsModerator(),
            ['avatar' => 'https://example.com/avatar.jpg']
        );
        $this->assertEquals(200, $response->getStatusCode());

        $response = $this->updateUserRequest(
            $this->loginUtil->loginAsModerator(),
            ['blocked' => false]
        );
        $this->assertEquals(200, $response->getStatusCode());
    }

    protected function testShouldCheckNotEditableFieldsByModerator(): void
    {
        $response = $this->updateUserRequest(
            $this->loginUtil->loginAsModerator(),
            ['email' => 'updated_by_moderator@email.com']
        );
        $this->assertEquals(422, $response->getStatusCode());

        $response = $this->updateUserRequest(
            $this->loginUtil->loginAsModerator(),
            ['role' => 'USER']
        );
        $this->assertEquals(422, $response->getStatusCode());

        $response = $this->updateUserRequest(
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
            $this->loginUtil->loginAsEditor(),
            ['email' => 'updated_by_editor@email.com']
        );
        $this->assertEquals(422, $response->getStatusCode());

        $response = $this->updateUserRequest(
            $this->loginUtil->loginAsEditor(),
            ['name' => 'Updated by EDITOR']
        );
        $this->assertEquals(422, $response->getStatusCode());

        $response = $this->updateUserRequest(
            $this->loginUtil->loginAsEditor(),
            ['avatar' => 'https://example.com/avatar.jpg']
        );
        $this->assertEquals(422, $response->getStatusCode());

        $response = $this->updateUserRequest(
            $this->loginUtil->loginAsEditor(),
            ['blocked' => false]
        );
        $this->assertEquals(422, $response->getStatusCode());

        $response = $this->updateUserRequest(
            $this->loginUtil->loginAsEditor(),
            ['role' => 'USER']
        );
        $this->assertEquals(422, $response->getStatusCode());

        $response = $this->updateUserRequest(
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
        $token = $this->loginUtil->loginWithEmailAndPassword($this->loginUtil->getFirstNonBlockedStandardUser());

        $response = $this->updateUserRequest(
            $token,
            ['email' => 'updated_by_user@email.com'],
            UserRole::EDITOR,
        );
        $this->assertEquals(422, $response->getStatusCode());
        $response = $this->updateUserRequest(
            $token,
            ['name' => 'Updated by EDITOR'],
            UserRole::EDITOR,
        );
        $this->assertEquals(422, $response->getStatusCode());

        $response = $this->updateUserRequest(
            $token,
            ['avatar' => 'https://example.com/avatar.jpg'],
            UserRole::EDITOR,
        );
        $this->assertEquals(422, $response->getStatusCode());

        $response = $this->updateUserRequest(
            $token,
            ['blocked' => false],
            UserRole::EDITOR,
        );
        $this->assertEquals(422, $response->getStatusCode());

        $response = $this->updateUserRequest(
            $token,
            ['role' => 'USER'],
            UserRole::EDITOR,
        );
        $this->assertEquals(422, $response->getStatusCode());

        $response = $this->updateUserRequest(
            $token,
            ['password' => 'test123123'],
            UserRole::EDITOR,
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
            null,
            ['email' => 'updated_by_guest@email.com']
        );
        $this->assertEquals(422, $response->getStatusCode());
        $response = $this->updateUserRequest(
            null,
            ['name' => 'Updated by GUEST']
        );
        $this->assertEquals(422, $response->getStatusCode());

        $response = $this->updateUserRequest(
            null,
            ['avatar' => 'https://example.com/avatar.jpg']
        );
        $this->assertEquals(422, $response->getStatusCode());

        $response = $this->updateUserRequest(
            null,
            ['blocked' => false]
        );
        $this->assertEquals(422, $response->getStatusCode());

        $response = $this->updateUserRequest(
            null,
            ['role' => 'USER']
        );
        $this->assertEquals(422, $response->getStatusCode());

        $response = $this->updateUserRequest(
            null,
            ['password' => 'test123123']
        );
        $this->assertEquals(422, $response->getStatusCode());
    }

    protected function testShouldCheckEditableFieldsByOwner(): void
    {
        $response = $this->updateUserRequest(
            $this->loginUtil->loginWithEmailAndPassword($this->loginUtil->getFirstNonBlockedStandardUser()),
            [
                'email' => 'not-used-mail-by-user@mail.com',
                'name' => 'Updated by OWNER',
                'password' => TestLoginUtil::DEFAULT_PASSWORD,
                'avatar' => 'https://example.com/avatar.jpg',
            ]
        );
        $this->assertEquals(200, $response->getStatusCode());
    }

    protected function testShouldCheckNotEditableFieldsByOwner(): void
    {
        $token = $this->loginUtil->loginWithEmailAndPassword($this->loginUtil->getFirstNonBlockedStandardUser());

        $response = $this->updateUserRequest(
            $token,
            ['role' => 'USER']
        );
        $this->assertEquals(422, $response->getStatusCode());

        $response = $this->updateUserRequest(
            $token,
            ['blocked' => false]
        );
        $this->assertEquals(422, $response->getStatusCode());
    }

    protected function testShouldCheckIfUpdateEmailToExistingOneIsImpossible(): void
    {
        $token = $this->loginUtil->loginWithEmailAndPassword($this->loginUtil->getFirstNonBlockedStandardUser());

        $response = $this->updateUserRequest(
            $token,
            [
                'email' => array_values(
                    array_filter($this->availableResources->getUsers(), fn($user) => $user['role'] === UserRole::ADMIN)
                )[0]['email']
            ]
        );

        $this->assertEquals(409, $response->getStatusCode());
    }

    protected function testShouldCheckIfAdminCanDeleteResource(): void
    {
        $id = $this->getUserAccountId();

        $response = $this->client->delete("{$this->endpoint}/{$id}", [
            'headers' => [
                'Authorization' => "Bearer {$this->loginUtil->loginAsAdmin()}"
            ]
        ]);

        $this->assertEquals(204, $response->getStatusCode());
    }

    protected function testShouldCheckIfModeratorCanDeleteResource(): void
    {
        $id = $this->getUserAccountId();

        $response = $this->client->delete("{$this->endpoint}/{$id}", [
            'headers' => [
                'Authorization' => "Bearer {$this->loginUtil->loginAsModerator()}"
            ]
        ]);

        $this->assertEquals(204, $response->getStatusCode());
    }

    protected function testShouldCheckIfEditorCanDeleteResource(): void
    {
        $user = array_values(
            array_filter($this->availableResources->getUsers(), fn($user) => $user['role'] === UserRole::ADMIN)
        )[0];

        $response = $this->client->delete("{$this->endpoint}/{$user['id']}", [
            'headers' => [
                'Authorization' => "Bearer {$this->loginUtil->loginAsEditor()}"
            ]
        ]);

        $this->assertEquals(403, $response->getStatusCode());
    }

    protected function testShouldCheckIfUserCanDeleteResource(): void
    {
        $token = $this->loginUtil->loginWithEmailAndPassword($this->loginUtil->getFirstNonBlockedStandardUser());

        $user = array_values(
            array_filter($this->availableResources->getUsers(), fn($user) => $user['role'] === UserRole::ADMIN)
        )[0];

        $response = $this->client->delete("{$this->endpoint}/{$user['id']}", [
            'headers' => [
                'Authorization' => "Bearer $token"
            ]
        ]);

        $this->assertEquals(403, $response->getStatusCode());
    }

    protected function testShouldCheckIfGuestCanDeleteResource(): void
    {
        $user = $this->availableResources->getUsers()[0];

        $response = $this->client->delete("{$this->endpoint}/{$user['id']}");

        $this->assertEquals(401, $response->getStatusCode());
    }

    protected function testShouldCheckIfOwnerCanDeleteResource(): void
    {
        $user = $this->loginUtil->getFirstNonBlockedStandardUser();

        $token = $this->loginUtil->loginWithEmailAndPassword($this->loginUtil->getFirstNonBlockedStandardUser());

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

    private function updateUserRequest(
        ?string $token,
        array $data,
        string $updatedUserRole = UserRole::USER
    ): ResponseInterface {
        $headers = [];
        if ($token) {
            $headers['Authorization'] = "Bearer $token";
        }

        $id = array_values(
            array_filter($this->availableResources->getUsers(), fn($user) => $user['role'] === $updatedUserRole)
        )[0]['id'];

        return $this->client->put(
            "{$this->endpoint}/{$id}",
            ['headers' => $headers, 'json' => $data]
        );
    }

    private function getUserAccountId(): string
    {
        return array_values(
            array_filter(
                $this->availableResources->getUsers(),
                fn($user) => $user['role'] === UserRole::USER
            )
        )[0]['id'];
    }
}
