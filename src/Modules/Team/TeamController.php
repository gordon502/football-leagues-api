<?php

namespace App\Modules\Team;

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
use App\Modules\Team\Dto\TeamCreateDto;
use App\Modules\Team\Dto\TeamGetDto;
use App\Modules\Team\Dto\TeamUpdateDto;
use App\Modules\Team\Model\TeamGetInterface;
use App\Modules\Team\Repository\TeamRepositoryInterface;
use App\Modules\Team\Voter\TeamVoter;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class TeamController extends AbstractController
{
    public function __construct(
        #[Autowire(service: 'team_repository')]
        private readonly TeamRepositoryInterface $teamRepository,
        private readonly RoleBasedSerializerInterface $serializer,
        private readonly DtoValidatorInterface $dtoValidator,
        private readonly HttpQueryHandlerInterface $httpQueryHandler,
        private readonly SingleObjectResponseFactoryInterface $singleObjectResponseFactory,
        private readonly PaginatedResponseFactoryInterface $paginatedResponseFactory,
    ) {
    }

    #[Route('/api/teams', name: 'api.teams.create', methods: ['POST'])]
    #[OA\Tag(name: 'Teams')]
    #[OA\RequestBody(
        required: true,
        content: new Model(type: TeamCreateDto::class)
    )]
    #[OA\Response(
        response: HttpCode::CREATED,
        description: 'Returns instance of the created team.',
        content: new Model(type: TeamGetDto::class)
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
        $this->denyAccessUnlessGranted(TeamVoter::CREATE);

        /** @var TeamCreateDto $dto */
        $dto = $this->serializer->denormalize(
            $request->getPayload()->all(),
            TeamCreateDto::class
        );
        $this->dtoValidator->validate($dto);

        $team = $this->teamRepository->create($dto);

        return $this->json(
            $this->singleObjectResponseFactory->fromObject(
                $team,
                TeamGetDto::class
            ),
            HttpCode::CREATED
        );
    }

    #[Route('/api/teams/{id}', name: 'api.teams.get_by_id', methods: ['GET'])]
    #[Security(name: null)]
    #[OA\Tag(name: 'Teams')]
    #[OA\Parameter(
        name: 'id',
        description: 'The id of the team to retrieve.',
        in: 'path',
        required: true
    )]
    #[OA\Response(
        response: 200,
        description: 'Returns the team with the given id.',
        content: new Model(type: TeamGetDto::class)
    )]
    #[OA\Response(
        response: 404,
        description: 'Team not found.',
    )]
    public function getById(string $id): JsonResponse
    {
        $team = $this->teamRepository->findById($id);
        if ($team === null) {
            throw new ResourceNotFoundException();
        }

        return $this->json($this->singleObjectResponseFactory->fromObject(
            $team,
            TeamGetDto::class
        ));
    }

    #[Route('/api/teams', name: 'api.teams.collection', methods: ['GET'])]
    #[Security(name: null)]
    #[OA\Tag(name: 'Teams')]
    #[OA\Response(
        response: 200,
        description: 'Returns the teams that match the filter.',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: TeamGetDto::class))
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
            TeamGetInterface::class
        );

        $paginatedTeams = $this->teamRepository->findByHttpQuery($httpQuery);

        return $this->json(
            $this->paginatedResponseFactory->fromPaginatedQueryResultInterface(
                $paginatedTeams,
                TeamGetDto::class
            )
        );
    }

    #[Route('/api/teams/{id}', name: 'api.teams.update', methods: ['PUT'])]
    #[OA\Tag(name: 'Teams')]
    #[OA\Parameter(
        name: 'id',
        description: 'The id of the team to update.',
        in: 'path',
        required: true
    )]
    #[OA\RequestBody(
        required: true,
        content: new Model(type: TeamUpdateDto::class)
    )]
    #[OA\Response(
        response: HttpCode::OK,
        description: 'Returns instance of the team.',
        content: new Model(type: TeamGetDto::class)
    )]
    #[OA\Response(
        response: HttpCode::FORBIDDEN,
        description: 'Forbidden.'
    )]
    #[OA\Response(
        response: HttpCode::NOT_FOUND,
        description: 'Team not found.',
    )]
    #[OA\Response(
        response: HttpCode::UNPROCESSABLE_ENTITY,
        description: 'Invalid input.'
    )]
    public function update(Request $request, string $id): JsonResponse
    {
        $existingTeam = $this->teamRepository->findById($id);

        if ($existingTeam === null) {
            throw new ResourceNotFoundException();
        }

        $this->denyAccessUnlessGranted(TeamVoter::UPDATE, $existingTeam);

        /** @var TeamUpdateDto $dto */
        $dto = $this->serializer->denormalize(
            $request->getPayload()->all(),
            TeamUpdateDto::class,
        );

        $this->dtoValidator->validatePartial($dto);

        $this->teamRepository->updateOne($id, $dto);

        $updatedTeam = $this->teamRepository->findById($id);

        return $this->json(
            $this->singleObjectResponseFactory->fromObject(
                $updatedTeam,
                TeamGetDto::class
            )
        );
    }

    #[Route('/api/teams/{id}', name: 'api.teams.delete', methods: ['DELETE'])]
    #[OA\Tag(name: 'Teams')]
    #[OA\Response(
        response: HttpCode::NO_CONTENT,
        description: 'Team deleted.',
    )]
    #[OA\Response(
        response: HttpCode::FORBIDDEN,
        description: 'Forbidden.'
    )]
    #[OA\Response(
        response: HttpCode::NOT_FOUND,
        description: 'Team not found.',
    )]
    public function delete(string $id): JsonResponse
    {
        $team = $this->teamRepository->findById($id);

        if ($team === null) {
            throw new ResourceNotFoundException();
        }

        $this->denyAccessUnlessGranted(TeamVoter::DELETE, $team);

        $this->teamRepository->delete($id);

        return $this->json('', HttpCode::NO_CONTENT);
    }
}
