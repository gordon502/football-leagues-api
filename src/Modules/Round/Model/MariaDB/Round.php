<?php

namespace App\Modules\Round\Model\MariaDB;

use App\Common\Model\MariaDB\ModelUuidTrait;
use App\Common\Timestamp\TimestampableTrait;
use App\Modules\Game\Model\MariaDB\Game;
use App\Modules\Round\Model\RoundInterface;
use App\Modules\Round\Repository\MariaDB\RoundRepository;
use App\Modules\Season\Model\MariaDB\Season;
use App\Modules\Season\Model\SeasonInterface;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: RoundRepository::class)]
#[Table(name: 'round')]
#[HasLifecycleCallbacks]
class Round implements RoundInterface
{
    use ModelUuidTrait;
    use TimestampableTrait;

    #[Column(type: 'integer')]
    protected string $number;

    #[Column(type: 'date')]
    protected DateTimeInterface $standardStartDate;

    #[Column(type: 'date')]
    protected DateTimeInterface $standardEndDate;

    #[ManyToOne(targetEntity: Season::class, inversedBy: 'rounds')]
    #[JoinColumn(name: 'season_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    protected SeasonInterface $season;

    #[OneToMany(targetEntity: Game::class, mappedBy: 'round', cascade: ['all'], orphanRemoval: true)]
    #[JoinColumn(name: 'round_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    protected Collection $games;

    public function __construct()
    {
        $this->games = new ArrayCollection();
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    public function getStandardStartDate(): DateTimeInterface
    {
        return $this->standardStartDate;
    }

    public function getStandardEndDate(): DateTimeInterface
    {
        return $this->standardEndDate;
    }

    public function getSeason(): SeasonInterface
    {
        return $this->season;
    }

    public function getGames(): Collection
    {
        return $this->games;
    }

    public function setNumber(int $number): static
    {
        $this->number = $number;

        return $this;
    }

    public function setStandardStartDate(DateTimeInterface|string $standardStartDate): static
    {
        $this->standardStartDate = is_string($standardStartDate)
            ? new DateTime($standardStartDate)
            : $standardStartDate;

        return $this;
    }

    public function setStandardEndDate(DateTimeInterface|string $standardEndDate): static
    {
        $this->standardEndDate = is_string($standardEndDate)
            ? new DateTime($standardEndDate)
            : $standardEndDate;

        return $this;
    }

    public function setSeason(SeasonInterface $season): static
    {
        $this->season = $season;

        return $this;
    }
}
