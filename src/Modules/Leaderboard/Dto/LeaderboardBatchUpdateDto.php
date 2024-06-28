<?php

namespace App\Modules\Leaderboard\Dto;

use App\Common\OAAttributes\OARoleBasedProperty;
use App\Common\Serialization\RoleSerializationGroup;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use OpenApi\Attributes\Items;

class LeaderboardBatchUpdateDto
{
    private array $leaderboards;

    public function __construct(
        array $leaderboards,
    ) {
        $this->leaderboards = $leaderboards;
    }

    #[OARoleBasedProperty(
        'Leaderboards.',
        [
            RoleSerializationGroup::ADMIN,
            RoleSerializationGroup::MODERATOR,
            RoleSerializationGroup::EDITOR,
        ],
        type: 'array',
        items: new Items(ref: new Model(type: LeaderboardBatchUpdateSingleDto::class))
    )]
    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR,
    ])]
    #[Assert\NotNull]
    #[Assert\Type(['array'])]
    #[Assert\Count(min: 1, max: 50)]
    public function getLeaderboards(): array|null
    {
        return $this->leaderboards;
    }
}
