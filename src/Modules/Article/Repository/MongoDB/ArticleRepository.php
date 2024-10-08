<?php

namespace App\Modules\Article\Repository\MongoDB;

use App\Common\Repository\MongoDB\DeleteTrait;
use App\Common\Repository\MongoDB\FindByHttpQueryTrait;
use App\Common\Repository\MongoDB\UpdateOneTrait;
use App\Modules\Article\Factory\ArticleFactoryInterface;
use App\Modules\Article\Model\ArticleCreatableInterface;
use App\Modules\Article\Model\ArticleInterface;
use App\Modules\Article\Model\MongoDB\Article;
use App\Modules\Article\Repository\ArticleRepositoryInterface;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

class ArticleRepository extends DocumentRepository implements ArticleRepositoryInterface
{
    use FindByHttpQueryTrait;
    use UpdateOneTrait;
    use DeleteTrait;

    private readonly ArticleFactoryInterface $articleFactory;

    public function __construct(DocumentManager $dm, ArticleFactoryInterface $articleFactory)
    {
        $this->articleFactory = $articleFactory;

        $uow = $dm->getUnitOfWork();
        $classMetadata = $dm->getClassMetadata(Article::class);

        parent::__construct($dm, $uow, $classMetadata);
    }

    public function create(object $object): ArticleInterface
    {
        if (!$object instanceof ArticleCreatableInterface) {
            throw new \InvalidArgumentException(
                'Argument 1 must be an instance of ' . ArticleCreatableInterface::class
            );
        }

        $article = $this->articleFactory->create(
            $object,
            Article::class
        );

        $this->getDocumentManager()->persist($article);
        $this->getDocumentManager()->flush();

        return $article;
    }

    public function findById(string $id): ?ArticleInterface
    {
        return $this->find($id);
    }
}
