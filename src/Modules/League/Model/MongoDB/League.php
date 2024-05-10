<?php

namespace App\Modules\League\Model\MongoDB;

use App\Common\Model\MongoDB\ModelUuidTrait;
use App\Common\Timestamp\TimestampableTrait;
use App\Modules\League\Model\LeagueInterface;
use App\Modules\OrganizationalUnit\Model\MongoDB\OrganizationalUnit;
use App\Modules\OrganizationalUnit\Model\OrganizationalUnitInterface;
use App\Modules\Season\Model\MongoDB\Season;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations\Field;
use Doctrine\ODM\MongoDB\Mapping\Annotations\HasLifecycleCallbacks;
use Doctrine\ODM\MongoDB\Mapping\Annotations\ReferenceMany;
use Doctrine\ODM\MongoDB\Mapping\Annotations\ReferenceOne;

#[Document(collection: 'league')]
#[HasLifecycleCallbacks]
class League implements LeagueInterface
{
    use ModelUuidTrait;
    use TimestampableTrait;

    #[Field(type: 'string')]
    private string $name;

    #[Field(type: 'boolean')]
    private bool $active;

    #[Field(type: 'int', nullable: true)]
    private int|null $level;

    #[ReferenceOne(targetDocument: OrganizationalUnit::class, inversedBy: 'leagues')]
    private OrganizationalUnitInterface $organizationalUnit;

    private string $organizationalUnitId;

    #[ReferenceMany(targetDocument: Season::class, cascade: ['all'], orphanRemoval: true, mappedBy: 'league')]
    private Collection $seasons;

    public function __construct()
    {
        $this->seasons = new ArrayCollection();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;

        return $this;
    }

    public function getLevel(): int|null
    {
        return $this->level;
    }

    public function setLevel(int|null $level): static
    {
        $this->level = $level;

        return $this;
    }

    public function getOrganizationalUnit(): OrganizationalUnitInterface
    {
        return $this->organizationalUnit;
    }

    public function setOrganizationalUnit(OrganizationalUnitInterface $organizationalUnit): static
    {
        $this->organizationalUnit = $organizationalUnit;

        return $this;
    }

    public function setOrganizationalUnitId($organizationalUnitId): static
    {
        $this->organizationalUnit = $organizationalUnitId;

        return $this;
    }

    public function getSeasons(): Collection
    {
        return $this->seasons;
    }
}
