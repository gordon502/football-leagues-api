<?php

namespace App\Modules\Game;

use App\Common\CustomValidation\CustomValidationInterface;
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
use App\Modules\Game\CustomValidation\GameSeasonTeamBelongsToRightSeasonValidation;
use App\Modules\Game\Dto\GameCreateDto;
use App\Modules\Game\Dto\GameGetDto;
use App\Modules\Game\Dto\GameUpdateDto;
use App\Modules\Game\Exception\WrongSeasonTeamSelectedException;
use App\Modules\Game\Repository\GameRepositoryInterface;
use App\Modules\Game\Voter\GameVoter;
use App\Modules\Game\Model\GameGetInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class GameController extends AbstractController
{
    public function __construct(
        #[Autowire(service: 'game_repository')]
        private readonly GameRepositoryInterface $gameRepository,
        private readonly RoleBasedSerializerInterface $serializer,
        private readonly DtoValidatorInterface $dtoValidator,
        private readonly HttpQueryHandlerInterface $httpQueryHandler,
        private readonly SingleObjectResponseFactoryInterface $singleObjectResponseFactory,
        private readonly PaginatedResponseFactoryInterface $paginatedResponseFactory,
        #[Autowire(service: GameSeasonTeamBelongsToRightSeasonValidation::class)]
        private readonly CustomValidationInterface $gameSeasonTeamBelongsToRightSeasonValidation,
    ) {
    }

    #[Route('/api/games', name: 'api.games.create', methods: ['POST'])]
    #[OA\Tag(name: 'Games')]
    #[OA\RequestBody(
        required: true,
        content: new Model(type: GameCreateDto::class)
    )]
    #[OA\Response(
        response: HttpCode::CREATED,
        description: 'Returns instance of the created game.',
        content: new Model(type: GameGetDto::class)
    )]
    #[OA\Response(
        response: HttpCode::BAD_REQUEST,
        description: 'Validation failed.'
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
        $this->denyAccessUnlessGranted(GameVoter::CREATE);

        /** @var GameCreateDto $dto */
        $dto = $this->serializer->denormalize(
            $request->getPayload()->all(),
            GameCreateDto::class
        );
        $this->dtoValidator->validate($dto);

        $game = $this->gameRepository->create($dto);

        try {
            $this->gameSeasonTeamBelongsToRightSeasonValidation->validate($game);
        } catch (WrongSeasonTeamSelectedException $exception) {
            $this->gameRepository->delete($game->getId());
            throw $exception;
        }

        return $this->json(
            $this->singleObjectResponseFactory->fromObject(
                $game,
                GameGetDto::class
            ),
            HttpCode::CREATED
        );
    }

    #[Route('/api/games/{id}', name: 'api.games.get_by_id', methods: ['GET'])]
    #[Security(name: null)]
    #[OA\Tag(name: 'Games')]
    #[OA\Parameter(
        name: 'id',
        description: 'The id of the game to retrieve.',
        in: 'path',
        required: true
    )]
    #[OA\Response(
        response: 200,
        description: 'Returns the game with the given id.',
        content: new Model(type: GameGetDto::class)
    )]
    #[OA\Response(
        response: 404,
        description: 'Game not found.',
    )]
    public function getById(string $id): JsonResponse
    {
        $game = $this->gameRepository->findById($id);
        if ($game === null) {
            throw new ResourceNotFoundException();
        }

        return $this->json($this->singleObjectResponseFactory->fromObject(
            $game,
            GameGetDto::class
        ));
    }

    #[Route('/api/games', name: 'api.games.collection', methods: ['GET'])]
    #[Security(name: null)]
    #[OA\Tag(name: 'Games')]
    #[OA\Response(
        response: 200,
        description: 'Returns the games that match the filter.',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: GameGetDto::class))
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
            GameGetInterface::class
        );

        $paginatedGames = $this->gameRepository->findByHttpQuery($httpQuery);

        return $this->json(
            $this->paginatedResponseFactory->fromPaginatedQueryResultInterface(
                $paginatedGames,
                GameGetDto::class
            )
        );
    }

    #[Route('/api/games/{id}', name: 'api.games.update', methods: ['PUT'])]
    #[OA\Tag(name: 'Games')]
    #[OA\Parameter(
        name: 'id',
        description: 'The id of the game to update.',
        in: 'path',
        required: true
    )]
    #[OA\RequestBody(
        required: true,
        content: new Model(type: GameUpdateDto::class)
    )]
    #[OA\Response(
        response: HttpCode::OK,
        description: 'Returns instance of the game.',
        content: new Model(type: GameGetDto::class)
    )]
    #[OA\Response(
        response: HttpCode::BAD_REQUEST,
        description: 'Validation failed.'
    )]
    #[OA\Response(
        response: HttpCode::FORBIDDEN,
        description: 'Forbidden.'
    )]
    #[OA\Response(
        response: HttpCode::NOT_FOUND,
        description: 'Game not found.',
    )]
    #[OA\Response(
        response: HttpCode::UNPROCESSABLE_ENTITY,
        description: 'Invalid input.'
    )]
    public function update(Request $request, string $id): JsonResponse
    {
        $existingGame = $this->gameRepository->findById($id);

        if ($existingGame === null) {
            throw new ResourceNotFoundException();
        }

        $this->denyAccessUnlessGranted(GameVoter::UPDATE, $existingGame);

        /** @var GameUpdateDto $dto */
        $dto = $this->serializer->denormalize(
            $request->getPayload()->all(),
            GameUpdateDto::class,
        );

        $this->dtoValidator->validatePartial($dto);

        $updatedGame = $this->gameRepository->updateOne($existingGame, $dto, true);

        $this->gameSeasonTeamBelongsToRightSeasonValidation->validate($updatedGame);
        $this->gameRepository->flushUpdateOne();

        return $this->json(
            $this->singleObjectResponseFactory->fromObject(
                $updatedGame,
                GameGetDto::class
            )
        );
    }

    #[Route('/api/games/{id}', name: 'api.games.delete', methods: ['DELETE'])]
    #[OA\Tag(name: 'Games')]
    #[OA\Response(
        response: HttpCode::NO_CONTENT,
        description: 'Game deleted.',
    )]
    #[OA\Response(
        response: HttpCode::FORBIDDEN,
        description: 'Forbidden.'
    )]
    #[OA\Response(
        response: HttpCode::NOT_FOUND,
        description: 'Game not found.',
    )]
    public function delete(string $id): JsonResponse
    {
        $game = $this->gameRepository->findById($id);

        if ($game === null) {
            throw new ResourceNotFoundException();
        }

        $this->denyAccessUnlessGranted(GameVoter::DELETE, $game);

        $this->gameRepository->delete($id);

        return $this->json('', HttpCode::NO_CONTENT);
    }
}
