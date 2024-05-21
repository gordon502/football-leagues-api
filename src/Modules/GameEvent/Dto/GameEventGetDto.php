<?php

namespace App\Modules\GameEvent\Dto;

use App\Common\OAAttributes\OARoleBasedProperty;
use App\Common\Serialization\RoleSerializationGroup;
use App\Modules\GameEvent\Model\GameEventGetInterface;
use App\Modules\Round\Model\RoundGetInterface;
use Symfony\Component\Serializer\Attribute\Groups;

readonly class GameEventGetDto
{
    public function __construct(
        private GameEventGetInterface $gameEvent
    ) {
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Game event id.', RoleSerializationGroup::ALL)]
    public function getId(): string
    {
        return $this->gameEvent->getId();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Game event minute.', RoleSerializationGroup::ALL)]
    public function getMinute(): int
    {
        return $this->gameEvent->getMinute();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Game event part or half.', RoleSerializationGroup::ALL)]
    public function getPartOrHalf(): string
    {
        return $this->gameEvent->getPartOrHalf();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Game event team related.', RoleSerializationGroup::ALL)]
    public function getTeamRelated(): string
    {
        return $this->gameEvent->getTeamRelated();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Game event order.', RoleSerializationGroup::ALL)]
    public function getOrder(): int
    {
        return $this->gameEvent->getOrder();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Game event event type.', RoleSerializationGroup::ALL)]
    public function getEventType(): string
    {
        return $this->gameEvent->getEventType();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Game event game id.', RoleSerializationGroup::ALL)]
    public function getGameId(): string
    {
        return $this->gameEvent->getGame()->getId();
    }
}
