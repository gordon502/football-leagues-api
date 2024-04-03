<?php

namespace App\Modules\User;

use App\Common\Response\ResourceNotFoundException;
use App\Common\Serialization\RoleBasedSerializer;
use App\Modules\User\Dto\UserGetDto;
use App\Modules\User\Repository\UserRepositoryInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    public function __construct(
        #[Autowire(service: 'user_repository')]
        private readonly UserRepositoryInterface $userRepository,
        private readonly RoleBasedSerializer $serializer
    ) {
    }

    #[Route('/api/users/{id}', name: 'api.users.get_by_id', methods: ['GET'])]
    #[OA\Tag(name: 'Users')]
    #[Security(name: "Bearer")]
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
    public function getById(int|string $id): JsonResponse
    {
        $user = $this->userRepository->findById($id);
        if ($user === null) {
            throw new ResourceNotFoundException();
        }

        return $this->json($this->serializer->normalize(new UserGetDto($user)));
    }
}
