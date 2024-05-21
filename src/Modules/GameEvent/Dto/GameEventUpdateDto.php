<?php

namespace App\Modules\GameEvent\Dto;

use App\Common\Dto\DtoPropertyRelatedToEntity;
use App\Common\Dto\NotIncludedInBody;
use App\Common\Dto\NotIncludedInBodyTrait;
use App\Common\OAAttributes\OARoleBasedProperty;
use App\Common\Serialization\RoleSerializationGroup;
use App\Modules\Game\Model\GameInterface;
use App\Modules\GameEvent\Enum\GameEventEventTypeEnum;
use App\Modules\GameEvent\Enum\GameEventPartOrHalfEnum;
use App\Modules\GameEvent\Enum\GameEventTeamRelatedEnum;
use App\Modules\GameEvent\Model\GameEventUpdatableInterface;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

readonly class GameEventUpdateDto implements GameEventUpdatableInterface
{
    use NotIncludedInBodyTrait;

    public function __construct(
        private int|null|NotIncludedInBody $minute = new NotIncludedInBody(),
        private string|null|NotIncludedInBody $partOrHalf = new NotIncludedInBody(),
        private string|null|NotIncludedInBody $teamRelated = new NotIncludedInBody(),
        private int|null|NotIncludedInBody $order = new NotIncludedInBody(),
        private string|null|NotIncludedInBody $eventType = new NotIncludedInBody(),
        private string|null|NotIncludedInBody $gameId = new NotIncludedInBody()
    ) {
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
        return $this->toValueOrNull($this->minute);
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
        return $this->toValueOrNull($this->partOrHalf);
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
        return $this->toValueOrNull($this->teamRelated);
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
        return $this->toValueOrNull($this->order);
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
        return $this->toValueOrNull($this->eventType);
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
    #[DtoPropertyRelatedToEntity(GameInterface::class)]
    #[Assert\NotBlank]
    #[Assert\Uuid]
    public function getGameId(): string|null
    {
        return $this->toValueOrNull($this->gameId);
    }
}
