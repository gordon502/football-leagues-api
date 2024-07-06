<?php

namespace Tests\Util;

use App\Modules\User\Role\UserRole;
use GuzzleHttp\Client;
use Tests\Util\TestAvailableResources\TestAvailableResources;
use Tests\Util\TestAvailableResources\TestAvailableResourcesInterface;

readonly class TestLoginUtil
{
    public const DEFAULT_PASSWORD = 'password123!';

    public const DEFAULT_ADMIN_LIKE_PASSWORD = 'admin123!';

    protected Client $client;

    protected TestAvailableResourcesInterface $availableResources;

    public function __construct(Client $client, TestAvailableResourcesInterface $availableResources)
    {
        $this->client = $client;
        $this->availableResources = $availableResources;
    }

    public function loginWithEmailAndPassword(array $user): string
    {
        $response = $this->client->post('login', [
            'json' => [
                'username' => $user['email'],
                'password' => self::DEFAULT_PASSWORD,
            ]
        ]);

        $json = json_decode($response->getBody()->getContents(), true);

        return $json['token'];
    }

    public function getFirstNonBlockedStandardUser(): array
    {
        return array_values(
            array_filter(
                $this->availableResources->getUsers(),
                fn($user) => $user['role'] === UserRole::USER && $user['blocked'] === false
            )
        )[0];
    }

    public function loginAsAdmin(): string
    {
        $response = $this->client->post('login', [
            'json' => [
                'username' => 'admin@admin.com',
                'password' => self::DEFAULT_ADMIN_LIKE_PASSWORD,
            ]
        ]);

        $json = json_decode($response->getBody()->getContents(), true);

        return $json['token'];
    }

    public function loginAsModerator(): string
    {
        $response = $this->client->post('login', [
            'json' => [
                'username' => 'moderator@moderator.com',
                'password' => self::DEFAULT_ADMIN_LIKE_PASSWORD,
            ]
        ]);

        $json = json_decode($response->getBody()->getContents(), true);

        return $json['token'];
    }

    public function loginAsEditor(): string
    {
        $response = $this->client->post('login', [
            'json' => [
                'username' => 'editor@editor.com',
                'password' => self::DEFAULT_ADMIN_LIKE_PASSWORD,
            ]
        ]);

        $json = json_decode($response->getBody()->getContents(), true);

        return $json['token'];
    }
}
