<?php

namespace App\Modules\SeasonTeam;

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
use App\Modules\Season\Voter\SeasonVoter;
use App\Modules\SeasonTeam\Dto\SeasonTeamCreateDto;
use App\Modules\SeasonTeam\Dto\SeasonTeamGetDto;
use App\Modules\SeasonTeam\Dto\SeasonTeamUpdateDto;
use App\Modules\SeasonTeam\Model\SeasonTeamGetInterface;
use App\Modules\SeasonTeam\Repository\SeasonTeamRepositoryInterface;
use App\Modules\SeasonTeam\Voter\SeasonTeamVoter;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class SeasonTeamController extends AbstractController
{
    public function __construct(
        #[Autowire(service: 'season_team_repository')]
        private readonly SeasonTeamRepositoryInterface $seasonTeamRepository,
        private readonly RoleBasedSerializerInterface $serializer,
        private readonly DtoValidatorInterface $dtoValidator,
        private readonly HttpQueryHandlerInterface $httpQueryHandler,
        private readonly SingleObjectResponseFactoryInterface $singleObjectResponseFactory,
        private readonly PaginatedResponseFactoryInterface $paginatedResponseFactory,
    ) {
    }

    #[Route('/api/season-teams', name: 'api.seasons.teams.create', methods: ['POST'])]
    #[OA\Tag(name: 'Season Teams')]
    #[OA\RequestBody(
        required: true,
        content: new Model(type: SeasonTeamCreateDto::class)
    )]
    #[OA\Response(
        response: HttpCode::CREATED,
        description: 'Returns instance of the created season.',
        content: new Model(type: SeasonTeamGetDto::class)
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
        $this->denyAccessUnlessGranted(SeasonTeamVoter::CREATE);

        /** @var SeasonTeamCreateDto $dto */
        $dto = $this->serializer->denormalize(
            $request->getPayload()->all(),
            SeasonTeamCreateDto::class
        );
        $this->dtoValidator->validate($dto);

        $season = $this->seasonTeamRepository->create($dto);

        return $this->json(
            $this->singleObjectResponseFactory->fromObject(
                $season,
                SeasonTeamGetDto::class
            ),
            HttpCode::CREATED
        );
    }

    #[Route('/api/season-teams/{id}', name: 'api.seasons.teams.get_by_id', methods: ['GET'])]
    #[Security(name: null)]
    #[OA\Tag(name: 'Season Teams')]
    #[OA\Parameter(
        name: 'id',
        description: 'The id of the season to retrieve.',
        in: 'path',
        required: true
    )]
    #[OA\Response(
        response: 200,
        description: 'Returns the season with the given id.',
        content: new Model(type: SeasonTeamGetDto::class)
    )]
    #[OA\Response(
        response: 404,
        description: 'Season Teams not found.',
    )]
    public function getById(string $id): JsonResponse
    {
        $seasonTeam = $this->seasonTeamRepository->findById($id);
        if ($seasonTeam === null) {
            throw new ResourceNotFoundException();
        }

        return $this->json($this->singleObjectResponseFactory->fromObject(
            $seasonTeam,
            SeasonTeamGetDto::class
        ));
    }

    #[Route('/api/season-teams', name: 'api.seasons.teams.collection', methods: ['GET'])]
    #[Security(name: null)]
    #[OA\Tag(name: 'Season Teams')]
    #[OA\Response(
        response: 200,
        description: 'Returns the seasons that match the filter.',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: SeasonTeamGetDto::class))
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
            SeasonTeamGetInterface::class
        );

        $paginatedSeasons = $this->seasonTeamRepository->findByHttpQuery($httpQuery);

        return $this->json(
            $this->paginatedResponseFactory->fromPaginatedQueryResultInterface(
                $paginatedSeasons,
                SeasonTeamGetDto::class
            )
        );
    }

    #[Route('/api/season-teams/{id}', name: 'api.seasons.teams.update', methods: ['PUT'])]
    #[OA\Tag(name: 'Season Teams')]
    #[OA\Parameter(
        name: 'id',
        description: 'The id of the season to update.',
        in: 'path',
        required: true
    )]
    #[OA\RequestBody(
        required: true,
        content: new Model(type: SeasonTeamUpdateDto::class)
    )]
    #[OA\Response(
        response: HttpCode::OK,
        description: 'Returns instance of the season.',
        content: new Model(type: SeasonTeamGetDto::class)
    )]
    #[OA\Response(
        response: HttpCode::FORBIDDEN,
        description: 'Forbidden.'
    )]
    #[OA\Response(
        response: HttpCode::NOT_FOUND,
        description: 'Season Teams not found.',
    )]
    #[OA\Response(
        response: HttpCode::UNPROCESSABLE_ENTITY,
        description: 'Invalid input.'
    )]
    public function update(Request $request, string $id): JsonResponse
    {
        $existingSeasonTeam = $this->seasonTeamRepository->findById($id);

        if ($existingSeasonTeam === null) {
            throw new ResourceNotFoundException();
        }

        $this->denyAccessUnlessGranted(SeasonVoter::UPDATE, $existingSeasonTeam);

        /** @var SeasonTeamUpdateDto $dto */
        $dto = $this->serializer->denormalize(
            $request->getPayload()->all(),
            SeasonTeamUpdateDto::class,
        );

        $this->dtoValidator->validatePartial($dto);

        $updatedSeasonTeam = $this->seasonTeamRepository->updateOne($existingSeasonTeam, $dto);

        return $this->json(
            $this->singleObjectResponseFactory->fromObject(
                $updatedSeasonTeam,
                SeasonTeamGetDto::class
            )
        );
    }

    #[Route('/api/season-teams/{id}', name: 'api.seasons.teams.delete', methods: ['DELETE'])]
    #[OA\Tag(name: 'Season Teams')]
    #[OA\Response(
        response: HttpCode::NO_CONTENT,
        description: 'Season Teams deleted.',
    )]
    #[OA\Response(
        response: HttpCode::FORBIDDEN,
        description: 'Forbidden.'
    )]
    #[OA\Response(
        response: HttpCode::NOT_FOUND,
        description: 'Season Teams not found.',
    )]
    public function delete(string $id): JsonResponse
    {
        $seasonTeam = $this->seasonTeamRepository->findById($id);

        if ($seasonTeam === null) {
            throw new ResourceNotFoundException();
        }

        $this->denyAccessUnlessGranted(SeasonVoter::DELETE, $seasonTeam);

        $this->seasonTeamRepository->delete($id);

        return $this->json('', HttpCode::NO_CONTENT);
    }
}
