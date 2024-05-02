<?php

namespace App\Modules\User;

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
use App\Modules\User\CustomValidation\UserEmailAlreadyExistsValidation;
use App\Modules\User\Dto\UserCreateDto;
use App\Modules\User\Dto\UserGetDto;
use App\Modules\User\Dto\UserUpdateDto;
use App\Modules\User\Model\UserGetInterface;
use App\Modules\User\Repository\UserRepositoryInterface;
use App\Modules\User\Voter\UserVoter;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    public function __construct(
        #[Autowire(service: 'user_repository')]
        private readonly UserRepositoryInterface $userRepository,
        private readonly RoleBasedSerializerInterface $serializer,
        private readonly DtoValidatorInterface $dtoValidator,
        #[Autowire(service: UserEmailAlreadyExistsValidation::class)]
        private readonly CustomValidationInterface $userEmailAlreadyExistsValidation,
        private readonly HttpQueryHandlerInterface $httpQueryHandler,
        private readonly SingleObjectResponseFactoryInterface $singleObjectResponseFactory,
        private readonly PaginatedResponseFactoryInterface $paginatedResponseFactory,
    ) {
    }

    #[Route('/api/users', name: 'api.users.register', methods: ['POST'])]
    #[Security(name: null)]
    #[OA\Tag(name: 'Users')]
    #[OA\RequestBody(
        required: true,
        content: new Model(type: UserCreateDto::class)
    )]
    #[OA\Response(
        response: HttpCode::CREATED,
        description: 'Returns instance of the created user.',
        content: new Model(type: UserGetDto::class)
    )]
    #[OA\Response(
        response: HttpCode::CONFLICT,
        description: 'Email is already taken.'
    )]
    #[OA\Response(
        response: HttpCode::UNPROCESSABLE_ENTITY,
        description: 'Invalid input.'
    )]
    public function register(Request $request): JsonResponse
    {
        /** @var UserCreateDto $dto */
        $dto = $this->serializer->denormalize($request->getPayload()->all(), UserCreateDto::class);
        $this->dtoValidator->validate($dto);

        $this->userEmailAlreadyExistsValidation->validate($dto->getEmail());

        $user = $this->userRepository->create($dto);

        return $this->json(
            $this->singleObjectResponseFactory->fromObject($user, UserGetDto::class),
            HttpCode::CREATED
        );
    }

    #[Route('/api/users/{id}', name: 'api.users.get_by_id', methods: ['GET'])]
    #[Security(name: null)]
    #[OA\Tag(name: 'Users')]
    #[OA\Parameter(
        name: 'id',
        description: 'The id of the user to retrieve.',
        in: 'path',
        required: true
    )]
    #[OA\Response(
        response: 200,
        description: 'Returns the user with the given id.',
        content: new Model(type: UserGetDto::class)
    )]
    #[OA\Response(
        response: 404,
        description: 'User not found.',
    )]
    public function getById(string $id): JsonResponse
    {
        $user = $this->userRepository->findById($id);
        if ($user === null) {
            throw new ResourceNotFoundException();
        }

        return $this->json($this->singleObjectResponseFactory->fromObject($user, UserGetDto::class));
    }

    #[Route('/api/users', name: 'api.users.collection', methods: ['GET'])]
    #[Security(name: null)]
    #[OA\Tag(name: 'Users')]
    #[OA\Response(
        response: 200,
        description: 'Returns the users that match the filter.',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: UserGetDto::class))
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
            UserGetInterface::class
        );

        $paginatedUsers = $this->userRepository->findByHttpQuery($httpQuery);

        return $this->json(
            $this->paginatedResponseFactory->fromPaginatedQueryResultInterface($paginatedUsers, UserGetDto::class)
        );
    }

    #[Route('/api/users/{id}', name: 'api.users.update', methods: ['PUT'])]
    #[OA\Tag(name: 'Users')]
    #[OA\Parameter(
        name: 'id',
        description: 'The id of the user to update.',
        in: 'path',
        required: true
    )]
    #[OA\RequestBody(
        required: true,
        content: new Model(type: UserUpdateDto::class)
    )]
    #[OA\Response(
        response: HttpCode::OK,
        description: 'Returns instance of the updated user.',
        content: new Model(type: UserGetDto::class)
    )]
    #[OA\Response(
        response: HttpCode::NOT_FOUND,
        description: 'User not found.',
    )]
    #[OA\Response(
        response: HttpCode::CONFLICT,
        description: 'Email is already taken.'
    )]
    #[OA\Response(
        response: HttpCode::UNPROCESSABLE_ENTITY,
        description: 'Invalid input.'
    )]
    public function update(Request $request, string $id): JsonResponse
    {
        $existingUser = $this->userRepository->findById($id);

        if ($existingUser === null) {
            throw new ResourceNotFoundException();
        }

        /** @var UserUpdateDto $dto */
        $dto = $this->serializer->denormalize(
            $request->getPayload()->all(),
            UserUpdateDto::class,
        );

        $this->dtoValidator->validatePartial($dto);

        if ($dto->getEmail()) {
            $this->userEmailAlreadyExistsValidation->validate($dto->getEmail());
        }

        $this->userRepository->updateOne($id, $dto);

        $updatedUser = $this->userRepository->findById($id);

        return $this->json(
            $this->singleObjectResponseFactory->fromObject($updatedUser, UserGetDto::class)
        );
    }

    #[Route('/api/users/{id}', name: 'api.users.delete', methods: ['DELETE'])]
    #[OA\Tag(name: 'Users')]
    #[OA\Response(
        response: HttpCode::NO_CONTENT,
        description: 'User deleted.',
    )]
    #[OA\Response(
        response: HttpCode::FORBIDDEN,
        description: 'Forbidden.'
    )]
    #[OA\Response(
        response: HttpCode::NOT_FOUND,
        description: 'User not found.',
    )]
    public function delete(string $id): JsonResponse
    {
        $user = $this->userRepository->findById($id);

        if ($user === null) {
            throw new ResourceNotFoundException();
        }

        $this->denyAccessUnlessGranted(UserVoter::DELETE, $user);

        $this->userRepository->delete($id);

        return $this->json('', HttpCode::NO_CONTENT);
    }
}
