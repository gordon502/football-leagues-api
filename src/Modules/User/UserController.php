<?php

namespace App\Modules\User;

use App\Common\CustomValidation\CustomValidationInterface;
use App\Common\HttpQuery\HttpQueryHandlerInterface;
use App\Common\OAAttributes\OAFilterQueryParameter;
use App\Common\OAAttributes\OASortQueryParameter;
use App\Common\Response\HttpCode;
use App\Common\Response\ResourceNotFoundException;
use App\Common\Serialization\RoleBasedSerializerInterface;
use App\Common\Validator\DtoValidatorInterface;
use App\Modules\User\CustomValidation\UserEmailAlreadyExistsValidation;
use App\Modules\User\Dto\UserCreateDto;
use App\Modules\User\Dto\UserGetDto;
use App\Modules\User\Model\UserGetInterface;
use App\Modules\User\Repository\UserRepositoryInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
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
        private readonly HttpQueryHandlerInterface $httpQueryHandler
    ) {
    }

    #[Route('/api/users', name: 'api.users.register', methods: ['POST'])]
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

        return $this->json($this->serializer->normalize(new UserGetDto($user)), HttpCode::CREATED);
    }

    #[Route('/api/users/{id}', name: 'api.users.get_by_id', methods: ['GET'])]
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
        response: 401,
        description: 'Unauthorized.',
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

        return $this->json($this->serializer->normalize(new UserGetDto($user)));
    }

    #[Route('/api/users', name: 'api.users.filter', methods: ['GET'])]
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
    public function filter(Request $request): JsonResponse
    {
        $httpQuery = $this->httpQueryHandler->handle(
            $request->query,
            UserGetInterface::class
        );

        $users = $this->userRepository->findByHttpQuery($httpQuery);

        return $this->json(
            array_map(fn(UserGetInterface $user) => $this->serializer->normalize(new UserGetDto($user)), $users)
        );
    }
}
