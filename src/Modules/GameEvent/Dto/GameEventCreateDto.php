<?php

namespace App\Modules\GameEvent\Dto;

use App\Common\OAAttributes\OARoleBasedProperty;
use App\Common\Serialization\RoleSerializationGroup;
use App\Modules\GameEvent\Enum\GameEventEventTypeEnum;
use App\Modules\GameEvent\Enum\GameEventPartOrHalfEnum;
use App\Modules\GameEvent\Enum\GameEventTeamRelatedEnum;
use App\Modules\GameEvent\Model\GameEventCreatableInterface;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class GameEventCreateDto implements GameEventCreatableInterface
{
    private int|null $minute;
    private string|null $partOrHalf;
    private string|null $teamRelated;
    private int|null $order;
    private string|null $eventType;
    private string|null $gameId;

    public function __construct(
        int|null $minute,
        string|null $partOrHalf,
        string|null $teamRelated,
        int|null $order,
        string|null $eventType,
        string|null $gameId
    ) {
        $this->minute = $minute;
        $this->partOrHalf = $partOrHalf;
        $this->teamRelated = $teamRelated;
        $this->order = $order;
        $this->eventType = $eventType;
        $this->gameId = $gameId;
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Game event minute.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\NotBlank]
    #[Assert\Type('int')]
    #[Assert\PositiveOrZero]
    public function getMinute(): int|null
    {
        return $this->minute;
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Game event part or half.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\NotBlank]
    #[Assert\Type('string')]
    #[Assert\Choice([
        GameEventPartOrHalfEnum::FIRST_HALF->value,
        GameEventPartOrHalfEnum::SECOND_HALF->value
    ])]
    public function getPartOrHalf(): string|null
    {
        return $this->partOrHalf;
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Game event team related.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\NotBlank]
    #[Assert\Type('string')]
    #[Assert\Choice([
        GameEventTeamRelatedEnum::TEAM_1->value,
        GameEventTeamRelatedEnum::TEAM_2->value
    ])]
    public function getTeamRelated(): string|null
    {
        return $this->teamRelated;
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Game event order.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\NotBlank]
    #[Assert\Type('int')]
    #[Assert\PositiveOrZero]
    public function getOrder(): int|null
    {
        return $this->order;
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Game event type.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\NotBlank]
    #[Assert\Type('string')]
    #[Assert\Choice([
        GameEventEventTypeEnum::GOAL->value,
        GameEventEventTypeEnum::PENALTY->value,
        GameEventEventTypeEnum::RED_CARD->value,
        GameEventEventTypeEnum::YELLOW_CARD->value
    ])]
    public function getEventType(): string|null
    {
        return $this->eventType;
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Game event game id.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\NotBlank]
    #[Assert\Uuid]
    public function getGameId(): string|null
    {
        return $this->gameId;
    }
}
