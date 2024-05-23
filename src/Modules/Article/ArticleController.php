<?php

namespace App\Modules\Article;

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
use App\Modules\Article\Model\ArticleGetInterface;
use App\Modules\Article\Dto\ArticleCreateDto;
use App\Modules\Article\Dto\ArticleGetDto;
use App\Modules\Article\Dto\ArticleUpdateDto;
use App\Modules\Article\Repository\ArticleRepositoryInterface;
use App\Modules\Article\Voter\ArticleVoter;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class ArticleController extends AbstractController
{
    public function __construct(
        #[Autowire(service: 'article_repository')]
        private readonly ArticleRepositoryInterface $articleRepository,
        private readonly RoleBasedSerializerInterface $serializer,
        private readonly DtoValidatorInterface $dtoValidator,
        private readonly HttpQueryHandlerInterface $httpQueryHandler,
        private readonly SingleObjectResponseFactoryInterface $singleObjectResponseFactory,
        private readonly PaginatedResponseFactoryInterface $paginatedResponseFactory,
    ) {
    }

    #[Route('/api/articles', name: 'api.articles.create', methods: ['POST'])]
    #[OA\Tag(name: 'Articles')]
    #[OA\RequestBody(
        required: true,
        content: new Model(type: ArticleCreateDto::class)
    )]
    #[OA\Response(
        response: HttpCode::CREATED,
        description: 'Returns instance of the created article.',
        content: new Model(type: ArticleGetDto::class)
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
        $this->denyAccessUnlessGranted(ArticleVoter::CREATE);

        /** @var ArticleCreateDto $dto */
        $dto = $this->serializer->denormalize(
            $request->getPayload()->all(),
            ArticleCreateDto::class
        );
        $this->dtoValidator->validate($dto);

        $article = $this->articleRepository->create($dto);

        return $this->json(
            $this->singleObjectResponseFactory->fromObject(
                $article,
                ArticleGetDto::class
            ),
            HttpCode::CREATED
        );
    }

    #[Route('/api/articles/{id}', name: 'api.articles.get_by_id', methods: ['GET'])]
    #[OA\Tag(name: 'Articles')]
    #[OA\Parameter(
        name: 'id',
        description: 'The id of the article to retrieve.',
        in: 'path',
        required: true
    )]
    #[OA\Response(
        response: 200,
        description: 'Returns the article with the given id.',
        content: new Model(type: ArticleGetDto::class)
    )]
    #[OA\Response(
        response: 404,
        description: 'Article not found.',
    )]
    public function getById(string $id): JsonResponse
    {
        $article = $this->articleRepository->findById($id);
        if ($article === null) {
            throw new ResourceNotFoundException();
        }

        return $this->json($this->singleObjectResponseFactory->fromObject(
            $article,
            ArticleGetDto::class
        ));
    }

    #[Route('/api/articles', name: 'api.articles.collection', methods: ['GET'])]
    #[OA\Tag(name: 'Articles')]
    #[OA\Response(
        response: 200,
        description: 'Returns the articles that match the filter.',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: ArticleGetDto::class))
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
            ArticleGetInterface::class
        );

        $paginatedArticles = $this->articleRepository->findByHttpQuery($httpQuery);

        return $this->json(
            $this->paginatedResponseFactory->fromPaginatedQueryResultInterface(
                $paginatedArticles,
                ArticleGetDto::class
            )
        );
    }

    #[Route('/api/articles/{id}', name: 'api.articles.update', methods: ['PUT'])]
    #[OA\Tag(name: 'Articles')]
    #[OA\Parameter(
        name: 'id',
        description: 'The id of the article to update.',
        in: 'path',
        required: true
    )]
    #[OA\RequestBody(
        required: true,
        content: new Model(type: ArticleUpdateDto::class)
    )]
    #[OA\Response(
        response: HttpCode::OK,
        description: 'Returns instance of the article.',
        content: new Model(type: ArticleGetDto::class)
    )]
    #[OA\Response(
        response: HttpCode::FORBIDDEN,
        description: 'Forbidden.'
    )]
    #[OA\Response(
        response: HttpCode::NOT_FOUND,
        description: 'Article not found.',
    )]
    #[OA\Response(
        response: HttpCode::UNPROCESSABLE_ENTITY,
        description: 'Invalid input.'
    )]
    public function update(Request $request, string $id): JsonResponse
    {
        $existingArticle = $this->articleRepository->findById($id);

        if ($existingArticle === null) {
            throw new ResourceNotFoundException();
        }

        $this->denyAccessUnlessGranted(ArticleVoter::UPDATE, $existingArticle);

        /** @var ArticleUpdateDto $dto */
        $dto = $this->serializer->denormalize(
            $request->getPayload()->all(),
            ArticleUpdateDto::class,
        );

        $this->dtoValidator->validatePartial($dto);

        $updatedArticle = $this->articleRepository->updateOne($existingArticle, $dto);

        return $this->json(
            $this->singleObjectResponseFactory->fromObject(
                $updatedArticle,
                ArticleGetDto::class
            )
        );
    }

    #[Route('/api/articles/{id}', name: 'api.articles.delete', methods: ['DELETE'])]
    #[OA\Tag(name: 'Articles')]
    #[OA\Response(
        response: HttpCode::NO_CONTENT,
        description: 'Article deleted.',
    )]
    #[OA\Response(
        response: HttpCode::FORBIDDEN,
        description: 'Forbidden.'
    )]
    #[OA\Response(
        response: HttpCode::NOT_FOUND,
        description: 'Article not found.',
    )]
    public function delete(string $id): JsonResponse
    {
        $article = $this->articleRepository->findById($id);

        if ($article === null) {
            throw new ResourceNotFoundException();
        }

        $this->denyAccessUnlessGranted(ArticleVoter::DELETE, $article);

        $this->articleRepository->delete($id);

        return $this->json('', HttpCode::NO_CONTENT);
    }
}
