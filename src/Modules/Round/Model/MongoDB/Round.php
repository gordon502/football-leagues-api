<?php

namespace App\Modules\Round\Model\MongoDB;

use App\Common\Model\MongoDB\ModelUuidTrait;
use App\Common\Timestamp\TimestampableTrait;
use App\Modules\Round\Model\RoundInterface;
use App\Modules\Season\Model\MongoDB\Season;
use App\Modules\Season\Model\SeasonInterface;
use DateTimeInterface;
use Doctrine\ODM\MongoDB\Mapping\Annotations\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations\Field;
use Doctrine\ODM\MongoDB\Mapping\Annotations\HasLifecycleCallbacks;
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
