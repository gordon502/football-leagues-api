<?php

namespace App\Modules\Round;

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
use App\Modules\Round\Dto\RoundCreateDto;
use App\Modules\Round\Dto\RoundGetDto;
use App\Modules\Round\Dto\RoundUpdateDto;
use App\Modules\Round\Model\RoundGetInterface;
use App\Modules\Round\Repository\RoundRepositoryInterface;
use App\Modules\Round\Voter\RoundVoter;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class RoundController extends AbstractController
{
    public function __construct(
        #[Autowire(service: 'round_repository')]
        private readonly RoundRepositoryInterface $roundRepository,
        private readonly RoleBasedSerializerInterface $serializer,
        private readonly DtoValidatorInterface $dtoValidator,
        private readonly HttpQueryHandlerInterface $httpQueryHandler,
        private readonly SingleObjectResponseFactoryInterface $singleObjectResponseFactory,
        private readonly PaginatedResponseFactoryInterface $paginatedResponseFactory,
    ) {
    }

    #[Route('/api/rounds', name: 'api.rounds.create', methods: ['POST'])]
    #[OA\Tag(name: 'Rounds')]
    #[OA\RequestBody(
        required: true,
        content: new Model(type: RoundCreateDto::class)
    )]
    #[OA\Response(
        response: HttpCode::CREATED,
        description: 'Returns instance of the created round.',
        content: new Model(type: RoundGetDto::class)
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
        $this->denyAccessUnlessGranted(RoundVoter::CREATE);

        /** @var RoundCreateDto $dto */
        $dto = $this->serializer->denormalize(
            $request->getPayload()->all(),
            RoundCreateDto::class
        );
        $this->dtoValidator->validate($dto);

        $round = $this->roundRepository->create($dto);

        return $this->json(
            $this->singleObjectResponseFactory->fromObject(
                $round,
                RoundGetDto::class
            ),
            HttpCode::CREATED
        );
    }

    #[Route('/api/rounds/{id}', name: 'api.rounds.get_by_id', methods: ['GET'])]
    #[Security(name: null)]
    #[OA\Tag(name: 'Rounds')]
    #[OA\Parameter(
        name: 'id',
        description: 'The id of the round to retrieve.',
        in: 'path',
        required: true
    )]
    #[OA\Response(
        response: 200,
        description: 'Returns the round with the given id.',
        content: new Model(type: RoundGetDto::class)
    )]
    #[OA\Response(
        response: 404,
        description: 'Round not found.',
    )]
    public function getById(string $id): JsonResponse
    {
        $round = $this->roundRepository->findById($id);
        if ($round === null) {
            throw new ResourceNotFoundException();
        }

        return $this->json($this->singleObjectResponseFactory->fromObject(
            $round,
            RoundGetDto::class
        ));
    }

    #[Route('/api/rounds', name: 'api.rounds.collection', methods: ['GET'])]
    #[Security(name: null)]
    #[OA\Tag(name: 'Rounds')]
    #[OA\Response(
        response: 200,
        description: 'Returns the rounds that match the filter.',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: RoundGetDto::class))
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
            RoundGetInterface::class
        );

        $paginatedRounds = $this->roundRepository->findByHttpQuery($httpQuery);

        return $this->json(
            $this->paginatedResponseFactory->fromPaginatedQueryResultInterface(
                $paginatedRounds,
                RoundGetDto::class
            )
        );
    }

    #[Route('/api/rounds/{id}', name: 'api.rounds.update', methods: ['PUT'])]
    #[OA\Tag(name: 'Rounds')]
    #[OA\Parameter(
        name: 'id',
        description: 'The id of the round to update.',
        in: 'path',
        required: true
    )]
    #[OA\RequestBody(
        required: true,
        content: new Model(type: RoundUpdateDto::class)
    )]
    #[OA\Response(
        response: HttpCode::OK,
        description: 'Returns instance of the round.',
        content: new Model(type: RoundGetDto::class)
    )]
    #[OA\Response(
        response: HttpCode::FORBIDDEN,
        description: 'Forbidden.'
    )]
    #[OA\Response(
        response: HttpCode::NOT_FOUND,
        description: 'Round not found.',
    )]
    #[OA\Response(
        response: HttpCode::UNPROCESSABLE_ENTITY,
        description: 'Invalid input.'
    )]
    public function update(Request $request, string $id): JsonResponse
    {
        $existingRound = $this->roundRepository->findById($id);

        if ($existingRound === null) {
            throw new ResourceNotFoundException();
        }

        $this->denyAccessUnlessGranted(RoundVoter::UPDATE, $existingRound);

        /** @var RoundUpdateDto $dto */
        $dto = $this->serializer->denormalize(
            $request->getPayload()->all(),
            RoundUpdateDto::class,
        );

        $this->dtoValidator->validatePartial($dto);

        $updatedRound = $this->roundRepository->updateOne($existingRound, $dto);

        return $this->json(
            $this->singleObjectResponseFactory->fromObject(
                $updatedRound,
                RoundGetDto::class
            )
        );
    }

    #[Route('/api/rounds/{id}', name: 'api.rounds.delete', methods: ['DELETE'])]
    #[OA\Tag(name: 'Rounds')]
    #[OA\Response(
        response: HttpCode::NO_CONTENT,
        description: 'Round deleted.',
    )]
    #[OA\Response(
        response: HttpCode::FORBIDDEN,
        description: 'Forbidden.'
    )]
    #[OA\Response(
        response: HttpCode::NOT_FOUND,
        description: 'Round not found.',
    )]
    public function delete(string $id): JsonResponse
    {
        $round = $this->roundRepository->findById($id);

        if ($round === null) {
            throw new ResourceNotFoundException();
        }

        $this->denyAccessUnlessGranted(RoundVoter::DELETE, $round);

        $this->roundRepository->delete($id);

        return $this->json('', HttpCode::NO_CONTENT);
    }
}
