<?php

namespace App\Modules\Article\Dto;

use App\Common\OAAttributes\OARoleBasedProperty;
use App\Common\Serialization\RoleSerializationGroup;
use App\Modules\Article\Model\ArticleCreatableInterface;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class ArticleCreateDto implements ArticleCreatableInterface
{
    private string|null $title;
    private string|null $content;
    private bool|null $draft;
    private string|null $postAt;
    private array|null $seasonTeamsId;

    public function __construct(
        string|null $title,
        string|null $content,
        bool|null $draft,
        string|null $postAt,
        array|null $seasonTeamsId
    ) {
        $this->title = $title;
        $this->content = $content;
        $this->draft = $draft;
        $this->postAt = $postAt;
        $this->seasonTeamsId = $seasonTeamsId;
    }

    #[OARoleBasedProperty('Article title.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR,
    ])]
    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR,
    ])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    public function getTitle(): string|null
    {
        return $this->title;
    }

    #[OARoleBasedProperty('Article content.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR,
    ])]
    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR,
    ])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 3000)]
    #[Assert\Type(['string'])]
    public function getContent(): string|null
    {
        return $this->content;
    }

    #[OARoleBasedProperty('Article draft.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR,
    ])]
    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR,
    ])]
    #[Assert\NotNull]
    #[Assert\Type(['bool'])]
    public function isDraft(): bool|null
    {
        return $this->draft;
    }

    #[OARoleBasedProperty('Article post at.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR,
    ])]
    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR,
    ])]
    #[Assert\NotNull]
    #[Assert\Type(['string'])]
    #[Assert\DateTime]
    public function getPostAt(): ?string
    {
        return $this->postAt;
    }

    #[OARoleBasedProperty('Season team ids.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR,
    ])]
    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR,
    ])]
    #[Assert\NotNull]
    #[Assert\Type(['array'])]
    #[Assert\All([
        'constraints' => [
            new Assert\Uuid(),
        ],
    ])]
    public function getSeasonTeamsId(): array|null
    {
        return $this->seasonTeamsId;
    }
}
