<?php

namespace App\Modules\Round\Model\MariaDB;

use App\Common\Model\MariaDB\ModelUuidTrait;
use App\Common\Timestamp\TimestampableTrait;
use App\Modules\Round\Model\RoundInterface;
use App\Modules\Round\Repository\MariaDB\RoundRepository;
use App\Modules\Season\Model\MariaDB\Season;
use App\Modules\Season\Model\SeasonInterface;
use DateTimeInterface;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\ManyToOne;
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
    protected SeasonInterface $season;

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

    public function setNumber(int $number): static
    {
        $this->number = $number;

        return $this;
    }

    public function setStandardStartDate(DateTimeInterface $standardStartDate): static
    {
        $this->standardStartDate = $standardStartDate;

        return $this;
    }

    public function setStandardEndDate(DateTimeInterface $standardEndDate): static
    {
        $this->standardEndDate = $standardEndDate;

        return $this;
    }

    public function setSeason(SeasonInterface $season): static
    {
        $this->season = $season;

        return $this;
    }
}
