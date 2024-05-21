<?php

namespace App\Modules\League;

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
use App\Modules\League\Dto\LeagueCreateDto;
use App\Modules\League\Dto\LeagueGetDto;
use App\Modules\League\Dto\LeagueUpdateDto;
use App\Modules\League\Model\LeagueGetInterface;
use App\Modules\League\Repository\LeagueRepositoryInterface;
use App\Modules\League\Voter\LeagueVoter;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class LeagueController extends AbstractController
{
    public function __construct(
        #[Autowire(service: 'league_repository')]
        private readonly LeagueRepositoryInterface $leagueRepository,
        private readonly RoleBasedSerializerInterface $serializer,
        private readonly DtoValidatorInterface $dtoValidator,
        private readonly HttpQueryHandlerInterface $httpQueryHandler,
        private readonly SingleObjectResponseFactoryInterface $singleObjectResponseFactory,
        private readonly PaginatedResponseFactoryInterface $paginatedResponseFactory,
    ) {
    }

    #[Route('/api/leagues', name: 'api.leagues.create', methods: ['POST'])]
    #[OA\Tag(name: 'Leagues')]
    #[OA\RequestBody(
        required: true,
        content: new Model(type: LeagueCreateDto::class)
    )]
    #[OA\Response(
        response: HttpCode::CREATED,
        description: 'Returns instance of the created league.',
        content: new Model(type: LeagueGetDto::class)
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
        $this->denyAccessUnlessGranted(LeagueVoter::CREATE);

        /** @var LeagueCreateDto $dto */
        $dto = $this->serializer->denormalize(
            $request->getPayload()->all(),
            LeagueCreateDto::class
        );
        $this->dtoValidator->validate($dto);

        $league = $this->leagueRepository->create($dto);

        return $this->json(
            $this->singleObjectResponseFactory->fromObject(
                $league,
                LeagueGetDto::class
            ),
            HttpCode::CREATED
        );
    }

    #[Route('/api/leagues/{id}', name: 'api.leagues.get_by_id', methods: ['GET'])]
    #[Security(name: null)]
    #[OA\Tag(name: 'Leagues')]
    #[OA\Parameter(
        name: 'id',
        description: 'The id of the league to retrieve.',
        in: 'path',
        required: true
    )]
    #[OA\Response(
        response: 200,
        description: 'Returns the league with the given id.',
        content: new Model(type: LeagueGetDto::class)
    )]
    #[OA\Response(
        response: 404,
        description: 'League not found.',
    )]
    public function getById(string $id): JsonResponse
    {
        $league = $this->leagueRepository->findById($id);
        if ($league === null) {
            throw new ResourceNotFoundException();
        }

        return $this->json($this->singleObjectResponseFactory->fromObject(
            $league,
            LeagueGetDto::class
        ));
    }

    #[Route('/api/leagues', name: 'api.leagues.collection', methods: ['GET'])]
    #[Security(name: null)]
    #[OA\Tag(name: 'Leagues')]
    #[OA\Response(
        response: 200,
        description: 'Returns the leagues that match the filter.',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: LeagueGetDto::class))
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
            LeagueGetInterface::class
        );

        $paginatedLeagues = $this->leagueRepository->findByHttpQuery($httpQuery);

        return $this->json(
            $this->paginatedResponseFactory->fromPaginatedQueryResultInterface(
                $paginatedLeagues,
                LeagueGetDto::class
            )
        );
    }

    #[Route('/api/leagues/{id}', name: 'api.leagues.update', methods: ['PUT'])]
    #[OA\Tag(name: 'Leagues')]
    #[OA\Parameter(
        name: 'id',
        description: 'The id of the league to update.',
        in: 'path',
        required: true
    )]
    #[OA\RequestBody(
        required: true,
        content: new Model(type: LeagueUpdateDto::class)
    )]
    #[OA\Response(
        response: HttpCode::OK,
        description: 'Returns instance of the league.',
        content: new Model(type: LeagueGetDto::class)
    )]
    #[OA\Response(
        response: HttpCode::FORBIDDEN,
        description: 'Forbidden.'
    )]
    #[OA\Response(
        response: HttpCode::NOT_FOUND,
        description: 'League not found.',
    )]
    #[OA\Response(
        response: HttpCode::UNPROCESSABLE_ENTITY,
        description: 'Invalid input.'
    )]
    public function update(Request $request, string $id): JsonResponse
    {
        $existingLeague = $this->leagueRepository->findById($id);

        if ($existingLeague === null) {
            throw new ResourceNotFoundException();
        }

        $this->denyAccessUnlessGranted(LeagueVoter::UPDATE, $existingLeague);

        /** @var LeagueUpdateDto $dto */
        $dto = $this->serializer->denormalize(
            $request->getPayload()->all(),
            LeagueUpdateDto::class,
        );

        $this->dtoValidator->validatePartial($dto);

        $updatedLeague = $this->leagueRepository->updateOne($existingLeague, $dto);

        return $this->json(
            $this->singleObjectResponseFactory->fromObject(
                $updatedLeague,
                LeagueGetDto::class
            )
        );
    }

    #[Route('/api/leagues/{id}', name: 'api.leagues.delete', methods: ['DELETE'])]
    #[OA\Tag(name: 'Leagues')]
    #[OA\Response(
        response: HttpCode::NO_CONTENT,
        description: 'League deleted.',
    )]
    #[OA\Response(
        response: HttpCode::FORBIDDEN,
        description: 'Forbidden.'
    )]
    #[OA\Response(
        response: HttpCode::NOT_FOUND,
        description: 'League not found.',
    )]
    public function delete(string $id): JsonResponse
    {
        $league = $this->leagueRepository->findById($id);

        if ($league === null) {
            throw new ResourceNotFoundException();
        }

        $this->denyAccessUnlessGranted(LeagueVoter::DELETE, $league);

        $this->leagueRepository->delete($id);

        return $this->json('', HttpCode::NO_CONTENT);
    }
}
