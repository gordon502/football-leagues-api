<?php

namespace App\Modules\SeasonTeam\Model\MongoDB;

use App\Common\Model\MongoDB\ModelUuidTrait;
use App\Common\Timestamp\TimestampableTrait;
use App\Modules\Article\Model\ArticleInterface;
use App\Modules\Article\Model\MongoDB\Article;
use App\Modules\Game\Model\GameInterface;
use App\Modules\Game\Model\MongoDB\Game;
use App\Modules\Leaderboard\Model\LeaderboardInterface;
use App\Modules\Leaderboard\Model\MongoDB\Leaderboard;
use App\Modules\Season\Model\MongoDB\Season;
use App\Modules\Season\Model\SeasonInterface;
use App\Modules\SeasonTeam\Model\SeasonTeamInterface;
use App\Modules\Team\Model\MongoDB\Team;
use App\Modules\Team\Model\TeamInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations\Field;
use Doctrine\ODM\MongoDB\Mapping\Annotations\HasLifecycleCallbacks;
use Doctrine\ODM\MongoDB\Mapping\Annotations\PreRemove;
use Doctrine\ODM\MongoDB\Mapping\Annotations\ReferenceMany;
use Doctrine\ODM\MongoDB\Mapping\Annotations\ReferenceOne;

#[Document(collection: 'season_team')]
#[HasLifecycleCallbacks]
class SeasonTeam implements SeasonTeamInterface
{
    use ModelUuidTrait;
    use TimestampableTrait;

    #[Field(type: 'string')]
    protected string $name;

    #[ReferenceOne(targetDocument: Team::class, inversedBy: 'seasonTeams')]
    protected TeamInterface $team;

    #[ReferenceOne(targetDocument: Season::class, inversedBy: 'seasonTeams')]
    protected SeasonInterface $season;

    #[ReferenceMany(targetDocument: Game::class, mappedBy: 'seasonTeam1')]
    protected Collection $gamesAsTeam1;

    #[ReferenceMany(targetDocument: Game::class, mappedBy: 'seasonTeam2')]
    protected Collection $gamesAsTeam2;

    #[ReferenceMany(targetDocument: Article::class, inversedBy: 'seasonTeams')]
    protected Collection $articles;

    #[ReferenceOne(targetDocument: Leaderboard::class, cascade: ['all'], orphanRemoval: true, mappedBy: 'seasonTeam')]
    protected LeaderboardInterface $leaderboard;

    public function __construct()
    {
        $this->gamesAsTeam1 = new ArrayCollection();
        $this->gamesAsTeam2 = new ArrayCollection();
        $this->articles = new ArrayCollection();
    }

    #[PreRemove]
    public function preRemove(): void
    {
        /** @var GameInterface $game */
        foreach ($this->gamesAsTeam1 as $game) {
            $game->setSeasonTeam1(null);
        }
        foreach ($this->gamesAsTeam2 as $game) {
            $game->setSeasonTeam2(null);
        }
        /** @var ArticleInterface $article */
        foreach ($this->articles as $article) {
            $article->getSeasonTeams()->removeElement($this);
        }
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTeam(): TeamInterface
    {
        return $this->team;
    }

    public function getSeason(): SeasonInterface
    {
        return $this->season;
    }

    public function getGamesAsTeam1(): Collection
    {
        return $this->gamesAsTeam1;
    }

    public function getGamesAsTeam2(): Collection
    {
        return $this->gamesAsTeam2;
    }

    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function getLeaderboard(): LeaderboardInterface
    {
        return $this->leaderboard;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function setTeam(TeamInterface $team): static
    {
        $this->team = $team;

        return $this;
    }

    public function setSeason(SeasonInterface $season): static
    {
        $this->season = $season;

        return $this;
    }

    public function addArticle(ArticleInterface $article): static
    {
        if (!$this->articles->contains($article)) {
            $this->articles->add($article);
        }

        return $this;
    }

    public function removeArticle(ArticleInterface $article): static
    {
        $this->articles->removeElement($article);

        return $this;
    }

    public function clearArticles(): static
    {
        $this->articles->clear();

        return $this;
    }
}
