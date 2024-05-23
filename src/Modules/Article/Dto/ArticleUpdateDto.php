<?php

namespace App\Modules\Article\Dto;

use App\Common\Dto\DtoPropertyRelatedToEntity;
use App\Common\Dto\NotIncludedInBody;
use App\Common\Dto\NotIncludedInBodyTrait;
use App\Common\OAAttributes\OARoleBasedProperty;
use App\Common\Serialization\RoleSerializationGroup;
use App\Modules\Article\Model\ArticleUpdatableInterface;
use App\Modules\SeasonTeam\Model\SeasonTeamInterface;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

readonly class ArticleUpdateDto implements ArticleUpdatableInterface
{
    use NotIncludedInBodyTrait;

    public function __construct(
        private string|null|NotIncludedInBody $title = new NotIncludedInBody(),
        private string|null|NotIncludedInBody $content = new NotIncludedInBody(),
        private bool|null|NotIncludedInBody $draft = new NotIncludedInBody(),
        private string|null|NotIncludedInBody $postAt = new NotIncludedInBody(),
        private array|null|NotIncludedInBody $seasonTeamsId = new NotIncludedInBody()
    ) {
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Article title.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    public function getTitle(): string|null
    {
        return $this->toValueOrNull($this->title);
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Article content.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\NotBlank]
    public function getContent(): string|null
    {
        return $this->toValueOrNull($this->content);
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Article draft.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\NotNull]
    #[Assert\Type('bool')]
    public function isDraft(): bool|null
    {
        return $this->toValueOrNull($this->draft);
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Article post at.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\NotBlank]
    #[Assert\Type('string')]
    #[Assert\DateTime]
    public function getPostAt(): string|null
    {
        return $this->toValueOrNull($this->postAt);
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Season team ids.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[DtoPropertyRelatedToEntity(SeasonTeamInterface::class)]
    #[Assert\NotNull]
    #[Assert\Type('array')]
    #[Assert\All(constraints: [
        new Assert\Uuid()
    ])]
    public function getSeasonTeamsId(): array|null
    {
        return $this->toValueOrNull($this->seasonTeamsId);
    }
}
