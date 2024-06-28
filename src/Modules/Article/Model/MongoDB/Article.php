<?php

namespace App\Modules\Article\Model\MongoDB;

use App\Common\Model\MongoDB\ModelUuidTrait;
use App\Common\Timestamp\TimestampableTrait;
use App\Modules\Article\Model\ArticleInterface;
use App\Modules\SeasonTeam\Model\MongoDB\SeasonTeam;
use App\Modules\SeasonTeam\Model\SeasonTeamInterface;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations\Field;
use Doctrine\ODM\MongoDB\Mapping\Annotations\HasLifecycleCallbacks;
use Doctrine\ODM\MongoDB\Mapping\Annotations\ReferenceMany;
use Exception;

#[Document(collection: 'article')]
#[HasLifecycleCallbacks]
class Article implements ArticleInterface
{
    use ModelUuidTrait;
    use TimestampableTrait;

    #[Field(type: 'string')]
    protected string $title;

    #[Field(type: 'string')]
    protected string $content;

    #[Field(type: 'boolean')]
    protected bool $draft;

    #[Field(type: 'date', nullable: true)]
    protected ?DateTimeInterface $postAt;

    #[ReferenceMany(targetDocument: SeasonTeam::class, inversedBy: 'articles')]
    protected Collection $seasonTeams;

    public function __construct()
    {
        $this->seasonTeams = new ArrayCollection();
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function isDraft(): bool
    {
        return $this->draft;
    }

    public function getPostAt(): ?DateTimeInterface
    {
        return $this->postAt;
    }

    public function getSeasonTeams(): Collection
    {
        return $this->seasonTeams;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function setDraft(bool $draft): static
    {
        $this->draft = $draft;

        return $this;
    }

    /**
     * @throws Exception
     */
    public function setPostAt(DateTimeInterface|string|null $postAt): static
    {
        $this->postAt = is_string($postAt)
            ? new DateTime($postAt)
            : $postAt;

        return $this;
    }

    public function addSeasonTeams(SeasonTeamInterface $seasonTeam): static
    {
        if (!$this->seasonTeams->contains($seasonTeam)) {
            $this->seasonTeams->add($seasonTeam);
            $seasonTeam->addArticle($this);
        }

        return $this;
    }

    public function removeSeasonTeam(SeasonTeamInterface $seasonTeam): static
    {
        if ($this->seasonTeams->contains($seasonTeam)) {
            $this->seasonTeams->removeElement($seasonTeam);
            $seasonTeam->removeArticle($this);
        }

        return $this;
    }

    public function clearSeasonTeams(): static
    {
        /** @var SeasonTeamInterface $seasonTeam */
        foreach ($this->seasonTeams as $seasonTeam) {
            $seasonTeam->removeArticle($this);
        }

        $this->seasonTeams->clear();

        return $this;
    }
}
