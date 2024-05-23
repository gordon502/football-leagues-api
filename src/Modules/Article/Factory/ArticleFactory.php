<?php

namespace App\Modules\Article\Factory;

use App\Common\Repository\Exception\RelatedEntityNotFoundException;
use App\Modules\Article\Model\ArticleCreatableInterface;
use App\Modules\Article\Model\ArticleInterface;
use App\Modules\SeasonTeam\Repository\SeasonTeamRepositoryInterface;
use DateTime;
use InvalidArgumentException;
use ReflectionClass;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

readonly class ArticleFactory implements ArticleFactoryInterface
{
    public function __construct(
        #[Autowire(service: 'season_team_repository')]
        private SeasonTeamRepositoryInterface $seasonTeamRepository,
    ) {
    }

    public function create(
        ArticleCreatableInterface $articleCreatable,
        string $modelClass
    ): ArticleInterface {
        $reflection = new ReflectionClass($modelClass);
        if (!$reflection->implementsInterface(ArticleInterface::class)) {
            throw new InvalidArgumentException(
                'Model class must implement ' . ArticleInterface::class
            );
        }

        /** @var ArticleInterface $model */
        $model = new $modelClass();

        $model->setTitle($articleCreatable->getTitle());
        $model->setContent($articleCreatable->getContent());
        $model->setDraft($articleCreatable->isDraft());
        $model->setPostAt(
            $articleCreatable->getPostAt()
                ? DateTime::createFromFormat('Y-m-d H:i:s', $articleCreatable->getPostAt())
                : null
        );

        foreach ($articleCreatable->getSeasonTeamsId() as $seasonTeamId) {
            $seasonTeam = $this->seasonTeamRepository->findById($seasonTeamId);
            if (!$seasonTeam) {
                throw new RelatedEntityNotFoundException(
                    'Season team not found with id ' . $seasonTeamId
                );
            }

            $model->addSeasonTeams($seasonTeam);
        }

        return $model;
    }
}
