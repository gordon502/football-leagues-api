<?php

namespace App\Modules\Game\Dto;

use App\Common\OAAttributes\OARoleBasedProperty;
use App\Common\Serialization\RoleSerializationGroup;
use App\Modules\Game\Model\GameGetInterface;
use App\Modules\Round\Model\RoundGetInterface;
use Symfony\Component\Serializer\Attribute\Groups;

readonly class GameGetDto
{
    public function __construct(
        private GameGetInterface $game
    ) {
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Game ID.', RoleSerializationGroup::ALL)]
    public function getId(): string
    {
        return $this->game->getId();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Game date.', RoleSerializationGroup::ALL)]
    public function getDate(): string
    {
        return $this->game->getDate()->format('Y-m-d H:i:s');
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Stadium.', RoleSerializationGroup::ALL)]
    public function getStadium(): ?string
    {
        return $this->game->getStadium();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Team 1 score half.', RoleSerializationGroup::ALL)]
    public function getTeam1ScoreHalf(): ?int
    {
        return $this->game->getTeam1ScoreHalf();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Team 2 score half.', RoleSerializationGroup::ALL)]
    public function getTeam2ScoreHalf(): ?int
    {
        return $this->game->getTeam2ScoreHalf();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Team 1 score.', RoleSerializationGroup::ALL)]
    public function getTeam1Score(): ?int
    {
        return $this->game->getTeam1Score();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Team 2 score.', RoleSerializationGroup::ALL)]
    public function getTeam2Score(): ?int
    {
        return $this->game->getTeam2Score();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Game result.', RoleSerializationGroup::ALL)]
    public function getResult(): ?string
    {
        return $this->game->getResult();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Viewers.', RoleSerializationGroup::ALL)]
    public function getViewers(): ?string
    {
        return $this->game->getViewers();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Annotation.', RoleSerializationGroup::ALL)]
    public function getAnnotation(): ?string
    {
        return $this->game->getAnnotation();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Round ID.', RoleSerializationGroup::ALL)]
    public function getRoundId(): string
    {
        return $this->game->getRound()->getId();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Season team 1 ID.', RoleSerializationGroup::ALL)]
    public function getSeasonTeam1Id(): ?string
    {
        return $this->game->getSeasonTeam1()?->getId();
    }

    #[Groups(RoleSerializationGroup::ALL)]
    #[OARoleBasedProperty('Season team 2 ID.', RoleSerializationGroup::ALL)]
    public function getSeasonTeam2Id(): ?string
    {
        return $this->game->getSeasonTeam2()?->getId();
    }
}
