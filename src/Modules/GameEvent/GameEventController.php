<?php

namespace App\Modules\GameEvent;

use App\Common\HttpQuery\HttpQueryHandlerInterface;
use App\Common\OAAttributes\OAFilterQueryParameter;
use App\Common\OAAttributes\OALimitQueryParameter;
use App\Common\OAAttributes\OAPageQueryParameter;
use App\Common\OAAttributes\OASortQueryParameter;
use App\Common\Pagination\PaginatedResponseFactoryInterface;
use App\Common\Response\HttpCode;
use App\Common\Response\ResourceNotFoundException;
use App\Common\Response\SingleObjectResponseFactoryInterface;
use App\Common\Serialization\RoleBasedSerializerInterface;
use App\Common\Validator\DtoValidatorInterface;
use App\Modules\GameEvent\Dto\GameEventCreateDto;
use App\Modules\GameEvent\Dto\GameEventGetDto;
use App\Modules\GameEvent\Dto\GameEventUpdateDto;
use App\Modules\GameEvent\Model\GameEventGetInterface;
use App\Modules\GameEvent\Repository\GameEventRepositoryInterface;
use App\Modules\GameEvent\Voter\GameEventVoter;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class GameEventController extends AbstractController
{
    public function __construct(
        #[Autowire(service: 'game_event_repository')]
        private readonly GameEventRepositoryInterface $gameEventRepository,
        private readonly RoleBasedSerializerInterface $serializer,
        private readonly DtoValidatorInterface $dtoValidator,
        private readonly HttpQueryHandlerInterface $httpQueryHandler,
        private readonly SingleObjectResponseFactoryInterface $singleObjectResponseFactory,
        private readonly PaginatedResponseFactoryInterface $paginatedResponseFactory,
    ) {
    }

    #[Route('/api/game-events', name: 'api.game_events.create', methods: ['POST'])]
    #[OA\Tag(name: 'Game Events')]
    #[OA\RequestBody(
        required: true,
        content: new Model(type: GameEventCreateDto::class)
    )]
    #[OA\Response(
        response: HttpCode::CREATED,
        description: 'Returns instance of the created game event.',
        content: new Model(type: GameEventGetDto::class)
    )]
    #[OA\Response(
        response: HttpCode::FORBIDDEN,
        description: 'Access denied.'
    )]
    #[OA\Response(
        response: HttpCode::UNPROCESSABLE_ENTITY,
        description: 'Invalid input.'
    )]
    public function create(Request $request): JsonResponse
    {
        $this->denyAccessUnlessGranted(GameEventVoter::CREATE);

        /** @var GameEventCreateDto $dto */
        $dto = $this->serializer->denormalize(
            $request->getPayload()->all(),
            GameEventCreateDto::class
        );
        $this->dtoValidator->validate($dto);

        $gameEvent = $this->gameEventRepository->create($dto);

        return $this->json(
            $this->singleObjectResponseFactory->fromObject(
                $gameEvent,
                GameEventGetDto::class
            ),
            HttpCode::CREATED
        );
    }

    #[Route('/api/game-events/{id}', name: 'api.game_events.get_by_id', methods: ['GET'])]
    #[Security(name: null)]
    #[OA\Tag(name: 'Game Events')]
    #[OA\Parameter(
        name: 'id',
        description: 'The id of the game event to retrieve.',
        in: 'path',
        required: true
    )]
    #[OA\Response(
        response: 200,
        description: 'Returns the game event with the given id.',
        content: new Model(type: GameEventGetDto::class)
    )]
    #[OA\Response(
        response: 404,
        description: 'Game Event not found.',
    )]
    public function getById(string $id): JsonResponse
    {
        $gameEvent = $this->gameEventRepository->findById($id);
        if ($gameEvent === null) {
            throw new ResourceNotFoundException();
        }

        return $this->json($this->singleObjectResponseFactory->fromObject(
            $gameEvent,
            GameEventGetDto::class
        ));
    }

    #[Route('/api/game-events', name: 'api.game_events.collection', methods: ['GET'])]
    #[Security(name: null)]
    #[OA\Tag(name: 'Game Events')]
    #[OA\Response(
        response: 200,
        description: 'Returns the game events that match the filter.',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: GameEventGetDto::class))
        )
    )]
    #[OAFilterQueryParameter]
    #[OASortQueryParameter]
    #[OAPageQueryParameter]
    #[OALimitQueryParameter]
    public function collection(Request $request): JsonResponse
    {
        $httpQuery = $this->httpQueryHandler->handle(
            $request->query,
            GameEventGetInterface::class
        );

        $paginatedGameEvents = $this->gameEventRepository->findByHttpQuery($httpQuery);

        return $this->json(
            $this->paginatedResponseFactory->fromPaginatedQueryResultInterface(
                $paginatedGameEvents,
                GameEventGetDto::class
            )
        );
    }

    #[Route('/api/game-events/{id}', name: 'api.game_events.update', methods: ['PUT'])]
    #[OA\Tag(name: 'Game Events')]
    #[OA\Parameter(
        name: 'id',
        description: 'The id of the game event to update.',
        in: 'path',
        required: true
    )]
    #[OA\RequestBody(
        required: true,
        content: new Model(type: GameEventUpdateDto::class)
    )]
    #[OA\Response(
        response: HttpCode::OK,
        description: 'Returns instance of the game event.',
        content: new Model(type: GameEventGetDto::class)
    )]
    #[OA\Response(
        response: HttpCode::FORBIDDEN,
        description: 'Forbidden.'
    )]
    #[OA\Response(
        response: HttpCode::NOT_FOUND,
        description: 'Game Event not found.',
    )]
    #[OA\Response(
        response: HttpCode::UNPROCESSABLE_ENTITY,
        description: 'Invalid input.'
    )]
    public function update(Request $request, string $id): JsonResponse
    {
        $existingGameEvent = $this->gameEventRepository->findById($id);

        if ($existingGameEvent === null) {
            throw new ResourceNotFoundException();
        }

        $this->denyAccessUnlessGranted(GameEventVoter::UPDATE, $existingGameEvent);

        /** @var GameEventUpdateDto $dto */
        $dto = $this->serializer->denormalize(
            $request->getPayload()->all(),
            GameEventUpdateDto::class,
        );

        $this->dtoValidator->validatePartial($dto);

        $updatedGameEvent = $this->gameEventRepository->updateOne($existingGameEvent, $dto);

        return $this->json(
            $this->singleObjectResponseFactory->fromObject(
                $updatedGameEvent,
                GameEventGetDto::class
            )
        );
    }

    #[Route('/api/game-events/{id}', name: 'api.game_events.delete', methods: ['DELETE'])]
    #[OA\Tag(name: 'Game Events')]
    #[OA\Response(
        response: HttpCode::NO_CONTENT,
        description: 'Game Event deleted.',
    )]
    #[OA\Response(
        response: HttpCode::FORBIDDEN,
        description: 'Forbidden.'
    )]
    #[OA\Response(
        response: HttpCode::NOT_FOUND,
        description: 'Game Event not found.',
    )]
    public function delete(string $id): JsonResponse
    {
        $gameEvent = $this->gameEventRepository->findById($id);

        if ($gameEvent === null) {
            throw new ResourceNotFoundException();
        }

        $this->denyAccessUnlessGranted(GameEventVoter::DELETE, $gameEvent);

        $this->gameEventRepository->delete($id);

        return $this->json('', HttpCode::NO_CONTENT);
    }
}
