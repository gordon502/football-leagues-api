<?php

namespace App\Modules\GameEvent\Model\MongoDB;

use App\Common\Model\MongoDB\ModelUuidTrait;
use App\Common\Timestamp\TimestampableTrait;
use App\Modules\Game\Model\GameInterface;
use App\Modules\Game\Model\MongoDB\Game;
use App\Modules\GameEvent\Model\GameEventInterface;
use Doctrine\ODM\MongoDB\Mapping\Annotations\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations\Field;
use Doctrine\ODM\MongoDB\Mapping\Annotations\HasLifecycleCallbacks;
use Doctrine\ODM\MongoDB\Mapping\Annotations\ReferenceOne;

#[Document(collection: 'game_event')]
#[HasLifecycleCallbacks]
class GameEvent implements GameEventInterface
{
    use ModelUuidTrait;
    use TimestampableTrait;

    #[Field(type: 'int')]
    private int $minute;

    #[Field(type: 'string')]
    private string $partOrHalf;

    #[Field(type: 'string')]
    private string $teamRelated;

    #[Field(type: 'int')]
    private int $order;

    #[Field(type: 'string')]
    private string $eventType;

    #[ReferenceOne(targetDocument: Game::class)]
    private GameInterface $game;

    public function getMinute(): int
    {
        return $this->minute;
    }

    public function getPartOrHalf(): string
    {
        return $this->partOrHalf;
    }

    public function getTeamRelated(): string
    {
        return $this->teamRelated;
    }

    public function getOrder(): int
    {
        return $this->order;
    }

    public function getEventType(): string
    {
        return $this->eventType;
    }

    public function getGame(): GameInterface
    {
        return $this->game;
    }

    public function setMinute(int $minute): static
    {
        $this->minute = $minute;

        return $this;
    }

    public function setPartOrHalf(string $partOrHalf): static
    {
        $this->partOrHalf = $partOrHalf;

        return $this;
    }

    public function setTeamRelated(string $teamRelated): static
    {
        $this->teamRelated = $teamRelated;

        return $this;
    }

    public function setOrder(int $order): static
    {
        $this->order = $order;

        return $this;
    }

    public function setEventType(string $eventType): static
    {
        $this->eventType = $eventType;

        return $this;
    }

    public function setGame(GameInterface $game): static
    {
        $this->game = $game;

        return $this;
    }
}
