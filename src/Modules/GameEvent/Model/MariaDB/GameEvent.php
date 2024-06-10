<?php

namespace App\Modules\GameEvent\Model\MariaDB;

use App\Common\Model\MariaDB\ModelUuidTrait;
use App\Common\Timestamp\TimestampableTrait;
use App\Modules\Game\Model\GameInterface;
use App\Modules\Game\Model\MariaDB\Game;
use App\Modules\GameEvent\Model\GameEventInterface;
use App\Modules\GameEvent\Repository\MariaDB\GameEventRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: GameEventRepository::class)]
#[Table(name: 'game_event')]
#[HasLifecycleCallbacks]
class GameEvent implements GameEventInterface
{
    use ModelUuidTrait;
    use TimestampableTrait;

    #[Column(type: 'integer')]
    private int $minute;

    #[Column(type: 'string')]
    private string $partOrHalf;

    #[Column(type: 'string')]
    private string $teamRelated;

    #[Column(name: '`order`', type: 'integer')]
    private int $order;

    #[Column(type: 'string')]
    private string $eventType;

    #[ManyToOne(targetEntity: Game::class)]
    #[JoinColumn(name: 'game_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
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
