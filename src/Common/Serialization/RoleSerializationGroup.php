<?php

namespace App\Common\Serialization;

final class RoleSerializationGroup
{
    public const OWNER = 'OWNER';
    public const ADMIN = 'ADMIN';
    public const MODERATOR = 'MODERATOR';
    public const EDITOR = 'EDITOR';
    public const USER = 'USER';
    public const GUEST = 'GUEST';

    public const ALL = [
        self::OWNER,
        self::ADMIN,
        self::MODERATOR,
        self::EDITOR,
        self::USER,
        self::GUEST,
    ];

    private function __construct()
    {
    }
}
