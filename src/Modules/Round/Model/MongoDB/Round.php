<?php

namespace App\Modules\Round\Model\MongoDB;

use App\Common\Model\MongoDB\ModelUuidTrait;
use App\Common\Timestamp\TimestampableTrait;
use App\Modules\Game\Model\MongoDB\Game;
use App\Modules\Round\Model\RoundInterface;
use App\Modules\Season\Model\MongoDB\Season;
use App\Modules\Season\Model\SeasonInterface;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations\Field;
use Doctrine\ODM\MongoDB\Mapping\Annotations\HasLifecycleCallbacks;
use Doctrine\ODM\MongoDB\Mapping\Annotations\ReferenceMany;
use Doctrine\ODM\MongoDB\Mapping\Annotations\ReferenceOne;

#[Document(collection: 'round')]
#[HasLifecycleCallbacks]
class Round implements RoundInterface
{
    use ModelUuidTrait;
    use TimestampableTrait;

    #[Field(type: 'int')]
    protected string $number;

    #[Field(type: 'date')]
    protected DateTimeInterface $standardStartDate;

    #[Field(type: 'date')]
    protected DateTimeInterface $standardEndDate;

    #[ReferenceOne(targetDocument: Season::class, inversedBy: 'rounds')]
    protected SeasonInterface $season;

    #[ReferenceMany(targetDocument: Game::class, cascade: ['all'], orphanRemoval: true, mappedBy: 'round')]
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
