<?php

namespace App\Modules\Article\Repository\MariaDB;

use App\Common\Repository\MariaDB\FindByHttpQueryTrait;
use App\Common\Repository\MariaDB\UpdateOneTrait;
use App\Modules\Article\Factory\ArticleFactoryInterface;
use App\Modules\Article\Model\ArticleCreatableInterface;
use App\Modules\Article\Model\ArticleInterface;
use App\Modules\Article\Model\MariaDB\Article;
use App\Modules\Article\Repository\ArticleRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ArticleRepository extends ServiceEntityRepository implements ArticleRepositoryInterface
{
    use FindByHttpQueryTrait;
    use UpdateOneTrait;

    private readonly ArticleFactoryInterface $articleFactory;

    public function __construct(
        ManagerRegistry $registry,
        ArticleFactoryInterface $articleFactory
    ) {
        $this->articleFactory = $articleFactory;

        parent::__construct($registry, Article::class);
    }

    public function create(object $object): ArticleInterface
    {
        if (!$object instanceof ArticleCreatableInterface) {
            throw new \InvalidArgumentException(
                'Argument 1 must be an instance of ' . ArticleCreatableInterface::class
            );
        }

        /** @var Article $article */
        $article = $this->articleFactory->create(
            $object,
            Article::class
        );

        $em = $this->getEntityManager();
        $em->persist($article);
        $em->flush();

        return $article;
    }

    public function findById(string $id): ?ArticleInterface
    {
        return $this->find($id);
    }

    public function delete(string $id): void
    {
        $this->createQueryBuilder('u')
            ->delete()
            ->where('u.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->execute();
    }
}
