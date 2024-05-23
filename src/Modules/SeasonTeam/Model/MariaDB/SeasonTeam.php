<?php

namespace App\Modules\SeasonTeam\Model\MariaDB;

use App\Common\Model\MariaDB\ModelUuidTrait;
use App\Common\Timestamp\TimestampableTrait;
use App\Modules\Article\Model\ArticleInterface;
use App\Modules\Article\Model\MariaDB\Article;
use App\Modules\Game\Model\MariaDB\Game;
use App\Modules\Season\Model\MariaDB\Season;
use App\Modules\Season\Model\SeasonInterface;
use App\Modules\SeasonTeam\Model\SeasonTeamInterface;
use App\Modules\SeasonTeam\Repository\MariaDB\SeasonTeamRepository;
use App\Modules\Team\Model\MariaDB\Team;
use App\Modules\Team\Model\TeamInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: SeasonTeamRepository::class)]
#[Table(name: 'season_team')]
#[HasLifecycleCallbacks]
class SeasonTeam implements SeasonTeamInterface
{
    use ModelUuidTrait;
    use TimestampableTrait;

    #[Column(type: 'string')]
    protected string $name;

    #[ManyToOne(targetEntity: Team::class, inversedBy: 'seasonTeams')]
    #[JoinColumn(name: 'team_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    protected TeamInterface $team;

    #[ManyToOne(targetEntity: Season::class, inversedBy: 'seasonTeams')]
    #[JoinColumn(name: 'season_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    protected SeasonInterface $season;

    #[OneToMany(targetEntity: Game::class, mappedBy: 'seasonTeam1', cascade: ['all'])]
    #[JoinColumn(name: 'season_team1_id', referencedColumnName: 'id', onDelete: 'SET NULL')]
    protected Collection $gamesAsTeam1;

    #[OneToMany(targetEntity: Game::class, mappedBy: 'seasonTeam2', cascade: ['all'])]
    #[JoinColumn(name: 'season_team2_id', referencedColumnName: 'id', onDelete: 'SET NULL')]
    protected Collection $gamesAsTeam2;

    #[ManyToMany(targetEntity: Article::class, inversedBy: 'seasonTeams', cascade: ['all'])]
    protected Collection $articles;

    public function __construct()
    {
        $this->gamesAsTeam1 = new ArrayCollection();
        $this->gamesAsTeam2 = new ArrayCollection();
        $this->articles = new ArrayCollection();
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
