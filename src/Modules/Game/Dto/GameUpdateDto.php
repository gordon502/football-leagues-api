<?php

namespace App\Modules\Game\Dto;

use App\Common\Dto\DtoPropertyRelatedToEntity;
use App\Common\Dto\NotIncludedInBody;
use App\Common\Dto\NotIncludedInBodyTrait;
use App\Common\OAAttributes\OARoleBasedProperty;
use App\Common\Serialization\RoleSerializationGroup;
use App\Modules\Game\Enum\GameResultEnum;
use App\Modules\Game\Model\GameUpdatableInterface;
use App\Modules\Round\Model\RoundInterface;
use App\Modules\SeasonTeam\Model\SeasonTeamInterface;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

readonly class GameUpdateDto implements GameUpdatableInterface
{
    use NotIncludedInBodyTrait;

    public function __construct(
        private string|null|NotIncludedInBody $date = new NotIncludedInBody(),
        private string|null|NotIncludedInBody $stadium = new NotIncludedInBody(),
        private int|null|NotIncludedInBody $team1ScoreHalf = new NotIncludedInBody(),
        private int|null|NotIncludedInBody $team2ScoreHalf = new NotIncludedInBody(),
        private int|null|NotIncludedInBody $team1Score = new NotIncludedInBody(),
        private int|null|NotIncludedInBody $team2Score = new NotIncludedInBody(),
        private GameResultEnum|null|NotIncludedInBody $result = new NotIncludedInBody(),
        private string|null|NotIncludedInBody $viewers = new NotIncludedInBody(),
        private string|null|NotIncludedInBody $annotation = new NotIncludedInBody(),
        private string|null|NotIncludedInBody $roundId = new NotIncludedInBody(),
        private string|null|NotIncludedInBody $seasonTeam1Id = new NotIncludedInBody(),
        private string|null|NotIncludedInBody $seasonTeam2Id = new NotIncludedInBody()
    ) {
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Game date.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\NotBlank]
    #[Assert\DateTime]
    public function getDate(): string|null
    {
        return $this->toValueOrNull($this->date);
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Stadium.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\Length(max: 255)]
    public function getStadium(): string|null
    {
        return $this->toValueOrNull($this->stadium);
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Team 1 score half.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\PositiveOrZero]
    public function getTeam1ScoreHalf(): int|null
    {
        return $this->toValueOrNull($this->team1ScoreHalf);
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Team 2 score half.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\PositiveOrZero]
    public function getTeam2ScoreHalf(): int|null
    {
        return $this->toValueOrNull($this->team2ScoreHalf);
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Team 1 score.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\PositiveOrZero]
    public function getTeam1Score(): int|null
    {
        return $this->toValueOrNull($this->team1Score);
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Team 2 score.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\PositiveOrZero]
    public function getTeam2Score(): int|null
    {
        return $this->toValueOrNull($this->team2Score);
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Game result.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\Choice(choices: [
        GameResultEnum::TEAM_1,
        GameResultEnum::TEAM_2,
        GameResultEnum::DRAW,
        GameResultEnum::NOT_PLAYED,
        GameResultEnum::CANCELLED,
        GameResultEnum::POSTPONED,
        GameResultEnum::TEAM_1_WALKOVER,
        GameResultEnum::TEAM_2_WALKOVER
    ])]
    public function getResult(): ?GameResultEnum
    {
        return $this->toValueOrNull($this->result);
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Viewers.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\Length(max: 255)]
    public function getViewers(): string|null
    {
        return $this->toValueOrNull($this->viewers);
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[OARoleBasedProperty('Annotation.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\Length(max: 255)]
    public function getAnnotation(): string|null
    {
        return $this->toValueOrNull($this->annotation);
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[DtoPropertyRelatedToEntity(RoundInterface::class)]
    #[OARoleBasedProperty('Round ID.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\Uuid]
    public function getRoundId(): string|null
    {
        return $this->toValueOrNull($this->roundId);
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[DtoPropertyRelatedToEntity(SeasonTeamInterface::class)]
    #[OARoleBasedProperty('Season team 1 ID.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\Uuid]
    public function getSeasonTeam1Id(): string|null
    {
        return $this->toValueOrNull($this->seasonTeam1Id);
    }

    #[Groups([
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[DtoPropertyRelatedToEntity(SeasonTeamInterface::class)]
    #[OARoleBasedProperty('Season team 2 ID.', [
        RoleSerializationGroup::ADMIN,
        RoleSerializationGroup::MODERATOR,
        RoleSerializationGroup::EDITOR
    ])]
    #[Assert\Uuid]
    public function getSeasonTeam2Id(): string|null
    {
        return $this->toValueOrNull($this->seasonTeam2Id);
    }
}
