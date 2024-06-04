<?php

namespace App\Modules\OrganizationalUnit;

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
use App\Modules\OrganizationalUnit\Dto\OrganizationalUnitCreateDto;
use App\Modules\OrganizationalUnit\Dto\OrganizationalUnitGetDto;
use App\Modules\OrganizationalUnit\Dto\OrganizationalUnitUpdateDto;
use App\Modules\OrganizationalUnit\Model\OrganizationalUnitGetInterface;
use App\Modules\OrganizationalUnit\Repository\OrganizationalUnitRepositoryInterface;
use App\Modules\OrganizationalUnit\Voter\OrganizationalUnitVoter;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class OrganizationalUnitController extends AbstractController
{
    public function __construct(
        #[Autowire(service: 'organizational_unit_repository')]
        private readonly OrganizationalUnitRepositoryInterface $organizationalUnitRepository,
        private readonly RoleBasedSerializerInterface $serializer,
        private readonly DtoValidatorInterface $dtoValidator,
        private readonly HttpQueryHandlerInterface $httpQueryHandler,
        private readonly SingleObjectResponseFactoryInterface $singleObjectResponseFactory,
        private readonly PaginatedResponseFactoryInterface $paginatedResponseFactory,
    ) {
    }

    #[Route('/api/organizational-units', name: 'api.organizational_units.create', methods: ['POST'])]
    #[OA\Tag(name: 'Organizational Units')]
    #[OA\RequestBody(
        required: true,
        content: new Model(type: OrganizationalUnitCreateDto::class)
    )]
    #[OA\Response(
        response: HttpCode::CREATED,
        description: 'Returns instance of the created organizational unit.',
        content: new Model(type: OrganizationalUnitGetDto::class)
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
        $this->denyAccessUnlessGranted(OrganizationalUnitVoter::CREATE);

        /** @var OrganizationalUnitCreateDto $dto */
        $dto = $this->serializer->denormalize(
            $request->getPayload()->all(),
            OrganizationalUnitCreateDto::class
        );
        $this->dtoValidator->validate($dto);

        $organizationalUnit = $this->organizationalUnitRepository->create($dto);

        return $this->json(
            $this->singleObjectResponseFactory->fromObject(
                $organizationalUnit,
                OrganizationalUnitGetDto::class
            ),
            HttpCode::CREATED
        );
    }

    #[Route('/api/organizational-units/{id}', name: 'api.organizational_units.get_by_id', methods: ['GET'])]
    #[OA\Tag(name: 'Organizational Units')]
    #[OA\Parameter(
        name: 'id',
        description: 'The id of the organizational unit to retrieve.',
        in: 'path',
        required: true
    )]
    #[OA\Response(
        response: 200,
        description: 'Returns the organizational unit with the given id.',
        content: new Model(type: OrganizationalUnitGetDto::class)
    )]
    #[OA\Response(
        response: 404,
        description: 'Organizational unit not found.',
    )]
    public function getById(string $id): JsonResponse
    {
        $organizationalUnit = $this->organizationalUnitRepository->findById($id);
        if ($organizationalUnit === null) {
            throw new ResourceNotFoundException();
        }

        return $this->json($this->singleObjectResponseFactory->fromObject(
            $organizationalUnit,
            OrganizationalUnitGetDto::class
        ));
    }

    #[Route('/api/organizational-units', name: 'api.organizational_units.collection', methods: ['GET'])]
    #[OA\Tag(name: 'Organizational Units')]
    #[OA\Response(
        response: 200,
        description: 'Returns the organizational units that match the filter.',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: OrganizationalUnitGetDto::class))
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
            OrganizationalUnitGetInterface::class
        );

        $paginatedOrganizationalUnits = $this->organizationalUnitRepository->findByHttpQuery($httpQuery);

        return $this->json(
            $this->paginatedResponseFactory->fromPaginatedQueryResultInterface(
                $paginatedOrganizationalUnits,
                OrganizationalUnitGetDto::class
            )
        );
    }

    #[Route('/api/organizational-units/{id}', name: 'api.organizational_units.update', methods: ['PUT'])]
    #[OA\Tag(name: 'Organizational Units')]
    #[OA\Parameter(
        name: 'id',
        description: 'The id of the organizational unit to update.',
        in: 'path',
        required: true
    )]
    #[OA\RequestBody(
        required: true,
        content: new Model(type: OrganizationalUnitUpdateDto::class)
    )]
    #[OA\Response(
        response: HttpCode::OK,
        description: 'Returns instance of the organizational unit.',
        content: new Model(type: OrganizationalUnitGetDto::class)
    )]
    #[OA\Response(
        response: HttpCode::UNAUTHORIZED,
        description: 'Unauthorized.',
    )]
    #[OA\Response(
        response: HttpCode::FORBIDDEN,
        description: 'Operation forbidden.',
    )]
    #[OA\Response(
        response: HttpCode::NOT_FOUND,
        description: 'Organizational unit not found.',
    )]
    #[OA\Response(
        response: HttpCode::UNPROCESSABLE_ENTITY,
        description: 'Invalid input.'
    )]
    public function update(Request $request, string $id): JsonResponse
    {
        $existingOrganizationalUnit = $this->organizationalUnitRepository->findById($id);

        if ($existingOrganizationalUnit === null) {
            throw new ResourceNotFoundException();
        }

        $this->denyAccessUnlessGranted(OrganizationalUnitVoter::UPDATE, $existingOrganizationalUnit);

        /** @var OrganizationalUnitUpdateDto $dto */
        $dto = $this->serializer->denormalize(
            $request->getPayload()->all(),
            OrganizationalUnitUpdateDto::class,
        );

        $this->dtoValidator->validatePartial($dto);

        $updatedOrganizationalUnit = $this->organizationalUnitRepository->updateOne($existingOrganizationalUnit, $dto);

        return $this->json(
            $this->singleObjectResponseFactory->fromObject(
                $updatedOrganizationalUnit,
                OrganizationalUnitGetDto::class
            )
        );
    }

    #[Route('/api/organizational-units/{id}', name: 'api.organizational_units.delete', methods: ['DELETE'])]
    #[OA\Tag(name: 'Organizational Units')]
    #[OA\Response(
        response: HttpCode::NO_CONTENT,
        description: 'Organizational unit deleted.',
    )]
    #[OA\Response(
        response: HttpCode::FORBIDDEN,
        description: 'Forbidden.'
    )]
    #[OA\Response(
        response: HttpCode::NOT_FOUND,
        description: 'Organizational unit not found.',
    )]
    public function delete(string $id): JsonResponse
    {
        $organizationalUnit = $this->organizationalUnitRepository->findById($id);

        if ($organizationalUnit === null) {
            throw new ResourceNotFoundException();
        }

        $this->denyAccessUnlessGranted(OrganizationalUnitVoter::DELETE, $organizationalUnit);

        $this->organizationalUnitRepository->delete($id);

        return $this->json('', HttpCode::NO_CONTENT);
    }
}
