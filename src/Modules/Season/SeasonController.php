<?php

namespace App\Modules\Season;

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
use App\Modules\Season\Dto\SeasonCreateDto;
use App\Modules\Season\Dto\SeasonGetDto;
use App\Modules\Season\Dto\SeasonUpdateDto;
use App\Modules\Season\Model\SeasonGetInterface;
use App\Modules\Season\Repository\SeasonRepositoryInterface;
use App\Modules\Season\Voter\SeasonVoter;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class SeasonController extends AbstractController
{
    public function __construct(
        #[Autowire(service: 'season_repository')]
        private readonly SeasonRepositoryInterface $seasonRepository,
        private readonly RoleBasedSerializerInterface $serializer,
        private readonly DtoValidatorInterface $dtoValidator,
        private readonly HttpQueryHandlerInterface $httpQueryHandler,
        private readonly SingleObjectResponseFactoryInterface $singleObjectResponseFactory,
        private readonly PaginatedResponseFactoryInterface $paginatedResponseFactory,
    ) {
    }

    #[Route('/api/seasons', name: 'api.seasons.create', methods: ['POST'])]
    #[OA\Tag(name: 'Seasons')]
    #[OA\RequestBody(
        required: true,
        content: new Model(type: SeasonCreateDto::class)
    )]
    #[OA\Response(
        response: HttpCode::CREATED,
        description: 'Returns instance of the created season.',
        content: new Model(type: SeasonGetDto::class)
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
        $this->denyAccessUnlessGranted(SeasonVoter::CREATE);

        /** @var SeasonCreateDto $dto */
        $dto = $this->serializer->denormalize(
            $request->getPayload()->all(),
            SeasonCreateDto::class
        );
        $this->dtoValidator->validate($dto);

        $season = $this->seasonRepository->create($dto);

        return $this->json(
            $this->singleObjectResponseFactory->fromObject(
                $season,
                SeasonGetDto::class
            ),
            HttpCode::CREATED
        );
    }

    #[Route('/api/seasons/{id}', name: 'api.seasons.get_by_id', methods: ['GET'])]
    #[Security(name: null)]
    #[OA\Tag(name: 'Seasons')]
    #[OA\Parameter(
        name: 'id',
        description: 'The id of the season to retrieve.',
        in: 'path',
        required: true
    )]
    #[OA\Response(
        response: 200,
        description: 'Returns the season with the given id.',
        content: new Model(type: SeasonGetDto::class)
    )]
    #[OA\Response(
        response: 404,
        description: 'Season not found.',
    )]
    public function getById(string $id): JsonResponse
    {
        $season = $this->seasonRepository->findById($id);
        if ($season === null) {
            throw new ResourceNotFoundException();
        }

        return $this->json($this->singleObjectResponseFactory->fromObject(
            $season,
            SeasonGetDto::class
        ));
    }

    #[Route('/api/seasons', name: 'api.seasons.collection', methods: ['GET'])]
    #[Security(name: null)]
    #[OA\Tag(name: 'Seasons')]
    #[OA\Response(
        response: 200,
        description: 'Returns the seasons that match the filter.',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: SeasonGetDto::class))
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
            SeasonGetInterface::class
        );

        $paginatedSeasons = $this->seasonRepository->findByHttpQuery($httpQuery);

        return $this->json(
            $this->paginatedResponseFactory->fromPaginatedQueryResultInterface(
                $paginatedSeasons,
                SeasonGetDto::class
            )
        );
    }

    #[Route('/api/seasons/{id}', name: 'api.seasons.update', methods: ['PUT'])]
    #[OA\Tag(name: 'Seasons')]
    #[OA\Parameter(
        name: 'id',
        description: 'The id of the season to update.',
        in: 'path',
        required: true
    )]
    #[OA\RequestBody(
        required: true,
        content: new Model(type: SeasonUpdateDto::class)
    )]
    #[OA\Response(
        response: HttpCode::OK,
        description: 'Returns instance of the season.',
        content: new Model(type: SeasonGetDto::class)
    )]
    #[OA\Response(
        response: HttpCode::FORBIDDEN,
        description: 'Forbidden.'
    )]
    #[OA\Response(
        response: HttpCode::NOT_FOUND,
        description: 'Season not found.',
    )]
    #[OA\Response(
        response: HttpCode::UNPROCESSABLE_ENTITY,
        description: 'Invalid input.'
    )]
    public function update(Request $request, string $id): JsonResponse
    {
        $existingSeason = $this->seasonRepository->findById($id);

        if ($existingSeason === null) {
            throw new ResourceNotFoundException();
        }

        $this->denyAccessUnlessGranted(SeasonVoter::UPDATE, $existingSeason);

        /** @var SeasonUpdateDto $dto */
        $dto = $this->serializer->denormalize(
            $request->getPayload()->all(),
            SeasonUpdateDto::class,
        );

        $this->dtoValidator->validatePartial($dto);

        $this->seasonRepository->updateOne($id, $dto);

        $updatedSeason = $this->seasonRepository->findById($id);

        return $this->json(
            $this->singleObjectResponseFactory->fromObject(
                $updatedSeason,
                SeasonGetDto::class
            )
        );
    }

    #[Route('/api/seasons/{id}', name: 'api.seasons.delete', methods: ['DELETE'])]
    #[OA\Tag(name: 'Seasons')]
    #[OA\Response(
        response: HttpCode::NO_CONTENT,
        description: 'Season deleted.',
    )]
    #[OA\Response(
        response: HttpCode::FORBIDDEN,
        description: 'Forbidden.'
    )]
    #[OA\Response(
        response: HttpCode::NOT_FOUND,
        description: 'Season not found.',
    )]
    public function delete(string $id): JsonResponse
    {
        $season = $this->seasonRepository->findById($id);

        if ($season === null) {
            throw new ResourceNotFoundException();
        }

        $this->denyAccessUnlessGranted(SeasonVoter::DELETE, $season);

        $this->seasonRepository->delete($id);

        return $this->json('', HttpCode::NO_CONTENT);
    }
}
