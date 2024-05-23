<?php

namespace App\Modules\Article\Dto;

use App\Common\OAAttributes\OARoleBasedProperty;
use App\Common\Serialization\RoleSerializationGroup;
use App\Modules\Article\Model\ArticleGetInterface;
use Symfony\Component\Serializer\Attribute\Groups;

readonly class ArticleGetDto
{
    public function __construct(
        private ArticleGetInterface $article
    ) {
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Article id.', RoleSerializationGroup::ALL)]
    public function getId(): string
    {
        return $this->article->getId();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Article title.', RoleSerializationGroup::ALL)]
    public function getTitle(): string
    {
        return $this->article->getTitle();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Article content.', RoleSerializationGroup::ALL)]
    public function getContent(): string
    {
        return $this->article->getContent();
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR,
    ])]
    #[OARoleBasedProperty(
        'Article draft.',
        [
            RoleSerializationGroup::ADMIN,
            RoleSerializationGroup::MODERATOR,
            RoleSerializationGroup::EDITOR,
        ]
    )]
    public function isDraft(): bool
    {
        return $this->article->isDraft();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Article post at.', RoleSerializationGroup::ALL)]
    public function getPostAt(): ?string
    {
        return $this->article->getPostAt()?->format('Y-m-d H:i:s');
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Article related season teams.', RoleSerializationGroup::ALL)]
    public function getSeasonTeamsId(): array
    {
        return $this->article->getSeasonTeams()->map(
            fn ($seasonTeam) => $seasonTeam->getId()
        )->toArray();
    }
}
