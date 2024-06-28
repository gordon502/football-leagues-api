<?php

namespace App\Modules\Leaderboard;

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
use App\Modules\Leaderboard\CustomValidation\LeaderboardSeasonAndTeamRelatedValidation;
use App\Modules\Leaderboard\CustomValidation\SeasonTeamOnlyOnOneLeaderboardValidation;
use App\Modules\Leaderboard\Dto\LeaderboardBatchCreateDto;
use App\Modules\Leaderboard\Dto\LeaderboardBatchUpdateDto;
use App\Modules\Leaderboard\Dto\LeaderboardBatchUpdateSingleDto;
use App\Modules\Leaderboard\Dto\LeaderboardCreateDto;
use App\Modules\Leaderboard\Dto\LeaderboardGetDto;
use App\Modules\Leaderboard\Dto\LeaderboardUpdateDto;
use App\Modules\Leaderboard\Exception\SeasonTeamAlreadyOnLeaderboardException;
use App\Modules\Leaderboard\Exception\WrongSeasonTeamSelectedException;
use App\Modules\Leaderboard\Model\LeaderboardGetInterface;
use App\Modules\Leaderboard\Model\LeaderboardInterface;
use App\Modules\Leaderboard\Repository\LeaderboardRepositoryInterface;
use App\Modules\Leaderboard\Voter\LeaderboardVoter;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class LeaderboardController extends AbstractController
{
    public function __construct(
        #[Autowire(service: 'leaderboard_repository')]
        private readonly LeaderboardRepositoryInterface $leaderboardRepository,
        private readonly RoleBasedSerializerInterface $serializer,
        private readonly DtoValidatorInterface $dtoValidator,
        private readonly HttpQueryHandlerInterface $httpQueryHandler,
        private readonly SingleObjectResponseFactoryInterface $singleObjectResponseFactory,
        private readonly PaginatedResponseFactoryInterface $paginatedResponseFactory,
        #[Autowire(service: LeaderboardSeasonAndTeamRelatedValidation::class)]
        private readonly CustomValidationInterface $leaderboardSeasonAndTeamRelatedValidation,
        #[Autowire(service: SeasonTeamOnlyOnOneLeaderboardValidation::class)]
        private readonly CustomValidationInterface $seasonTeamOnlyOnOneLeaderboardValidation
    ) {
    }

    #[Route('/api/leaderboards', name: 'api.leaderboards.create', methods: ['POST'])]
    #[OA\Tag(name: 'Leaderboards')]
    #[OA\RequestBody(
        required: true,
        content: new Model(type: LeaderboardCreateDto::class)
    )]
    #[OA\Response(
        response: HttpCode::CREATED,
        description: 'Returns instance of the created leaderboard.',
        content: new Model(type: LeaderboardGetDto::class)
    )]
    #[OA\Response(
        response: HttpCode::BAD_REQUEST,
        description: 'Season team is already on a leaderboard or season team is not related to season.'
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
        $this->denyAccessUnlessGranted(LeaderboardVoter::CREATE);

        /** @var LeaderboardCreateDto $dto */
        $dto = $this->serializer->denormalize(
            $request->getPayload()->all(),
            LeaderboardCreateDto::class
        );
        $this->dtoValidator->validate($dto);

        try {
            $leaderboard = $this->leaderboardRepository->create($dto);
            $this->leaderboardSeasonAndTeamRelatedValidation->validate($leaderboard);
            $this->seasonTeamOnlyOnOneLeaderboardValidation->validate($leaderboard);
        } catch (
            WrongSeasonTeamSelectedException
            | SeasonTeamAlreadyOnLeaderboardException
            | UniqueConstraintViolationException $exception
        ) {
            if (isset($leaderboard)) {
                $this->leaderboardRepository->delete($leaderboard->getId());
            }

            $exception instanceof UniqueConstraintViolationException
                ? throw new SeasonTeamAlreadyOnLeaderboardException()
                : throw $exception;
        }

        return $this->json(
            $this->singleObjectResponseFactory->fromObject(
                $leaderboard,
                LeaderboardGetDto::class
            ),
            HttpCode::CREATED
        );
    }

    /**
     * @throws UniqueConstraintViolationException
     */
    #[Route('/api/leaderboards/batch-create', name: 'api.leaderboards.batch.create', methods: ['POST'])]
    #[OA\Tag(name: 'Leaderboards')]
    #[OA\RequestBody(
        required: true,
        content: new Model(type: LeaderboardBatchCreateDto::class)
    )]
    #[OA\Response(
        response: HttpCode::CREATED,
        description: 'Returns instances of the created leaderboards.',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: LeaderboardGetDto::class))
        )
    )]
    #[OA\Response(
        response: HttpCode::BAD_REQUEST,
        description: 'One of Season team is already on a leaderboard or season team is not related to season.'
    )]
    #[OA\Response(
        response: HttpCode::FORBIDDEN,
        description: 'Access denied.'
    )]
    #[OA\Response(
        response: HttpCode::UNPROCESSABLE_ENTITY,
        description: 'Invalid input.'
    )]
    public function batchCreate(Request $request): JsonResponse
    {
        $this->denyAccessUnlessGranted(LeaderboardVoter::CREATE);

        /** @var LeaderboardBatchCreateDto $dto */
        $dto = $this->serializer->denormalize(
            $request->getPayload()->all(),
            LeaderboardBatchCreateDto::class
        );
        $this->dtoValidator->validate($dto);

        /** @var LeaderboardCreateDto[] $denormalizedLeaderboards */
        $denormalizedLeaderboards = [];
        foreach ($dto->getLeaderboards() as $leaderboardArr) {
            $denormalizedLeaderboards[] = $this->serializer->denormalize(
                $leaderboardArr,
                LeaderboardCreateDto::class
            );
            $this->dtoValidator->validate($denormalizedLeaderboards[count($denormalizedLeaderboards) - 1]);
        }

        /** @var LeaderboardInterface[] $createdLeaderboards */
        $createdLeaderboards = [];
        try {
            foreach ($denormalizedLeaderboards as $leaderboard) {
                $createdLeaderboards[] = $this->leaderboardRepository->create($leaderboard);
            }

            foreach ($createdLeaderboards as $leaderboard) {
                $this->leaderboardSeasonAndTeamRelatedValidation->validate($leaderboard);
                $this->seasonTeamOnlyOnOneLeaderboardValidation->validate($leaderboard);
            }
        } catch (
            WrongSeasonTeamSelectedException
            | SeasonTeamAlreadyOnLeaderboardException
            | UniqueConstraintViolationException $exception
        ) {
            foreach ($createdLeaderboards as $leaderboard) {
                $this->leaderboardRepository->delete($leaderboard->getId());
            }

            $exception instanceof UniqueConstraintViolationException
                ? throw new SeasonTeamAlreadyOnLeaderboardException()
                : throw $exception;
        }

        return $this->json(
            array_map(
                fn($leaderboard) => $this->singleObjectResponseFactory->fromObject(
                    $leaderboard,
                    LeaderboardGetDto::class
                ),
                $createdLeaderboards,
            ),
            HttpCode::CREATED
        );
    }

    #[Route('/api/leaderboards/batch-update', name: 'api.leaderboards.batch.update', methods: ['POST'])]
    #[OA\Tag(name: 'Leaderboards')]
    #[OA\RequestBody(
        required: true,
        content: new Model(type: LeaderboardBatchUpdateDto::class)
    )]
    #[OA\Response(
        response: HttpCode::CREATED,
        description: 'Returns instances of the updated leaderboards.',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: LeaderboardGetDto::class))
        )
    )]
    #[OA\Response(
        response: HttpCode::BAD_REQUEST,
        description: 'One of Season team is already on a leaderboard or season team is not related to season.'
    )]
    #[OA\Response(
        response: HttpCode::FORBIDDEN,
        description: 'Access denied.'
    )]
    #[OA\Response(
        response: HttpCode::UNPROCESSABLE_ENTITY,
        description: 'Invalid input.'
    )]
    public function batchUpdate(Request $request): JsonResponse
    {
        $this->denyAccessUnlessGranted(LeaderboardVoter::UPDATE);

        /** @var LeaderboardBatchUpdateDto $dto */
        $dto = $this->serializer->denormalize(
            $request->getPayload()->all(),
            LeaderboardBatchUpdateDto::class
        );
        $this->dtoValidator->validate($dto);

        /** @var LeaderboardBatchUpdateSingleDto[] $denormalizedLeaderboards */
        $denormalizedLeaderboards = [];
        foreach ($dto->getLeaderboards() as $leaderboardArr) {
            $denormalizedLeaderboards[] = $this->serializer->denormalize(
                $leaderboardArr,
                LeaderboardBatchUpdateSingleDto::class
            );
            $this->dtoValidator->validatePartial($denormalizedLeaderboards[count($denormalizedLeaderboards) - 1]);
        }

        /** @var LeaderboardInterface[] $updatedLeaderboards */
        $updatedLeaderboards = [];
        try {
            foreach ($denormalizedLeaderboards as $leaderboardDto) {
                $foundLeaderboard = $this->leaderboardRepository->findById($leaderboardDto->getId());
                if ($foundLeaderboard === null) {
                    throw new ResourceNotFoundException(
                        'Could not find leaderboard with id ' . $leaderboardDto->getId()
                    );
                }

                $updatedLeaderboards[] = $this->leaderboardRepository->updateOne(
                    $foundLeaderboard,
                    $leaderboardDto,
                    true
                );
            }

            foreach ($updatedLeaderboards as $leaderboard) {
                $this->leaderboardSeasonAndTeamRelatedValidation->validate($leaderboard);
                $this->seasonTeamOnlyOnOneLeaderboardValidation->validate($leaderboard, [
                    'batchUpdateItems' => $updatedLeaderboards,
                ]);
            }
        } catch (
            WrongSeasonTeamSelectedException
            | SeasonTeamAlreadyOnLeaderboardException
            | UniqueConstraintViolationException $exception
        ) {
            $exception instanceof UniqueConstraintViolationException
                ? throw new SeasonTeamAlreadyOnLeaderboardException()
                : throw $exception;
        }

        $this->leaderboardRepository->flushUpdateOne();

        return $this->json(
            array_map(
                fn($leaderboard) => $this->singleObjectResponseFactory->fromObject(
                    $leaderboard,
                    LeaderboardGetDto::class
                ),
                $updatedLeaderboards,
            ),
            HttpCode::OK
        );
    }

    #[Route('/api/leaderboards/{id}', name: 'api.leaderboards.get_by_id', methods: ['GET'])]
    #[OA\Tag(name: 'Leaderboards')]
    #[OA\Parameter(
        name: 'id',
        description: 'The id of the leaderboard to retrieve.',
        in: 'path',
        required: true
    )]
    #[OA\Response(
        response: 200,
        description: 'Returns the leaderboard with the given id.',
        content: new Model(type: LeaderboardGetDto::class)
    )]
    #[OA\Response(
        response: 404,
        description: 'Leaderboard not found.',
    )]
    public function getById(string $id): JsonResponse
    {
        $leaderboard = $this->leaderboardRepository->findById($id);
        if ($leaderboard === null) {
            throw new ResourceNotFoundException();
        }

        return $this->json($this->singleObjectResponseFactory->fromObject(
            $leaderboard,
            LeaderboardGetDto::class
        ));
    }

    #[Route('/api/leaderboards', name: 'api.leaderboards.collection', methods: ['GET'])]
    #[OA\Tag(name: 'Leaderboards')]
    #[OA\Response(
        response: 200,
        description: 'Returns the leaderboards that match the filter.',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: LeaderboardGetDto::class))
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
            LeaderboardGetInterface::class
        );

        $paginatedLeaderboards = $this->leaderboardRepository->findByHttpQuery($httpQuery);

        return $this->json(
            $this->paginatedResponseFactory->fromPaginatedQueryResultInterface(
                $paginatedLeaderboards,
                LeaderboardGetDto::class
            )
        );
    }

    #[Route('/api/leaderboards/{id}', name: 'api.leaderboards.update', methods: ['PUT'])]
    #[OA\Tag(name: 'Leaderboards')]
    #[OA\Parameter(
        name: 'id',
        description: 'The id of the leaderboard to update.',
        in: 'path',
        required: true
    )]
    #[OA\RequestBody(
        required: true,
        content: new Model(type: LeaderboardUpdateDto::class)
    )]
    #[OA\Response(
        response: HttpCode::OK,
        description: 'Returns instance of the leaderboard.',
        content: new Model(type: LeaderboardGetDto::class)
    )]
    #[OA\Response(
        response: HttpCode::BAD_REQUEST,
        description: 'Season team is already on a leaderboard or season team is not related to season.'
    )]
    #[OA\Response(
        response: HttpCode::FORBIDDEN,
        description: 'Forbidden.'
    )]
    #[OA\Response(
        response: HttpCode::NOT_FOUND,
        description: 'Leaderboard not found.',
    )]
    #[OA\Response(
        response: HttpCode::UNPROCESSABLE_ENTITY,
        description: 'Invalid input.'
    )]
    public function update(Request $request, string $id): JsonResponse
    {
        $existingLeaderboard = $this->leaderboardRepository->findById($id);

        if ($existingLeaderboard === null) {
            throw new ResourceNotFoundException();
        }

        $this->denyAccessUnlessGranted(LeaderboardVoter::UPDATE, $existingLeaderboard);

        /** @var LeaderboardUpdateDto $dto */
        $dto = $this->serializer->denormalize(
            $request->getPayload()->all(),
            LeaderboardUpdateDto::class,
        );

        $this->dtoValidator->validatePartial($dto);

        $updatedLeaderboard = $this->leaderboardRepository->updateOne($existingLeaderboard, $dto, true);
        $this->leaderboardSeasonAndTeamRelatedValidation->validate($updatedLeaderboard);
        $this->seasonTeamOnlyOnOneLeaderboardValidation->validate($updatedLeaderboard);
        $this->leaderboardRepository->flushUpdateOne();

        return $this->json(
            $this->singleObjectResponseFactory->fromObject(
                $updatedLeaderboard,
                LeaderboardGetDto::class
            )
        );
    }

    #[Route('/api/leaderboards/{id}', name: 'api.leaderboards.delete', methods: ['DELETE'])]
    #[OA\Tag(name: 'Leaderboards')]
    #[OA\Response(
        response: HttpCode::NO_CONTENT,
        description: 'Leaderboard deleted.',
    )]
    #[OA\Response(
        response: HttpCode::FORBIDDEN,
        description: 'Forbidden.'
    )]
    #[OA\Response(
        response: HttpCode::NOT_FOUND,
        description: 'Leaderboard not found.',
    )]
    public function delete(string $id): JsonResponse
    {
        $leaderboard = $this->leaderboardRepository->findById($id);

        if ($leaderboard === null) {
            throw new ResourceNotFoundException();
        }

        $this->denyAccessUnlessGranted(LeaderboardVoter::DELETE, $leaderboard);

        $this->leaderboardRepository->delete($id);

        return $this->json('', HttpCode::NO_CONTENT);
    }
}
