<?php

namespace App\Modules\Article\Model\MariaDB;

use App\Common\Model\MariaDB\ModelUuidTrait;
use App\Common\Timestamp\TimestampableTrait;
use App\Modules\Article\Model\ArticleInterface;
use App\Modules\Article\Repository\MariaDB\ArticleRepository;
use App\Modules\SeasonTeam\Model\MariaDB\SeasonTeam;
use App\Modules\SeasonTeam\Model\SeasonTeamInterface;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: ArticleRepository::class)]
#[Table(name: 'article')]
#[HasLifecycleCallbacks]
class Article implements ArticleInterface
{
    use ModelUuidTrait;
    use TimestampableTrait;

    #[Column(type: 'string', length: 255)]
    protected string $title;

    #[Column(type: 'text')]
    protected string $content;

    #[Column(type: 'boolean')]
    protected bool $draft;

    #[Column(type: 'datetime', nullable: true)]
    protected ?DateTimeInterface $postAt;

    #[ManyToMany(targetEntity: SeasonTeam::class, mappedBy: 'articles', cascade: ['all'])]
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

    public function setPostAt(?DateTimeInterface $postAt): static
    {
        $this->postAt = $postAt;

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
