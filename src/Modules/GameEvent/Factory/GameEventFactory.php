<?php

namespace App\Modules\GameEvent\Factory;

use App\Common\Repository\Exception\RelatedEntityNotFoundException;
use App\Modules\Game\Repository\GameRepositoryInterface;
use App\Modules\GameEvent\Model\GameEventCreatableInterface;
use App\Modules\GameEvent\Model\GameEventInterface;
use InvalidArgumentException;
use ReflectionClass;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

readonly class GameEventFactory implements GameEventFactoryInterface
{
    public function __construct(
        #[Autowire(service: 'game_repository')]
        private GameRepositoryInterface $gameRepository,
    ) {
    }

    public function create(
        GameEventCreatableInterface $gameEventCreatable,
        string $modelClass
    ): GameEventInterface {
        $reflection = new ReflectionClass($modelClass);
        if (!$reflection->implementsInterface(GameEventInterface::class)) {
            throw new InvalidArgumentException(
                'Model class must implement ' . GameEventInterface::class
            );
        }

        /** @var GameEventInterface $model */
        $model = new $modelClass();

        $model->setMinute($gameEventCreatable->getMinute());
        $model->setPartOrHalf($gameEventCreatable->getPartOrHalf());
        $model->setTeamRelated($gameEventCreatable->getTeamRelated());
        $model->setOrder($gameEventCreatable->getOrder());
        $model->setEventType($gameEventCreatable->getEventType());

        $game = $this->gameRepository->find($gameEventCreatable->getGameId());
        if ($game === null) {
            throw new RelatedEntityNotFoundException(
                'Game not found with id ' . $gameEventCreatable->getGameId()
            );
        }

        $model->setGame($game);

        return $model;
    }
}
